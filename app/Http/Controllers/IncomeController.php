<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Kitat;
use App\Models\User; // Import the User model if you need to pass users to the form
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Policies\GenericPolicy;
use Carbon\Carbon;
class IncomeController extends Controller
{
   use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

   protected $genericPolicy;

   public function __construct(GenericPolicy $genericPolicy)
   {
       $this->genericPolicy = $genericPolicy;
   }


    public function index(): View
    {
        if (!$this->genericPolicy->view(Auth::user(), new Income())) {
            abort(403, 'Unauthorized action.');
        }

        $search = request('search');
        $perPage = request('per_page', 10); // Default to 10 if not specified

        $incomes = Income::with(['sourceUser', 'createdByUser', 'updatedByUser'])
                        ->when($search, function ($query, $search) {
                            $query->where('title', 'like', '%' . $search . '%')
                                ->orWhere('description', 'like', '%' . $search . '%');
                        })
                        ->paginate($perPage)
                        ->appends(request()->query()); // Keep search/per_page in pagination links

        $users = User::all();

        $taxRate = Kitat::first()->amount ?? 0;

        foreach ($incomes as $income) {
            if ($income->status === 'pending') {
                $days = Carbon::parse($income->date)->diffInDays(now());
                $income->debt = $taxRate * $days;
            } else {
                $income->debt = 0;
            }
        }

        if (request()->ajax()) {
            return view('income.result', compact('incomes'));
        }

        return view('income.index', compact('incomes', 'users'));
    }



    // This method will now return only the form partial for creation
    public function create(): View
    {
        if (!$this->genericPolicy->create(Auth::user(), new Income())) { // Should be 'create' for form display
           abort(403, 'Unauthorized action.');
        }

       $users = User::all();
       return view('income.partials.form', compact('users')); // Return the form partial
    }

    public function store(Request $request): RedirectResponse
    {
        if (!$this->genericPolicy->create(Auth::user(), new Income())) {
           abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'source' => 'required|exists:users,id',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        $data['created_By'] = Auth::id(); // Automatically set created_By
        $data['updated_By'] = Auth::id(); // Set updated_By on creation too

        Income::create($data);
        return Redirect::route('income.index')->with('success', 'Income added successfully.');
    }

    // This method will now return only the view details partial
    public function show($id): View // Changed from JsonResponse to View
    {
        if (!$this->genericPolicy->view(Auth::user(), new Income())) {
           abort(403, 'Unauthorized action.');
        }
        $income = Income::with('sourceUser', 'createdByUser', 'updatedByUser')->findOrFail($id);
        return view('income.partials.show', compact('income')); // Return the show partial
    }

    // This method will now return only the form partial for editing
    public function edit($id): View // Changed from JsonResponse to View
    {
        if (!$this->genericPolicy->update(Auth::user(), new Income())) { // Should be 'update' for form display
           abort(403, 'Unauthorized action.');
       }
        $income = Income::findOrFail($id);
        $users = User::all(); // Pass users for the dropdown
        return view('income.partials.form', compact('income', 'users')); // Return the form partial
    }


    public function update(Request $request, $id): RedirectResponse
    {
        if (!$this->genericPolicy->update(Auth::user(), new Income())) {
           abort(403, 'Unauthorized action.');
       }

        $income = Income::findOrFail($id);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'source' => 'required|exists:users,id',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        // Automatically set updated_By to the current authenticated user's ID
        $data['updated_By'] = Auth::id();

        $income->update($data);
        return Redirect::route('income.index')->with('success', 'Income updated successfully.');
    }

    public function approve($id): RedirectResponse
        {
            if (!$this->genericPolicy->update(Auth::user(), new Income())) {
                abort(403, 'Unauthorized action.');
            }

            $income = Income::findOrFail($id);
            $income->status = 'paid';
            $income->amount = $income->amount + ($income->debt ?? 0);
            $income->updated_By = Auth::id();
            $income->save();

            return redirect()->route('income.index')->with('success', 'Income approved successfully.');
        }



    public function destroy($id): RedirectResponse
    {
        if (!$this->genericPolicy->delete(Auth::user(), new Income())) {
           abort(403, 'Unauthorized action.');
       }

        $income = Income::findOrFail($id);
        $income->delete();
        return Redirect::route('income.index')->with('success', 'Income deleted successfully.');
    }
}
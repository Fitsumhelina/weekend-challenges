<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Policies\GenericPolicy;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Ensure Carbon is imported if you're using it in the controller for date formatting

class ExpenseController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    protected $genericPolicy;

    public function __construct(GenericPolicy $genericPolicy)
    {
        $this->genericPolicy = $genericPolicy;
    }

    public function index(): View
    {
        if (!$this->genericPolicy->view(Auth::user(), new Expense())) {
            abort(403, 'Unauthorized action.');
        }

       $search = request('search');
        $perPage = request('per_page', 10); // Default to 10 if not specified
        $date = request('date'); // Get the date from the request

        $expenses = Expense::orderBy('created_at', 'desc')->with(['createdByUser', 'updatedByUser'])
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('category', 'like', '%' . $search . '%')
                    ->orWhereRaw("DATE_FORMAT(date, '%Y-%m-%d') LIKE ?", ['%' . $search . '%']);
            })
            ->when($date, function ($query, $date) {
                $query->whereDate('date', $date);
            })
            ->paginate($perPage)
            ->appends(request()->query()); // Keep search/per_page/date in pagination links

        $users = User::all(); // Assuming users might be needed for some future dropdown or display

        if (request()->ajax()) {
            return view('expense.result', compact('expenses'));
        }

        return view('expense.index', compact('expenses', 'users'));
    }

    // This method will now return only the form partial for creation in a modal
    public function create(): View
    {
        // Policy check for 'create' action when displaying the form
        if (!$this->genericPolicy->create(Auth::user(), new Expense())) {
           abort(403, 'Unauthorized action.');
        }
        // Users are not explicitly needed for the current form, but can be passed if a dynamic dropdown is added
        return view('expense.partials.form');
    }

    public function store(Request $request): RedirectResponse
    {
        if (!$this->genericPolicy->create(Auth::user(), new Expense())) {
           abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string', 
            'amount' => 'required|numeric',
            'date' => 'required|date', 
            'description' => 'nullable|string|max:255', 
        ]);

        $data['created_by'] = Auth::id(); 
        $data['updated_by'] = Auth::id(); 

        Expense::create($data);
        return Redirect::route('expense.index')->with('success', 'Expense created successfully.');
    }

    // This method will now return only the view details partial for a modal
    public function show($id): View
    {
        if (!$this->genericPolicy->view(Auth::user(), new Expense())) {
           abort(403, 'Unauthorized action.');
        }
        $expense = Expense::with('createdByUser', 'updatedByUser')->findOrFail($id);
        return view('expense.partials.show', compact('expense'));
    }

    // This method will now return only the form partial for editing in a modal
    public function edit($id): View
    {
        // Policy check for 'update' action when displaying the form for editing
        if (!$this->genericPolicy->update(Auth::user(), new Expense())) {
           abort(403, 'Unauthorized action.');
        }
        $expense = Expense::findOrFail($id);
        // Users are not explicitly needed for the current form, but can be passed if a dynamic dropdown is added
        return view('expense.partials.form', compact('expense'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        if (!$this->genericPolicy->update(Auth::user(), new Expense())) {
           abort(403, 'Unauthorized action.');
        }
        $expense = Expense::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string', // Ensure category is validated
            'amount' => 'required|numeric',
            'date' => 'required|date', // Added date validation to align with form
            'description' => 'nullable|string|max:255', // Changed to nullable
        ]);

        $data['updated_by'] = Auth::id(); // Automatically set updated_by to the current authenticated user's ID

        $expense->update($data);
        return Redirect::route('expense.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        if (!$this->genericPolicy->delete(Auth::user(), new Expense())) {
           abort(403, 'Unauthorized action.');
        }
        $expense = Expense::findOrFail($id);
        $expense->delete();
        return Redirect::route('expense.index')->with('success', 'Expense deleted successfully.');
    }
}
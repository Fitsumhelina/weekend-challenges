<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\User; // Import the User model
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Policies\GenericPolicy;
use Illuminate\Http\JsonResponse;

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

       // Eager load all necessary relationships: sourceUser, createdByUser, updatedByUser
       $incomes = Income::with(['sourceUser', 'createdByUser', 'updatedByUser'])->paginate(10);
       return view('income.index', compact('incomes'));
   }


    public function create(): View
    {   
        if (!$this->genericPolicy->view(Auth::user(), new Income())) {
           abort(403, 'Unauthorized action.');
        }

       // If you want to populate a dropdown for 'source' with user names,
       // you would pass the users here:
       // $users = User::all();
       // return view('income.create', compact('users'));
       return view('income.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (!$this->genericPolicy->create(Auth::user(), new Income())) {
           abort(403, 'Unauthorized action.');
       }
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'source' => 'required|exists:users,id', // Validate that source is a valid user ID
            'description' => 'nullable|string|max:255',
            'amount' => 'required|numeric',
            'date' => 'required|date',
        ]);

        // Automatically set created_By to the current authenticated user's ID
        $data['created_By'] = Auth::id();

        Income::create($data);
        return Redirect::route('income.index')->with('success', 'Income created successfully.');
    }


    public function show($id): View
    {
        if (!$this->genericPolicy->view(Auth::user(), new Income())) {
           abort(403, 'Unauthorized action.');
       }
        // Eager load all necessary relationships for the show view
        $income = Income::with(['sourceUser', 'createdByUser', 'updatedByUser'])->findOrFail($id);
        return view('income.partials.show', compact('income'));
    }


    public function edit($id): JsonResponse
    {
        if (!$this->genericPolicy->view(Auth::user(), new Income())) {
           abort(403, 'Unauthorized action.');
       }
        $income = Income::findOrFail($id);
        return response()->json($income); // Return income data as JSON
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
            'source' => 'required|exists:users,id', // Validate that source is a valid user ID
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        // Automatically set updated_By to the current authenticated user's ID
        $data['updated_By'] = Auth::id();

        $income->update($data);
        return Redirect::route('income.index')->with('success', 'Income updated successfully.');
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

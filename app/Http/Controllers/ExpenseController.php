<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Policies\GenericPolicy;
use Illuminate\Support\Facades\Auth;


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
        
        $expenses = Expense::latest()->paginate(10);
        return view('expense.index', compact('expenses'));
    }
    public function create(): View
    {
        if (!$this->genericPolicy->view(Auth::user(), new Expense())) {
           abort(403, 'Unauthorized action.');
        }
        return view('expense.create');
    }
    public function store(Request $request): RedirectResponse
    {
        if (!$this->genericPolicy->create(Auth::user(), new Expense())) {
           abort(403, 'Unauthorized action.');
       }
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
        ]);

        Expense::create($data);
        return Redirect::route('expense.index')->with('success', 'Expense created successfully.');
    }
    public function show($id): View
    {
        if (!$this->genericPolicy->view(Auth::user(), new Expense())) {
           abort(403, 'Unauthorized action.');
       }
        $expense = Expense::findOrFail($id);
        return view('expense.show', compact('expense'));
    }
    public function edit($id): View
    {
        if (!$this->genericPolicy->view(Auth::user(), new Expense())) {
           abort(403, 'Unauthorized action.');
       }
        $expense = Expense::findOrFail($id);
        return view('expense.edit', compact('expense'));
    }
    public function update(Request $request, $id): RedirectResponse
    {
        if (!$this->genericPolicy->update(Auth::user(), new Expense())) {
           abort(403, 'Unauthorized action.');
       }
        $expense = Expense::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required',
            'amount' => 'required|numeric',
            'description' => 'required|string|max:255',
        ]);

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
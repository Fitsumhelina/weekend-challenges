<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Policies\GenericPolicy;

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
        $this->authorize('view', Expense::class);
        $expenses = Expense::latest()->paginate(10);
        return view('user.expense.index', compact('expenses'));
    }
    public function create(): View
    {
        $this->authorize('create', Expense::class);
        return view('user.expense.create');
    }
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Expense::class);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'description' => 'required|string|max:255',
        ]);

        Expense::create($data);
        return Redirect::route('user.expense.index')->with('success', 'Expense created successfully.');
    }
    public function show($id): View
    {
        $expense = Expense::findOrFail($id);
        $this->authorize('view', $expense);
        return view('user.expense.show', compact('expense'));
    }
    public function edit($id): View
    {
        $expense = Expense::findOrFail($id);
        $this->authorize('update', $expense);
        return view('user.expense.edit', compact('expense'));
    }
    public function update(Request $request, $id): RedirectResponse
    {
        $expense = Expense::findOrFail($id);
        $this->authorize('update', $expense);

        $data = $request->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $expense->update($data);
        return Redirect::route('user.expense.index')->with('success', 'Expense updated successfully.');
    }
    public function destroy($id): RedirectResponse
    {
        $expense = Expense::findOrFail($id);
        $this->authorize('delete', $expense);
        $expense->delete();
        return Redirect::route('user.expense.index')->with('success', 'Expense deleted successfully.');
    }
}   
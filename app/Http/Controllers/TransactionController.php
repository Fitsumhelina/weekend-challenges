<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Income;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // --- Income Methods ---

    public function indexIncome(Request $request)
    {
        $incomes = Income::all(); // Or filter by user if needed
        return view('income.index', compact('incomes'));
    }

    public function showIncome($id)
    {
        $income = Income::findOrFail($id);
        return view('income.show', compact('income'));
    }

    public function storeIncome(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        Income::create([
            ...$validated,
            'created_by' => $request->user()->id,
        ]);

        return redirect()->back()->with('success', 'Income added successfully.');
    }

    public function updateIncome(Request $request, $id)
    {
        $income = Income::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $income->update($validated);

        return redirect()->back()->with('success', 'Income updated successfully.');
    }

    public function deleteIncome($id)
    {
        $income = Income::findOrFail($id);
        $income->delete();

        return redirect()->back()->with('success', 'Income deleted successfully.');
    }

    // --- Expense Methods ---

    public function indexExpenses(Request $request)
    {
        $expenses = Expense::all();
        return view('expenses.index', compact('expenses'));
    }

    public function showExpense($id)
    {
        $expense = Expense::findOrFail($id);
        return view('expenses.show', compact('expense'));
    }

    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        Expense::create([
            ...$validated,
            'created_by' => $request->user()->id,
        ]);

        return redirect()->back()->with('success', 'Expense added successfully.');
    }

    public function updateExpenses(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        $expense->update($validated);

        return redirect()->back()->with('success', 'Expense updated successfully.');
    }

    public function deleteExpense($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect()->back()->with('success', 'Expense deleted successfully.');
    }
}

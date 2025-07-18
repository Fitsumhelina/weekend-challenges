<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Expense;
use App\Models\Income;

class TransactionController extends Controller
{
    public function dashboard(Request $request): View
    {
        $expenses = Expense::all();
        $incomes = Income::all();
        $isAdmin = $request->user() && $request->user()->hasRole('Admin');
        $user = $request->user();
        return view('dashboard', [
            'expenses' => $expenses,
            'incomes' => $incomes,
            'isAdmin' => $isAdmin,
            'user' => $user,
        ]);
    }

    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        Expense::create($validated);

        return redirect()->back()->with('success', 'Expense added successfully.');
    }

    public function storeIncome(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
        ]);

        Income::create($validated);

        return redirect()->back()->with('success', 'Income added successfully.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show dashboard with expenses and incomes.
     */
    public function dashboard(Request $request): View
    {
        $expenses = \App\Models\Expense::all();
        $incomes = \App\Models\Income::all();
        $isAdmin = $request->user() && $request->user()->hasRole('Admin');
        $user = $request->user();
        return view('dashboard', [
            'expenses' => $expenses,
            'incomes' => $incomes,
            'isAdmin' => $isAdmin,
            'user' => $user,
        ]);
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

        public function storeExpense(Request $request): RedirectResponse
        {
            $request->validate([
                'title' => 'required|string|max:255',
                'category' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'amount' => 'required|numeric|min:0',
            ]);

            \App\Models\Expense::create([
                'title' => $request->title,
                'category' => $request->category,
                'description' => $request->description,
                'amount' => $request->amount,
                'created_By' => auth()->user()->id,
                'updated_By' => auth()->user()->id,
            ]);

            return redirect()->route('dashboard')->with('success', 'Expense added successfully!');
        }

        /**
         * Store a new income
         */
        public function storeIncome(Request $request): RedirectResponse
        {
            $request->validate([
                'title' => 'required|string|max:255',
                'source' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'amount' => 'required|numeric|min:0',
            ]);

            \App\Models\Income::create([
                'title' => $request->title,
                'source' => $request->source,
                'description' => $request->description,
                'amount' => $request->amount,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
            ]);

            return redirect()->route('dashboard')->with('success', 'Income added successfully!');
        }

        /**
         * Show expense for editing
         */
        public function showExpense($id): View
        {
            $expense = \App\Models\Expense::findOrFail($id);
            return view('expenses.edit', compact('expense'));
        }

        /**
         * Show income for editing
         */
        public function showIncome($id): View
        {
            $income = \App\Models\Income::findOrFail($id);
            return view('income.edit', compact('income'));
        }

        /**
         * Update expense
         */
        public function updateExpenses(Request $request, $id): RedirectResponse
        {
            $request->validate([
                'title' => 'required|string|max:255',
                'category' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'amount' => 'required|numeric|min:0',
            ]);

            $expense = \App\Models\Expense::findOrFail($id);
            $expense->update([
                'title' => $request->title,
                'category' => $request->category,
                'description' => $request->description,
                'amount' => $request->amount,
                'updated_By' => auth()->id(),
            ]);

            return redirect()->route('dashboard')->with('success', 'Expense updated successfully!');
        }

        /**
         * Update income
         */
        public function updateIncome(Request $request, $id): RedirectResponse
        {
            $request->validate([
                'title' => 'required|string|max:255',
                'source' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'amount' => 'required|numeric|min:0',
            ]);

            $income = \App\Models\Income::findOrFail($id);
            $income->update([
                'title' => $request->title,
                'source' => $request->source,
                'description' => $request->description,
                'amount' => $request->amount,
                'updated_by' => auth()->id(),
            ]);

            return redirect()->route('dashboard')->with('success', 'Income updated successfully!');
        }

        /**
         * Delete expense
         */
        public function deleteExpense($id): RedirectResponse
        {
            $expense = \App\Models\Expense::findOrFail($id);
            $expense->delete();

            return redirect()->route('dashboard')->with('success', 'Expense deleted successfully!');
        }

        /**
         * Delete income
         */
        public function deleteIncome($id): RedirectResponse
        {
            $income = \App\Models\Income::findOrFail($id);
            $income->delete();

            return redirect()->route('dashboard')->with('success', 'Income deleted successfully!');
        }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Logic to retrieve and display expenses
        return view('expense.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Logic to show the form for creating a new expense
        return view('expense.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Logic to store the new expense
        return Redirect::route('expense.index')->with('success', 'Expense created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        // Logic to show a specific expense
        return view('expense.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        // Logic to show the form for editing an expense
        return view('expense.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // Logic to update the specified expense
        return Redirect::route('expense.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        // Logic to delete the specified expense
        return Redirect::route('expense.index')->with('success', 'Expense deleted successfully.');
    }
}
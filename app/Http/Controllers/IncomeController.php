<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Logic to retrieve and display incomes
        return view('income.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        // Logic to show the form for creating a new income
        return view('income.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // Logic to store the new income
        return Redirect::route('income.index')->with('success', 'Income created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        // Logic to show a specific income
        return view('income.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        // Logic to show the form for editing an income
        return view('income.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // Logic to update the income
        return Redirect::route('income.index')->with('success', 'Income updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        // Logic to delete the income
        return Redirect::route('income.index')->with('success', 'Income deleted successfully.');
    }
}
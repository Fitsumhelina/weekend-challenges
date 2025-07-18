<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
 {

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
 }
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;

class DashboardController extends Controller
{
    public function index()
    {
        $incomes = Income::latest()->take(10)->get();
        $expenses = Expense::latest()->take(10)->get();
        // $isAdmin = auth()->user()->hasRole('admin'); // adjust as needed

        return view('dashboard', compact('incomes', 'expenses'));
    }
}

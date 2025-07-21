<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;

class DashboardController extends Controller
{
    public function index()
    {
        $totalIncome = Income::sum('amount');
        $totalExpenses = Expense::sum('amount');
        $netBalance = $totalIncome - $totalExpenses;

        // Fetch recent transactions
        $recentIncome = Income::orderBy('created_at', 'desc')->take(5)->get();
        $recentExpenses = Expense::orderBy('created_at', 'desc')->take(5)->get();
        // $totalDebt = $incomes->sum('debt');


        return view('dashboard', [
            'totalIncome' => $totalIncome,
            'totalExpenses' => $totalExpenses,
            'netBalance' => $netBalance,
            'incomes' => $recentIncome,
            'expenses' => $recentExpenses,
        ]);
    }

}

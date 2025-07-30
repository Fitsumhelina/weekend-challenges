<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Income;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;


class DashboardController extends Controller
{
    public function index()
    {
        $totalIncome = Income::where('status', 'paid')->sum('amount');
        $totalExpenses = Expense::sum('amount');
        $netBalance = $totalIncome - $totalExpenses;

        // Fetch recent transactions
        $recentIncome = Income::orderBy('created_at', 'desc')->take(5)->get();
        $recentExpenses = Expense::orderBy('created_at', 'desc')->take(5)->get();
        $userId = Auth::id();
        $totalDebt = Income::where('source', $userId)->sum('debt');


        return view('dashboard', [
            'totalIncome' => $totalIncome,
            'totalDebt'=>$totalDebt,
            'totalExpenses' => $totalExpenses,
            'netBalance' => $netBalance,
            'incomes' => $recentIncome,
            'expenses' => $recentExpenses,
        ]);
    }

}

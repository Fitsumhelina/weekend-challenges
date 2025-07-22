@extends('layouts.app')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-6 sm:py-8 w-full">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 sm:gap-6 mb-8 sm:mb-10">
        @if($totalDebt>0)
        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 flex flex-col items-center justify-center transform hover:scale-105 transition duration-300 ease-in-out w-full">
            <div class="text-red-600 mb-2 sm:mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <p class="text-lg sm:text-xl font-semibold text-gray-600 text-center">Your Have Unpaid Debt</p>
            <p class="text-2xl sm:text-4xl font-bold text-red-600 mt-1 sm:mt-2 text-center">{{ number_format($totalDebt, 2)}} ETB</p>
        </div>
        @endif
    </div>
    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 mb-8 sm:mb-10">
        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 flex flex-col items-center justify-center transform hover:scale-105 transition duration-300 ease-in-out w-full">
            <div class="text-blue-600 mb-2 sm:mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c1.657 0 3 1.343 3 3v5a3 3 0 01-3 3H7a3 3 0 01-3-3v-5c0-1.657 1.343-3 3-3h5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8V5a2 2 0 012-2h4a2 2 0 012 2v3" />
                </svg>
            </div>
            <p class="text-lg sm:text-xl font-semibold text-gray-600 text-center">Total Income</p>
            <p class="text-2xl sm:text-4xl font-bold text-green-600 mt-1 sm:mt-2 text-center">{{ number_format($totalIncome, 2)}} ETB</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 flex flex-col items-center justify-center transform hover:scale-105 transition duration-300 ease-in-out w-full">
            <div class="text-red-600 mb-2 sm:mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <p class="text-lg sm:text-xl font-semibold text-gray-600 text-center">Total Expenses</p>
            <p class="text-2xl sm:text-4xl font-bold text-red-600 mt-1 sm:mt-2 text-center">{{ number_format($totalExpenses, 2)}} ETB</p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 flex flex-col items-center justify-center transform hover:scale-105 transition duration-300 ease-in-out w-full">
            <div class="text-purple-600 mb-2 sm:mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 sm:h-12 sm:w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M18 6l-3 1m0 0l3 9a5.002 5.002 0 01-6.001 0M18 7l-3 9m-9-9a3 3 0 110-6 3 3 0 010 6zm7 9a3 3 0 110-6 3 3 0 010 6zm7 0a3 3 0 110-6 3 3 0 010 6z" />
                </svg>
            </div>
            <p class="text-lg sm:text-xl font-semibold text-gray-600 text-center">Net Balance</p>
            <p class="text-2xl sm:text-4xl font-bold {{ $netBalance >= 0 ? 'text-green-600' : 'text-red-600' }} mt-1 sm:mt-2 text-center">{{ number_format($netBalance, 2) }} ETB</p>
        </div>
    </div>

    {{-- Recent Transactions Section --}}
    <div class="bg-white rounded-xl shadow-lg p-4 sm:p-6 mt-8 sm:mt-12">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4 sm:mb-6 text-center sm:text-left">Recent Records</h2>

        {{-- Tab Buttons --}}
        <div class="flex flex-col sm:flex-row border-b border-gray-200 mb-4 sm:mb-6">
            <button id="tab-incomes-table" onclick="showTable('incomes')"
                    class="py-2 sm:py-3 px-4 sm:px-6 text-base sm:text-lg font-medium border-b-2 border-green-600 text-green-700 focus:outline-none transition duration-300 ease-in-out">
                Recent Incomes
            </button>
            <button id="tab-expenses-table" onclick="showTable('expenses')"
                    class="py-2 sm:py-3 px-4 sm:px-6 text-base sm:text-lg font-medium border-b-2 border-transparent text-gray-600 hover:text-red-700 hover:border-red-600 focus:outline-none transition duration-300 ease-in-out">
                Recent Expenses
            </button>
        </div>

        {{-- Recent Incomes Table --}}
        <div id="incomes-table" class="overflow-x-auto">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2 sm:mb-4 text-center sm:text-left">Last 5 Incomes</h3>
            <div class="w-full">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg text-xs sm:text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase leading-normal">
                        <tr>
                            <th class="py-2 sm:py-3 px-2 sm:px-6 text-left">Title</th>
                            <th class="py-2 sm:py-3 px-2 sm:px-6 text-left">Amount</th>
                            <th class="py-2 sm:py-3 px-2 sm:px-6 text-left">From</th>
                            <th class="py-2 sm:py-3 px-2 sm:px-6 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 font-light">
                        @forelse ($incomes as $income)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-2 sm:py-3 px-2 sm:px-6 text-left">{{ $income->title }}</td>
                                <td class="py-2 sm:py-3 px-2 sm:px-6 text-left text-green-600">{{ number_format($income->amount, 2) }} ETB</td>
                                <td class="py-2 sm:py-3 px-2 sm:px-6 text-left">{{ $income->sourceUser->name }}</td>
                                <td class="py-2 sm:py-3 px-2 sm:px-6 text-left">{{ \Carbon\Carbon::parse($income->date)->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-3 px-2 sm:px-6 text-center text-gray-500">No recent income records.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Expenses Table --}}
        <div id="expenses-table" class="overflow-x-auto hidden">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2 sm:mb-4 text-center sm:text-left">Last 5 Expenses</h3>
            <div class="w-full">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg text-xs sm:text-sm">
                    <thead class="bg-gray-100 text-gray-700 uppercase leading-normal">
                        <tr>
                            <th class="py-2 sm:py-3 px-2 sm:px-6 text-left">Title</th>
                            <th class="py-2 sm:py-3 px-2 sm:px-6 text-left">Amount</th>
                            <th class="py-2 sm:py-3 px-2 sm:px-6 text-left">Category</th>
                            <th class="py-2 sm:py-3 px-2 sm:px-6 text-left">Date</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 font-light">
                        @forelse ($expenses as $expense)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-2 sm:py-3 px-2 sm:px-6 text-left">{{ $expense->title }}</td>
                                <td class="py-2 sm:py-3 px-2 sm:px-6 text-left text-red-600">{{ number_format($expense->amount, 2) }} ETB</td>
                                <td class="py-2 sm:py-3 px-2 sm:px-6 text-left">{{ $expense->category }}</td>
                                <td class="py-2 sm:py-3 px-2 sm:px-6 text-left">{{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-3 px-2 sm:px-6 text-center text-gray-500">No recent expense records.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function showTable(type) {
        const incomeTable = document.getElementById('incomes-table');
        const expenseTable = document.getElementById('expenses-table');

        const incomeTab = document.getElementById('tab-incomes-table');
        const expenseTab = document.getElementById('tab-expenses-table');

        if (type === 'incomes') {
            incomeTable.classList.remove('hidden');
            expenseTable.classList.add('hidden');

            incomeTab.classList.add('border-green-600', 'text-green-700');
            incomeTab.classList.remove('border-transparent', 'text-gray-600');

            expenseTab.classList.remove('border-red-600', 'text-red-700');
            expenseTab.classList.add('border-transparent', 'text-gray-600');
        } else {
            incomeTable.classList.add('hidden');
            expenseTable.classList.remove('hidden');

            incomeTab.classList.remove('border-green-600', 'text-green-700');
            incomeTab.classList.add('border-transparent', 'text-gray-600');

            expenseTab.classList.add('border-red-600', 'text-red-700');
            expenseTab.classList.remove('border-transparent', 'text-gray-600');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        showTable('incomes');
    });
</script>
@endsection

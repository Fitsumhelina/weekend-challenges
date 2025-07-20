<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-3xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Financial Dashboard') }}
        </h2>
        <div class="flex gap-2 mt-2">
            @can('create income')
                <button onclick="document.getElementById('transaction-form').classList.toggle('hidden')" class="bg-gradient-to-r from-green-400 to-blue-500 hover:from-blue-500 hover:to-green-400 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition duration-300 ease-in-out transform hover:scale-105 flex items-center">
                    <span class="fas fa-plus mr-2"></span>Add Income
                </button>
            @endcan
            @can('create expense')
                <button onclick="document.getElementById('transaction-form').classList.toggle('hidden')" class="bg-gradient-to-r from-red-400 to-yellow-500 hover:from-yellow-500 hover:to-red-400 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition duration-300 ease-in-out transform hover:scale-105 flex items-center">
                    <span class="fas fa-plus mr-2"></span>Add Expense
                </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-gray-600 dark:text-gray-300">

            {{-- Financial Summary --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <x-financial-card 
                    title="Total Income"
                    amount="{{ number_format($incomes->sum('amount'), 2) }}"
                    color="green"
                    icon="fa-arrow-up" />
                <x-financial-card 
                    title="Total Expenses"
                    amount="{{ number_format($expenses->sum('amount'), 2) }}"
                    color="red"
                    icon="fa-arrow-down" />
                <x-financial-card 
                    title="Net Balance"
                    amount="{{ number_format($incomes->sum('amount') - $expenses->sum('amount'), 2) }}"
                    color="blue"
                    icon="fa-balance-scale" />
            </div>

            {{-- Add Transaction Form --}}
            @if(auth()->user()->can('create income') || auth()->user()->can('create expense'))
                <div id="transaction-form" class="hidden mb-8">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
                            <nav class="flex space-x-4" aria-label="Tabs">
                                @can('create income')
                                    <button onclick="showForm('income')" class="form-tab px-4 py-2 font-semibold rounded-t-lg focus:outline-none text-blue-700 border-b-2 border-blue-600" aria-selected="true">
                                        Income
                                    </button>
                                @endcan
                                @can('create expense')
                                    <button onclick="showForm('expense')" class="form-tab px-4 py-2 font-semibold rounded-t-lg focus:outline-none text-red-700 border-b-2 border-transparent" aria-selected="false">
                                        Expense
                                    </button>
                                @endcan
                            </nav>
                        </div>
                        @can('create income')
                            <div id="income-form">
                                @include('income.partials.form', ['type' => 'income', 'route' => route('income.store')])
                            </div>
                        @endcan
                        @can('create expense')
                            <div id="expense-form" class="hidden">
                                @include('expense.partials.form', ['type' => 'expense', 'route' => route('expense.store')])
                            </div>
                        @endcan
                    </div>
                </div>
            @endif

            {{-- View Buttons --}}
            <div class="flex space-x-4 mb-6">
                @can('view income')
                    <button onclick="showTable('income')" class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">View Income</button>
                @endcan
                @can('view expense')
                    <button onclick="showTable('expense')" class="px-4 py-2 bg-red-600 text-white rounded shadow hover:bg-red-700">View Expense</button>
                @endcan
            </div>

            {{-- Tables --}}
            @can('view expense')
                <div id="expenses-table">
                    <x-transaction-table 
                        title="Expenses" 
                        items="{{ $expenses }}" 
                        type="expense"
                        color="red" 
                        icon="fa-credit-card" 
                        emptyText="No expenses recorded yet" />
                </div>
            @endcan
            @can('view income')
                <div id="incomes-table" class="hidden">
                    <x-transaction-table 
                        title="Income" 
                        items="{{ $incomes }}" 
                        type="income"
                        color="green" 
                        icon="fa-coins" 
                        emptyText="No income recorded yet" />
                </div>
            @endcan

        </div>
    </div>

    {{-- Simple JS for toggling tables and forms --}}
    <script>
        function showTable(type) {
            const expensesTable = document.getElementById('expenses-table');
            const incomesTable = document.getElementById('incomes-table');
            if (type === 'expense') {
                expensesTable?.classList.remove('hidden');
                incomesTable?.classList.add('hidden');
            } else if (type === 'income') {
                expensesTable?.classList.add('hidden');
                incomesTable?.classList.remove('hidden');
            }
        }
        function showForm(type) {
            const incomeForm = document.getElementById('income-form');
            const expenseForm = document.getElementById('expense-form');
            incomeForm?.classList.add('hidden');
            expenseForm?.classList.add('hidden');
            if (type === 'income') incomeForm?.classList.remove('hidden');
            if (type === 'expense') expenseForm?.classList.remove('hidden');
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>
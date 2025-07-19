<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-3xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Financial Dashboard') }}
            </h2>
            @can('create income') || @can('create expense')
                <button onclick="toggleTransactionForm()" class="bg-gradient-to-r from-white-600 to-gray-700 hover:from-gray-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
                    <p class="fas fa-plus mr-2 text-gray-600 dark:text-gray-300">Add Transaction</p><p class="text-gray-600 dark:text-gray-300"></p>
                </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-gray-600 dark:text-gray-300">

            {{-- Financial Summary --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Income -->
                <x-financial-card 
                    title="Total Income"
                    amount="{{ number_format($incomes->sum('amount'), 2) }}"
                    color="green"
                    icon="fa-arrow-up" />

                <!-- Total Expenses -->
                <x-financial-card 
                    title="Total Expenses"
                    amount="{{ number_format($expenses->sum('amount'), 2) }}"
                    color="red"
                    icon="fa-arrow-down" />

                <!-- Net Balance -->
                <x-financial-card 
                    title="Net Balance"
                    amount="{{ number_format($incomes->sum('amount') - $expenses->sum('amount'), 2) }}"
                    color="blue"
                    icon="fa-balance-scale" />
            </div>

           {{-- Toggleable Transaction Form --}}
        @can('create income') || @can('create expense')
            <div id="transaction-form" class="hidden mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
                        <nav class="flex space-x-4" aria-label="Tabs">
                            <button id="tab-income" onclick="showForm('income')" class="form-tab px-4 py-2 font-semibold rounded-t-lg focus:outline-none text-blue-700 border-b-2 border-blue-600" aria-selected="true">
                                Income
                            </button>
                            <button id="tab-expense" onclick="showForm('expense')" class="form-tab px-4 py-2 font-semibold rounded-t-lg focus:outline-none text-red-700 border-b-2 border-transparent" aria-selected="false">
                                Expense
                            </button>
                        </nav>
                    </div>

                    <!-- Income Form -->
                    <div id="income-form">
                        <x-transaction-form type="income" route="{{ route('income.store') }}" />
                    </div>

                    <!-- Expense Form -->
                    <div id="expense-form" class="hidden">
                        <x-transaction-form type="expense" route="{{ route('expenses.store') }}" />
                    </div>
                </div>
            </div>
                    
            @endcan

            {{-- Recent Transactions --}}

            {{-- Transactions Row with Tabs --}}
            <div class="mb-8">
                <nav class="flex space-x-4 mb-4" aria-label="Tabs">
                    <button id="tab-expenses-table" onclick="showTable('expenses')" class="table-tab px-4 py-2 font-semibold rounded-t-lg focus:outline-none text-red-700 border-b-2 border-red-600" aria-selected="true">
                        Expenses
                    </button>
                    <button id="tab-incomes-table" onclick="showTable('incomes')" class="table-tab px-4 py-2 font-semibold rounded-t-lg focus:outline-none text-green-700 border-b-2 border-transparent" aria-selected="false">
                        Income
                    </button>
                </nav>
                <div id="expenses-table">
                    <x-transaction-table 
                        title="Expenses" 
                        items="{{ $expenses }}" 
                        isAdmin="{{ $isAdmin }}"
                        type="expense"
                        color="red" 
                        icon="fa-credit-card" 
                        emptyText="No expenses recorded yet" />
                </div>
                <div id="incomes-table" class="hidden">
                    <x-transaction-table 
                        title="Income" 
                        items="{{ $incomes }}" 
                        isAdmin="{{ $isAdmin }}"
                        type="income"
                        color="green" 
                        icon="fa-coins" 
                        emptyText="No income recorded yet" />
                </div>
            </div>
        </div>
    </div>

    {{-- Script --}}
    <script src="{{('js/dashboard.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-3xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Financial Dashboard') }}
            </h2>
            @if($isAdmin)
                <button onclick="toggleTransactionForm()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
                    <i class="fas fa-plus mr-2"></i>Add Transaction
                </button>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

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
            @if($isAdmin)
            <div id="transaction-form" class="hidden mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="mb-6">
                        <button onclick="showForm('income')" class="form-tab bg-green-600 text-white px-4 py-2 rounded-lg mr-2">Add Income</button>
                        <button onclick="showForm('expense')" class="form-tab bg-red-600 text-white px-4 py-2 rounded-lg">Add Expense</button>
                    </div>

                    <!-- Income Form -->
                    <div id="income-form" class="hidden">
                        <x-transaction-form type="income" route="{{ route('income.store') }}" />
                    </div>

                    <!-- Expense Form -->
                    <div id="expense-form" class="hidden">
                        <x-transaction-form type="expense" route="{{ route('expenses.store') }}" />
                    </div>
                </div>
            </div>
            @endif

            {{-- Expenses Table --}}
            <x-transaction-table 
                title="Expenses" 
                items="{{ $expenses }}" 
                isAdmin="{{ $isAdmin }}"
                type="expense"
                color="red" 
                icon="fa-credit-card" 
                emptyText="No expenses recorded yet" />

            {{-- Incomes Table --}}
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

    {{-- Script --}}
    <script>
        function toggleTransactionForm() {
            const form = document.getElementById('transaction-form');
            form.classList.toggle('hidden');
            if (!form.classList.contains('hidden')) {
                form.scrollIntoView({ behavior: 'smooth' });
            }
        }

        function showForm(type) {
            const incomeForm = document.getElementById('income-form');
            const expenseForm = document.getElementById('expense-form');
            incomeForm.classList.add('hidden');
            expenseForm.classList.add('hidden');

            if (type === 'income') incomeForm.classList.remove('hidden');
            if (type === 'expense') expenseForm.classList.remove('hidden');
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</x-app-layout>

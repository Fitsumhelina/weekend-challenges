<x-app-layout>
    {{-- Header --}}
    <x-slot name="header">
        <div class="px-4 sm:px-6 lg:px-8 text-gray-600 dark:text-gray-300">
            <h2 class="font-semibold text-3xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Financial Dashboard') }}
            </h2>
        </div>

        {{-- Cards --}}
        <div class="w-full px-4 sm:px-6 lg:px-8 mt-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                <x-financial-card 
                    title="Total Income"
                    amount="{{ number_format($totalIncome, 2) }}"
                    color="green"
                    icon="fa-arrow-up" />

                <x-financial-card 
                    title="Total Expenses"
                    amount="{{ number_format($totalExpenses, 2) }}"
                    color="red"
                    icon="fa-arrow-down" />

                <x-financial-card 
                    title="Net Balance"
                    amount="{{ number_format($netBalance, 2) }}"
                    color="blue"
                    icon="fa-balance-scale" />
            </div>
        </div>
    </x-slot>

    {{-- Main Content --}}
    <div class="px-4 sm:px-6 lg:px-8 py-6 min-h-[calc(100vh-10rem)]">
        {{-- Tab Navigation --}}
        <div class="mb-4 flex space-x-4">
            <button id="tab-expenses-table" onclick="showTable('expenses')" class="table-tab px-4 py-2 font-semibold rounded-t-lg focus:outline-none text-red-700 border-b-2 border-red-600">
                Expenses
            </button>
            <button id="tab-incomes-table" onclick="showTable('incomes')" class="table-tab px-4 py-2 font-semibold rounded-t-lg focus:outline-none text-gray-600 border-b-2 border-transparent">
                Income
            </button>
        </div>

        {{-- Tables --}}
        <div id="expenses-table">
            <x-transaction-table 
                title="Expenses" 
                :items="$expenses" 
                type="expense"
                color="red" 
                icon="fa-credit-card" 
                emptyText="No expenses recorded yet" />
        </div>

        <div id="incomes-table" class="hidden">
            <x-transaction-table 
                title="Income" 
                :items="$incomes" 
                type="income"
                color="green" 
                icon="fa-coins" 
                emptyText="No income recorded yet" />
        </div>
    </div>

    @section('scripts')
    <script>
        function showTable(type) {
            const expensesTable = document.getElementById('expenses-table');
            const incomesTable = document.getElementById('incomes-table');
            const tabExpenses = document.getElementById('tab-expenses-table');
            const tabIncomes = document.getElementById('tab-incomes-table');

            expensesTable.classList.add('hidden');
            incomesTable.classList.add('hidden');
            tabExpenses.classList.remove('border-b-2', 'border-red-600', 'text-red-700');
            tabExpenses.classList.add('border-b-2', 'border-transparent', 'text-gray-600');
            tabIncomes.classList.remove('border-b-2', 'border-green-600', 'text-green-700');
            tabIncomes.classList.add('border-b-2', 'border-transparent', 'text-gray-600');

            if (type === 'expenses') {
                expensesTable.classList.remove('hidden');
                tabExpenses.classList.add('border-b-2', 'border-red-600', 'text-red-700');
            } else {
                incomesTable.classList.remove('hidden');
                tabIncomes.classList.add('border-b-2', 'border-green-600', 'text-green-700');
            }
        }
    </script>
    @endsection

</x-app-layout>

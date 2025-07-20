<x-app-layout>
        <x-slot name="header">
            <div>
                @can('create expense')
                <button onclick="toggleTransactionForm()" class="bg-gradient-to-r from-white-600 to-gray-700 hover:from-gray-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
                    <p class="fas fa-plus mr-2 text-gray-600 dark:text-gray-300">Add expense</p>
                </button>
                @endcan
            </div>
        </x-slot>
        <div>
             @can('view expense')
                <div id="expenses-table" class="hidden">
                    <x-transaction-table 
                        title="Expense" 
                        items="{{ $expenses }}" 
                        type="expense"
                        color="red" 
                        icon="fa-coins" 
                        emptyText="No expense recorded yet" />
                </div>
            @endcan
        </div>
</x-app-layout>

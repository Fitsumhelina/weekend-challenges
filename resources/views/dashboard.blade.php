<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto px-4">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-6">Expenses</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                    @if($isAdmin)
                                        <th class="px-6 py-3"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($expenses as $expense)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $expense->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $expense->category }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $expense->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-mono">{{ number_format($expense->amount, 2) }}</td>
                                        @if($isAdmin)
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('expenses.delete', $expense->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 font-bold mr-2">Delete</button>
                                                </form>
                                                <a href="{{ route('expenses.show', $expense->id) }}" class="text-blue-600 font-bold">Edit</a>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $isAdmin ? 5 : 4 }}" class="border px-4 py-3 text-center">No expenses found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <h3 class="text-2xl font-bold mb-6">Incomes</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                    @if($isAdmin)
                                        <th class="px-6 py-3"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($incomes as $income)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $income->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $income->category ?? $income->source }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $income->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-mono">{{ number_format($income->amount, 2) }}</td>
                                        @if($isAdmin)
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <form action="{{ route('income.delete', $income->id) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 font-bold mr-2">Delete</button>
                                                </form>
                                                <a href="{{ route('income.show', $income->id) }}" class="text-blue-600 font-bold">Edit</a>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $isAdmin ? 5 : 4 }}" class="border px-4 py-3 text-center">No incomes found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($isAdmin)
                        <div class="mb-8">
                            <button onclick="document.getElementById('create-transaction').style.display='block'" class="bg-green-600 text-white px-6 py-3 rounded-lg font-bold shadow hover:bg-green-700">Add Transaction</button>
                        </div>
                        <div id="create-transaction" style="display:none;" class="bg-gray-50 dark:bg-gray-900 p-6 rounded-lg border border-gray-300 dark:border-gray-700 mb-8">
                            <h4 class="font-bold mb-4 text-xl">Add Expense</h4>
                            <form action="{{ route('expenses.store') }}" method="POST" class="mb-6 flex flex-wrap gap-4 items-center">
                                @csrf
                                <input type="text" name="title" placeholder="Title" class="border px-4 py-2 rounded w-48" required>
                                <input type="text" name="category" placeholder="Category" class="border px-4 py-2 rounded w-48">
                                <input type="text" name="description" placeholder="Description" class="border px-4 py-2 rounded w-64">
                                <input type="number" step="0.01" name="amount" placeholder="Amount" class="border px-4 py-2 rounded w-32" required>
                                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold shadow hover:bg-blue-700">Add Expense</button>
                            </form>
                            <h4 class="font-bold mb-4 text-xl">Add Income</h4>
                            <form action="{{ route('income.store') }}" method="POST" class="flex flex-wrap gap-4 items-center">
                                <input type="text" name="title" placeholder="Title" class="border px-4 py-2 rounded w-48" required>
                                <input type="text" name="category" placeholder="Category" class="border px-4 py-2 rounded w-48">
                                <input type="text" name="description" placeholder="Description" class="border px-4 py-2 rounded w-64">
                                <input type="number" step="0.01" name="amount" placeholder="Amount" class="border px-4 py-2 rounded w-32" required>
                                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold shadow hover:bg-blue-700">Add Income</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

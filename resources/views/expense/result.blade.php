<div class="p-6">
    @if ($expenses->isEmpty())
        <p class="text-gray-600 text-center py-4">No expenses found.</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Title
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Category
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Description
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Created By
                        </th>
                    
                        @can ('update expense')
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                        @endcan
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($expenses as $expense)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $expense->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $expense->category }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                ${{ number_format($expense->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ Str::limit($expense->description, 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $expense->createdByUser->name ?? 'N/A' }}
                            </td>
            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    @can('view expense')
                                        <button
                                            class="expense-view-btn text-blue-600 hover:text-blue-900 transition duration-150 ease-in-out"
                                            title="View Expense"
                                            data-id="{{ $expense->id }}"
                                            data-bs-toggle="modal" data-bs-target="#viewExpenseModal"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @endcan
                                    @can('update expense')
                                        <button
                                            class="expense-edit-btn text-indigo-600 hover:text-indigo-900 transition duration-150 ease-in-out"
                                            title="Edit Expense"
                                            data-id="{{ $expense->id }}"
                                            data-bs-toggle="modal" data-bs-target="#editExpenseModal"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endcan
                                    @can('delete expense')
                                        <form action="{{ route('expense.destroy', $expense->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="button"
                                                class="expense-delete-btn text-red-600 hover:text-red-900 transition duration-150 ease-in-out"
                                                title="Delete Expense"
                                            >
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $expenses->links('pagination::tailwind') }}
        </div>
    @endif
</div>

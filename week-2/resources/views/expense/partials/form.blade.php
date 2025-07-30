<form id="expenseForm" method="POST" action="{{ isset($expense) ? route('expense.update', $expense->id) : route('expense.store') }}">
    @csrf
    @if(isset($expense))
        @method('PUT')
        <input type="hidden" name="id" id="expense_id" value="{{ $expense->id }}">
    @else
        @method('POST')
        <input type="hidden" name="id" id="expense_id" value="">
    @endif

    <div class="modal-body">
        {{-- Your form fields go here --}}
        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
            <input type="text" name="title" id="title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('title', $expense->title ?? '') }}" required>
            <span class="text-red-500 text-xs italic" id="title-error"></span>
        </div>

        <div class="mb-4">
            <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount:</label>
            <input type="number" name="amount" id="amount" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('amount', $expense->amount ?? '') }}" required>
            <span class="text-red-500 text-xs italic" id="amount-error"></span>
        </div>

        <div class="mb-4">
            <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
            <input type="text" name="category" id="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('category', $expense->category ?? '') }}" required>
            <span class="text-red-500 text-xs italic" id="category-error"></span>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
            <textarea name="description" id="description" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('description', $expense->description ?? '') }}</textarea>
            <span class="text-red-500 text-xs italic" id="description-error"></span>
        </div>

        <div class="mb-6">
            <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date:</label>
            <input type="date" name="date" id="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('date', isset($expense) ? \Carbon\Carbon::parse($expense->date)->format('Y-m-d') : '') }}" required>
            <span class="text-red-500 text-xs italic" id="date-error"></span>
        </div>
    </div>
    <div class="modal-footer flex justify-end space-x-2 p-4 border-t border-gray-200">
        <button type="button" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition duration-300 close-modal" data-modal-id="expenseFormModal">Close</button>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-300 ease-in-out">
            {{ isset($expense) ? 'Update Expense' : 'Add Expense' }}
        </button>
    </div>
</form>

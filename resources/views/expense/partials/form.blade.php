<div class="modal-header bg-blue-600 text-white px-6 py-4 rounded-t-lg">
    <h5 class="modal-title text-xl font-semibold" id="{{ isset($expense) ? 'editExpenseModalLabel' : 'createExpenseModalLabel' }}">
        {{ isset($expense) ? 'Edit Expense' : 'Create New Expense' }}
    </h5>
    <button type="button" class="btn-close text-white" data-modal-id="expenseFormModal" aria-label="Close">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
<form id="expenseForm" action="{{ isset($expense) ? route('expense.update', $expense->id) : route('expense.store') }}" method="POST" class="p-6">
    @csrf
    @if(isset($expense))
        @method('PUT')
        <input type="hidden" name="id" id="expense_id" value="{{ $expense->id }}">
    @else
        <input type="hidden" name="id" id="expense_id" value="">
    @endif

    <div class="modal-body">
        <div class="mb-4">
            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
            <input type="text" name="title" id="title" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('title', $expense->title ?? '') }}" required>
            <span id="title-error" class="text-red-500 text-xs italic mt-1"></span>
        </div>

        <div class="mb-4">
            <label for="category" class="block text-gray-700 text-sm font-bold mb-2">Category:</label>
            <select name="category" id="category" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">Select Category</option>
                <option value="Food" {{ (isset($expense) && $expense->category == 'Food') ? 'selected' : '' }}>Food</option>
                <option value="Transport" {{ (isset($expense) && $expense->category == 'Transport') ? 'selected' : '' }}>Transport</option>
                <option value="Utilities" {{ (isset($expense) && $expense->category == 'Utilities') ? 'selected' : '' }}>Utilities</option>
                <option value="Entertainment" {{ (isset($expense) && $expense->category == 'Entertainment') ? 'selected' : '' }}>Entertainment</option>
                <option value="Rent" {{ (isset($expense) && $expense->category == 'Rent') ? 'selected' : '' }}>Rent</option>
                <option value="Other" {{ (isset($expense) && $expense->category == 'Other') ? 'selected' : '' }}>Other</option>
            </select>
            <span id="category-error" class="text-red-500 text-xs italic mt-1"></span>
        </div>

        <div class="mb-4">
            <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount:</label>
            <input type="number" name="amount" id="amount" step="0.01" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('amount', $expense->amount ?? '') }}" required>
            <span id="amount-error" class="text-red-500 text-xs italic mt-1"></span>
        </div>

        <div class="mb-6">
            <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date:</label>
            <input type="date" name="date" id="date" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('date', isset($expense) ? \Carbon\Carbon::parse($expense->date)->format('Y-m-d') : '') }}" required>
            <span id="date-error" class="text-red-500 text-xs italic mt-1"></span>
        </div>

        <div class="mb-6">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
            <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $expense->description ?? '') }}</textarea>
            <span id="description-error" class="text-red-500 text-xs italic mt-1"></span>
        </div>
    </div>
    <div class="modal-footer flex justify-end space-x-2 p-4 border-t border-gray-200">
        <button type="button" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition duration-300 close-modal" data-modal-id="expenseFormModal">Close</button>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-300">
            {{ isset($expense) ? 'Update Expense' : 'Save Expense' }}
        </button>
    </div>
</form>
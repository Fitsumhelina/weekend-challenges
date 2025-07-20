<div class="modal-header bg-blue-600 text-white px-6 py-4 rounded-t-lg">
    <h5 class="modal-title text-xl font-semibold" id="{{ isset($expense) ? 'editExpenseModalLabel' : 'createExpenseModalLabel' }}">
        {{ isset($expense) ? 'Edit Expense' : 'Create New Expense' }}
    </h5>
    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
<form id="{{ isset($expense) ? 'editForm' : 'createForm' }}" action="{{ isset($expense) ? route('expense.update', $expense->id) : route('expense.store') }}" method="POST" class="p-6">
    @csrf
    @if(isset($expense))
        @method('PUT')
    @endif

    <div class="mb-4">
        <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
        <input type="text" name="title" id="title" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('title', $expense->title ?? '') }}" required>
        <p id="title-error" class="text-red-500 text-xs italic mt-1"></p>
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
        <p id="category-error" class="text-red-500 text-xs italic mt-1"></p>
    </div>

    <div class="mb-4">
        <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount:</label>
        <input type="number" name="amount" id="amount" step="0.01" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ old('amount', $expense->amount ?? '') }}" required>
        <p id="amount-error" class="text-red-500 text-xs italic mt-1"></p>
    </div>

    <div class="mb-6">
        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
        <textarea name="description" id="description" rows="4" class="shadow appearance-none border rounded-lg w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>{{ old('description', $expense->description ?? '') }}</textarea>
        <p id="description-error" class="text-red-500 text-xs italic mt-1"></p>
    </div>

    <div class="flex items-center justify-end space-x-4">
        <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out" data-bs-dismiss="modal">
            Cancel
        </button>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105" data-loading-text="{{ isset($expense) ? 'Updating...' : 'Saving...' }}">
            <i class="fas fa-save mr-2"></i> {{ isset($expense) ? 'Update Expense' : 'Save Expense' }}
        </button>
    </div>
</form>

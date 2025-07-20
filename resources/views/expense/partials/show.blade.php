<div class="modal-header bg-blue-600 text-white px-6 py-4 rounded-t-lg">
    <h5 class="modal-title text-xl font-semibold" id="viewExpenseModalLabel">
        Expense Details
    </h5>
    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
<div class="modal-body p-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700">
        <div class="flex flex-col">
            <span class="text-sm font-semibold text-gray-500">Title:</span>
            <span class="text-lg font-medium">{{ $expense->title }}</span>
        </div>
        <div class="flex flex-col">
            <span class="text-sm font-semibold text-gray-500">Category:</span>
            <span class="text-lg font-medium">{{ $expense->category }}</span>
        </div>
        <div class="flex flex-col">
            <span class="text-sm font-semibold text-gray-500">Amount:</span>
            <span class="text-lg font-medium">${{ number_format($expense->amount, 2) }}</span>
        </div>
        <div class="flex flex-col">
            <span class="text-sm font-semibold text-gray-500">Description:</span>
            <span class="text-lg font-medium">{{ $expense->description }}</span>
        </div>
        <div class="flex flex-col">
            <span class="text-sm font-semibold text-gray-500">Created At:</span>
            <span class="text-lg font-medium">{{ $expense->created_at->format('M d, Y H:i A') }}</span>
        </div>
        <div class="flex flex-col">
            <span class="text-sm font-semibold text-gray-500">Updated At:</span>
            <span class="text-lg font-medium">{{ $expense->updated_at->format('M d, Y H:i A') }}</span>
        </div>
        <div class="flex flex-col">
            <span class="text-sm font-semibold text-gray-500">Created By:</span>
            <span class="text-lg font-medium">{{ $expense->createdByUser->name ?? 'N/A' }}</span>
        </div>
        <div class="flex flex-col">
            <span class="text-sm font-semibold text-gray-500">Updated By:</span>
            <span class="text-lg font-medium">{{ $expense->updatedByUser->name ?? 'N/A' }}</span>
        </div>
    </div>
</div>
<div class="modal-footer bg-gray-50 px-6 py-4 flex justify-end rounded-b-lg">
    <button type="button" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out" data-bs-dismiss="modal">
        Close
    </button>
</div>

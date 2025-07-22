<div class="bg-white rounded-2xl shadow-md p-6 space-y-5 text-sm text-gray-800">
    {{-- Title --}}
  <div class="modal-body p-6">
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
            <span class="text-sm font-semibold text-gray-500">Date:</span>
            <span class="text-lg font-medium">{{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}</span>
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

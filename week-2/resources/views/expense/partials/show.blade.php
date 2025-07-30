<div class="bg-white rounded-2xl shadow-md p-6 text-gray-800 text-sm sm:p-8">

  <div class="flex flex-wrap -mx-4 gap-y-6">

    {{-- Title --}}
    <div class="flex flex-col px-4 w-full sm:w-1/2 lg:w-1/3">
      <span class="text-sm font-semibold text-gray-500">Title:</span>
      <span class="text-lg font-medium">{{ $expense->title }}</span>
    </div>

    {{-- Category --}}
    <div class="flex flex-col px-4 w-full sm:w-1/2 lg:w-1/3">
      <span class="text-sm font-semibold text-gray-500">Category:</span>
      <span class="text-lg font-medium">{{ $expense->category }}</span>
    </div>

    {{-- Amount --}}
    <div class="flex flex-col px-4 w-full sm:w-1/2 lg:w-1/3">
      <span class="text-sm font-semibold text-gray-500">Amount:</span>
      <span class="text-lg font-medium">${{ number_format($expense->amount, 2) }}</span>
    </div>

    {{-- Date --}}
    <div class="flex flex-col px-4 w-full sm:w-1/2 lg:w-1/3">
      <span class="text-sm font-semibold text-gray-500">Date:</span>
      <span class="text-lg font-medium">{{ \Carbon\Carbon::parse($expense->date)->format('M d, Y') }}</span>
    </div>

    {{-- Created By --}}
    <div class="flex flex-col px-4 w-full sm:w-1/2 lg:w-1/3">
      <span class="text-sm font-semibold text-gray-500">Created By:</span>
      <span class="text-lg font-medium">{{ $expense->createdByUser->name ?? 'N/A' }}</span>
      <span class="text-xs text-gray-500">{{ $expense->created_at->format('M d, Y H:i A') }}</span>
    </div>

    {{-- Updated By --}}
    <div class="flex flex-col px-4 w-full sm:w-1/2 lg:w-1/3">
      <span class="text-sm font-semibold text-gray-500">Updated By:</span>
      <span class="text-lg font-medium">{{ $expense->updatedByUser->name ?? 'N/A' }}</span>
      <span class="text-xs text-gray-500">{{ $expense->updated_at->format('M d, Y H:i A') }}</span>
    </div>

    {{-- Description (Full width) --}}
    <div class="flex flex-col px-4 w-full">
      <span class="text-sm font-semibold text-gray-500">Description:</span>
      <span class="text-lg font-medium whitespace-pre-line">{{ $expense->description ?? 'N/A' }}</span>
    </div>

  </div>

  <div class="mt-8 flex justify-end">
    <a href="{{ route('expense.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
        <i data-lucide="x" class="w-5 h-5 mr-2"></i>
        Close
    </a>
</div>

</div>

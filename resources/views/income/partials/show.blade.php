<div class="bg-white rounded-2xl shadow-md p-6 text-gray-800 text-sm sm:p-8">

  {{-- Container with responsive flex wrap for fields --}}
  <div class="flex flex-wrap -mx-4 gap-y-6">

    {{-- Title --}}
    <div class="flex items-center gap-3 px-4 w-full sm:w-1/2 lg:w-1/3">
      <i data-lucide="type" class="text-blue-600 w-6 h-6"></i>
      <div>
        <p class="font-medium text-gray-600">Title</p>
        <p class="text-lg font-semibold text-gray-900 break-words">{{ $income->title }}</p>
      </div>
    </div>

    {{-- Amount --}}
    <div class="flex items-center gap-3 px-4 w-full sm:w-1/2 lg:w-1/3">
      <i data-lucide="dollar-sign" class="text-green-600 w-6 h-6"></i>
      <div>
        <p class="font-medium text-gray-600">Amount</p>
        <p class="text-lg font-semibold text-gray-900 break-words">{{ number_format($income->amount, 2) }}</p>
      </div>
    </div>

    {{-- Source --}}
    <div class="flex items-center gap-3 px-4 w-full sm:w-1/2 lg:w-1/3">
      <i data-lucide="user" class="text-purple-600 w-6 h-6"></i>
      <div>
        <p class="font-medium text-gray-600">Source</p>
        <p class="text-lg font-semibold text-gray-900 break-words">{{ $income->sourceUser->name ?? 'N/A' }}</p>
      </div>
    </div>

    {{-- Date --}}
    <div class="flex items-center gap-3 px-4 w-full sm:w-1/2 lg:w-1/3">
      <i data-lucide="calendar-days" class="text-cyan-600 w-6 h-6"></i>
      <div>
        <p class="font-medium text-gray-600">Date</p>
        <p class="text-base text-gray-900 break-words">{{ \Carbon\Carbon::parse($income->date)->format('M d, Y') }}</p>
      </div>
    </div>

    {{-- Status --}}
    <div class="flex items-center gap-3 px-4 w-full sm:w-1/2 lg:w-1/3">
      <i data-lucide="badge-check" class="text-emerald-600 w-6 h-6"></i>
      <div>
        <p class="font-medium text-gray-600">Status</p>
        <p class="text-base font-semibold {{ $income->status === 'pending' ? 'text-red-600' : 'text-green-700' }} break-words">{{ ucfirst($income->status) }}</p>
      </div>
    </div>

    {{-- Debt (if pending) --}}
    @if ($income->status === 'pending')
    <div class="flex items-center gap-3 px-4 w-full sm:w-1/2 lg:w-1/3">
      <i data-lucide="alert-circle" class="text-red-500 w-6 h-6"></i>
      <div>
        <p class="font-medium text-red-600">Debt Accumulated</p>
        <p class="text-base text-gray-900 break-words">{{ number_format($income->debt, 2) }}</p>
      </div>
    </div>
    @endif

    {{-- Created By --}}
    <div class="flex items-center gap-3 px-4 w-full sm:w-1/2 lg:w-1/3">
      <i data-lucide="user-plus" class="text-indigo-600 w-6 h-6"></i>
      <div>
        <p class="font-medium text-gray-600">Created By</p>
        <p class="text-base text-gray-900 break-words">{{ $income->createdByUser->name ?? 'N/A' }}</p>
        <p class="text-xs text-gray-500">{{ $income->created_at->format('M d, Y H:i:s') }}</p>
      </div>
    </div>

    {{-- Updated By --}}
    <div class="flex items-center gap-3 px-4 w-full sm:w-1/2 lg:w-1/3">
      <i data-lucide="user-check" class="text-indigo-600 w-6 h-6"></i>
      <div>
        <p class="font-medium text-gray-600">Last Updated By</p>
        <p class="text-base text-gray-900 break-words">{{ $income->updatedByUser->name ?? 'N/A' }}</p>
        <p class="text-xs text-gray-500">{{ $income->updated_at->format('M d, Y H:i:s') }}</p>
      </div><div class="mt-8 flex justify-end">
    <a href="{{ route('income.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
        <i data-lucide="x" class="w-5 h-5 mr-2"></i>
        Close
    </a>
</div>
    </div>

    {{-- Description (Full width for readability) --}}
    <div class="flex items-start gap-3 px-4 w-full">
      <i data-lucide="file-text" class="text-yellow-600 w-6 h-6 mt-1"></i>
      <div>
        <p class="font-medium text-gray-600">Description</p>
        <p class="text-base text-gray-900 break-words whitespace-pre-line">{{ $income->description ?? 'N/A' }}</p>
      </div>
    </div>

  </div>
<div class="mt-8 flex justify-end">
    <a href="{{ route('income.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition">
        <i data-lucide="x" class="w-5 h-5 mr-2"></i>
        Close
    </a>
</div>
</div>

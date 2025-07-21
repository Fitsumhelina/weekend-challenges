<div class="bg-white rounded-2xl shadow-md p-6 space-y-5 text-sm text-gray-800">
    {{-- Title --}}
    <div class="flex items-start gap-3">
        <i data-lucide="type" class="text-blue-600 w-5 h-5 mt-1"></i>
        <div>
            <p class="font-medium text-gray-600">Title</p>
            <p class="text-lg font-semibold text-gray-900">{{ $income->title }}</p>
        </div>
    </div>

    {{-- Amount --}}
    <div class="flex items-start gap-3">
        <i data-lucide="dollar-sign" class="text-green-600 w-5 h-5 mt-1"></i>
        <div>
            <p class="font-medium text-gray-600">Amount</p>
            <p class="text-lg font-semibold text-gray-900">{{ number_format($income->amount, 2) }}</p>
        </div>
    </div>

    {{-- Source --}}
    <div class="flex items-start gap-3">
        <i data-lucide="user" class="text-purple-600 w-5 h-5 mt-1"></i>
        <div>
            <p class="font-medium text-gray-600">Source</p>
            <p class="text-lg font-semibold text-gray-900">{{ $income->sourceUser->name ?? 'N/A' }}</p>
        </div>
    </div>

    {{-- Description --}}
    <div class="flex items-start gap-3">
        <i data-lucide="file-text" class="text-yellow-600 w-5 h-5 mt-1"></i>
        <div>
            <p class="font-medium text-gray-600">Description</p>
            <p class="text-base text-gray-900">{{ $income->description ?? 'N/A' }}</p>
        </div>
    </div>

    {{-- Date --}}
    <div class="flex items-start gap-3">
        <i data-lucide="calendar-days" class="text-cyan-600 w-5 h-5 mt-1"></i>
        <div>
            <p class="font-medium text-gray-600">Date</p>
            <p class="text-base text-gray-900">{{ \Carbon\Carbon::parse($income->date)->format('M d, Y') }}</p>
        </div>
    </div>

    {{-- Created By --}}
    <div class="flex items-start gap-3">
        <i data-lucide="user-plus" class="text-indigo-600 w-5 h-5 mt-1"></i>
        <div>
            <p class="font-medium text-gray-600">Created By</p>
            <p class="text-base text-gray-900">{{ $income->createdByUser->name ?? 'N/A' }}</p>
        </div>
    </div>

    {{-- Updated By --}}
    <div class="flex items-start gap-3">
        <i data-lucide="user-check" class="text-indigo-600 w-5 h-5 mt-1"></i>
        <div>
            <p class="font-medium text-gray-600">Last Updated By</p>
            <p class="text-base text-gray-900">{{ $income->updatedByUser->name ?? 'N/A' }}</p>
        </div>
    </div>

    {{-- Created At --}}
    <div class="flex items-start gap-3">
        <i data-lucide="clock" class="text-amber-600 w-5 h-5 mt-1"></i>
        <div>
            <p class="font-medium text-gray-600">Created At</p>
            <p class="text-base text-gray-900">{{ $income->created_at->format('M d, Y H:i:s') }}</p>
        </div>
    </div>

    {{-- Updated At --}}
    <div class="flex items-start gap-3">
        <i data-lucide="refresh-ccw" class="text-blue-600 w-5 h-5 mt-1"></i>
        <div>
            <p class="font-medium text-gray-600">Last Updated At</p>
            <p class="text-base text-gray-900">{{ $income->updated_at->format('M d, Y H:i:s') }}</p>
        </div>
    </div>

    {{-- Status --}}
    <div class="flex items-start gap-3">
        <i data-lucide="badge-check" class="text-emerald-600 w-5 h-5 mt-1"></i>
        <div>
            <p class="font-medium text-gray-600">Status</p>
            <p class="text-base font-semibold {{ $income->status === 'pending' ? 'text-red-600' : 'text-green-700' }}">{{ ucfirst($income->status) }}</p>
        </div>
    </div>

    {{-- Debt (if pending) --}}
    @if ($income->status === 'pending')
    <div class="flex items-start gap-3">
        <i data-lucide="alert-circle" class="text-red-500 w-5 h-5 mt-1"></i>
        <div>
            <p class="font-medium text-red-600">Debt Accumulated</p>
            <p class="text-base text-gray-900">{{ number_format($income->debt, 2) }}</p>
        </div>
    </div>
    @endif

    {{-- Footer --}}
    <div class="flex justify-end pt-4">
        <button type="button"
            class="close-modal inline-flex items-center gap-2 px-5 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-800 transition"
            data-modal-id="incomeFormModal">
            <i data-lucide="x" class="w-4 h-4"></i> Close
        </button>
    </div>
</div>

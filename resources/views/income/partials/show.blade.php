{{-- resources/views/income/show.blade.php --}}
{{-- This partial is loaded into the view modal body --}}

<div class="bg-white rounded-lg p-4">
    <div class="mb-4">
        <p class="text-gray-700 text-sm font-bold">Title:</p>
        <p class="text-gray-900 text-lg">{{ $income->title }}</p>
    </div>
    <div class="mb-4">
        <p class="text-gray-700 text-sm font-bold">Amount:</p>
        <p class="text-gray-900 text-lg">{{ number_format($income->amount, 2) }}</p>
    </div>
    <div class="mb-4">
        <p class="text-gray-700 text-sm font-bold">Source:</p>
        <p class="text-gray-900 text-lg">{{ $income->sourceUser->name ?? 'N/A' }}</p>
    </div>
    <div class="mb-4">
        <p class="text-gray-700 text-sm font-bold">Description:</p>
        <p class="text-gray-900 text-lg">{{ $income->description ?? 'N/A' }}</p>
    </div>
    <div class="mb-4">
        <p class="text-gray-700 text-sm font-bold">Date:</p>
        <p class="text-gray-900 text-lg">{{ \Carbon\Carbon::parse($income->date)->format('M d, Y') }}</p>
    </div>
    <div class="mb-4">
        <p class="text-gray-700 text-sm font-bold">Created By:</p>
        <p class="text-gray-900 text-lg">{{ $income->createdByUser->name ?? 'N/A' }}</p>
    </div>
    <div class="mb-4">
        <p class="text-gray-700 text-sm font-bold">Last Updated By:</p>
        <p class="text-gray-900 text-lg">{{ $income->updatedByUser->name ?? 'N/A' }}</p>
    </div>
    <div class="mb-4">
        <p class="text-gray-700 text-sm font-bold">Created At:</p>
        <p class="text-gray-900 text-lg">{{ $income->created_at->format('M d, Y H:i:s') }}</p>
    </div>
    <div class="mb-4">
        <p class="text-gray-700 text-sm font-bold">Last Updated At:</p>
        <p class="text-gray-900 text-lg">{{ $income->updated_at->format('M d, Y H:i:s') }}</p>
    </div>

    <div class="flex justify-end">
        <button type="button" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 focus:outline-none focus:shadow-outline transition duration-300 close-modal">
            Close
        </button>
    </div>
</div>
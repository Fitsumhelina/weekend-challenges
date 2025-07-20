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
        <p class="text-gray-900 text-lg">{{ $income->source }}</p>
    </div>
    <div class="mb-4">
        <p class="text-gray-700 text-sm font-bold">Description:</p>
        <p class="text-gray-900 text-lg">{{ $income->description ?? 'N/A' }}</p>
    </div>
    <div class="mb-4">
        <p class="text-gray-700 text-sm font-bold">Date:</p>
        <p class="text-gray-900 text-lg">{{ \Carbon\Carbon::parse($income->date)->format('M d, Y') }}</p>
    </div>
    <div class="text-right">
        <button class="close-modal bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md focus:outline-none focus:shadow-outline transition duration-300 ease-in-out">
            Close
        </button>
    </div>
</div>

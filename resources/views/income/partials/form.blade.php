<form id="incomeForm" method="POST" action="{{ isset($income) ? route('income.update', $income->id) : route('income.store') }}">
    @csrf
    @if(isset($income))
        @method('PUT')
        <input type="hidden" name="id" id="income_id" value="{{ $income->id }}">
    @else
        @method('POST')
        <input type="hidden" name="id" id="income_id" value="">
    @endif

    <div class="mb-4">
        <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Title:</label>
        <input type="text" name="title" id="title"
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('title') border-red-500 @enderror"
               value="{{ old('title', $income->title ?? '') }}" required>
        @error('title')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="amount" class="block text-gray-700 text-sm font-bold mb-2">Amount:</label>
        <input type="number" name="amount" id="amount" step="0.01"
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('amount') border-red-500 @enderror"
               value="{{ old('amount', $income->amount ?? '') }}" required>
        @error('amount')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="source" class="block text-gray-700 text-sm font-bold mb-2">Source:</label>
        <input type="text" name="source" id="source"
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('source') border-red-500 @enderror"
               value="{{ old('source', $income->source ?? '') }}" required>
        @error('source')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Description:</label>
        <textarea name="description" id="description" rows="3"
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror">{{ old('description', $income->description ?? '') }}</textarea>
        @error('description')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-6">
        <label for="date" class="block text-gray-700 text-sm font-bold mb-2">Date:</label>
        <input type="date" name="date" id="date"
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('date') border-red-500 @enderror"
               value="{{ old('date', isset($income) ? \Carbon\Carbon::parse($income->date)->format('Y-m-d') : '') }}" required>
        @error('date')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md focus:outline-none focus:shadow-outline transition duration-300 ease-in-out">
            {{ isset($income) ? 'Update Income' : 'Add Income' }}
        </button>
        <button type="button" class="close-modal ml-4 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md focus:outline-none focus:shadow-outline transition duration-300 ease-in-out">
            Cancel
        </button>
    </div>
</form>

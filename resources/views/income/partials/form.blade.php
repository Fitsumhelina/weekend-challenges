@props(['type', 'route'])

@php
    $color = $type === 'income' ? 'green' : 'red';
    $title = $type === 'income' ? 'Add Income' : 'Add Expense';
@endphp

<div class="bg-{{ $color }}-50 dark:bg-{{ $color }}-900/20 rounded-lg p-6 border border-{{ $color }}-200 dark:border-{{ $color }}-800">
    <h4 class="text-lg font-semibold text-{{ $color }}-800 dark:text-{{ $color }}-200 mb-4 flex items-center">
        <i class="fas fa-{{ $type === 'income' ? 'plus' : 'minus' }}-circle mr-2"></i>{{ $title }}
    </h4>
    <form action="{{ $route }}" method="POST" class="space-y-4">
        @csrf
        <x-input-group label="Title *" name="title" required />
        <x-input-group label="{{ $type === 'income' ? 'Source/Category' : 'Category' }}" name="{{ $type === 'income' ? 'source' : 'category' }}" />
        <x-textarea-group label="Description" name="description" />
        <x-input-group label="Amount *" name="amount" type="number" step="0.01" required />

        <button type="submit" class="w-full bg-{{ $color }}-600 hover:bg-{{ $color }}-700 text-gray-600 dark:text-gray-300 font-semibold py-2 px-4 rounded-lg transition duration-300">
            {{ $title }}
        </button>
    </form>
</div>

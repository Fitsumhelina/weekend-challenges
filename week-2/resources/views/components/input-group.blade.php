@props([
    'label',
    'name',
    'type' => 'text',
    'required' => false,
    'step' => null,
])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name) }}"
        @if($required) required @endif
        @if($step) step="{{ $step }}" @endif
        {{ $attributes->merge(['class' => 'w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white']) }}
    >
    @error($name)
        <span class="text-red-600 text-sm">{{ $message }}</span>
    @enderror
</div>
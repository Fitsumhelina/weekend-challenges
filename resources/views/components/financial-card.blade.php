@props(['title', 'amount', 'color', 'icon'])

<div class="bg-gradient-to-r from-{{ $color }}-500 to-{{ $color }}-600 rounded-xl shadow-lg p-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-{{ $color }}-100 text-sm font-medium text-gray-600 dark:text-gray-300">{{ $title }}</p>
            <p class="text-3xl font-bold text-gray-600 dark:text-gray-300">${{ $amount }}</p>
        </div>
        <div class="text-gray-600 dark:text-gray-300">
            <i class="fas {{ $icon }} text-2xl"></i>
        </div>
    </div>
</div>

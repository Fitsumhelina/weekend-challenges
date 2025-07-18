@props(['title', 'amount', 'color', 'icon'])

<div class="bg-gradient-to-r from-{{ $color }}-500 to-{{ $color }}-600 rounded-xl shadow-lg p-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-{{ $color }}-100 text-sm font-medium">{{ $title }}</p>
            <p class="text-3xl font-bold">${{ $amount }}</p>
        </div>
        <div class="bg-{{ $color }}-600 bg-opacity-30 rounded-full p-3">
            <i class="fas {{ $icon }} text-2xl"></i>
        </div>
    </div>
</div>

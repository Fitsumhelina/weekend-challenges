@props(['title', 'items', 'type', 'color', 'icon', 'emptyText'])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-8">
    <div class="bg-{{ $color }}-50 dark:bg-{{ $color }}-900/20 px-6 py-4 border-b border-{{ $color }}-200 dark:border-{{ $color }}-800">
        <h3 class="text-xl font-semibold text-{{ $color }}-800 dark:text-{{ $color }}-200 flex items-center">
            <i class="fas {{ $icon }} mr-2"></i>{{ $title }}
        </h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                        {{ $type === 'income' ? 'Source' : 'Category' }}
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                    @can ('update income') 
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    @endcan
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @if(is_countable($items) && count($items) > 0)
                    @foreach($items as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                {{ $item->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->title }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $color }}-100 text-{{ $color }}-800 dark:bg-{{ $color }}-900/20 dark:text-{{ $color }}-300">
                                    {{ $type === 'income' ? $item->source ?? 'Other' : $item->category ?? 'Uncategorized' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300 max-w-xs truncate">
                                {{ $item->description ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-semibold text-{{ $color }}-600 dark:text-{{ $color }}-400">
                                {{ $type === 'income' ? '+' : '-' }}${{ number_format($item->amount, 2) }}
                            </td>
                            @can ('delete income')
                                <td class="px-6 py-4 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <a href="{{ route("$type.destroy", $item->id) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route("$type.delete", $item->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Delete this {{ $type }}?')" 
                                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            <i class="fas {{ $type === 'income' ? 'fa-hand-holding-usd' : 'fa-receipt' }} text-4xl mb-4"></i>
                            <p class="text-lg font-medium">{{ $emptyText }}</p>
                            @can ('create income')
                                <p class="text-sm">Click "Add Transaction" to create your first {{ $type }} entry</p>
                            @endcan
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

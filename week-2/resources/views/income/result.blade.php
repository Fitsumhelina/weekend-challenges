<div class="overflow-x-auto rounded-lg shadow-md">
    <table class="min-w-full bg-white hidden md:table">
        <thead class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal">
            <tr>
                <th class="py-3 px-6 text-left">Title</th>
                <th class="py-3 px-6 text-left">Amount</th>
                <th class="py-3 px-6 text-left">From</th>
                <th class="py-3 px-6 text-left">Date</th>
                <th class="py-3 px-6 text-left">Status</th>
                <th class="py-3 px-6 text-left">Dept</th>
                <th class="py-3 px-6 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 text-sm font-light text-left">
            @forelse ($incomes as $income)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $income->title }}</td>
                    <td class="py-3 px-6 text-left">{{ number_format($income->amount, 2) }}</td>
                    <td class="py-3 px-6 text-left">{{ $income->sourceUser->name ?? 'Unknown' }}</td>
                    <td class="py-3 px-6 text-left">{{ \Carbon\Carbon::parse($income->date)->format('M d, Y') }}</td>
                    @if ($income->status === 'paid')
                        <td class="py-3 px-6 text-left text-green-600 font-semibold">{{ $income->status }}</td>
                    @else
                        <td class="py-3 px-6 text-left text-yellow-600 font-semibold">{{ $income->status }}</td>
                    @endif
                    <td class="py-3 px-6 text-left">
                        @if ($income->status === 'pending')
                            <span class="text-red-600 font-semibold">
                                {{ number_format($income->debt, 2) }} Br
                            </span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex items-center justify-center space-x-2">
                            @can('update income')
                                @if($income->status === 'pending')
                                    <form action="{{ route('income.approve', $income->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="w-8 h-8 rounded-full bg-green-100 text-green-600 hover:bg-green-200 flex items-center justify-center transition duration-300"
                                                title="Approve">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            @endcan

                            @can('view income')
                                <button type="button"
                                        class="view-income-btn w-8 h-8 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 flex items-center justify-center transition duration-300"
                                        title="View" data-id="{{ $income->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            @endcan
                            @can('update income')
                                <button type="button"
                                        class="edit-income-btn w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 hover:bg-yellow-200 flex items-center justify-center transition duration-300"
                                        title="Edit" data-id="{{ $income->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                            @endcan

                            @can('delete income')
                                <form action="{{ route('income.destroy', $income->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="delete-income-btn w-8 h-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center transition duration-300"
                                            title="Delete" data-id="{{ $income->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-gray-500 py-4">No income records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Mobile Card View -->
    <div class="md:hidden">
        @forelse ($incomes as $income)
            <div class="bg-white rounded-lg shadow mb-4 p-4">
                <div class="flex justify-between items-center mb-2">
                    <div class="font-semibold text-lg">{{ $income->title }}</div>
                    <div class="text-sm {{ $income->status === 'paid' ? 'text-green-600' : 'text-yellow-600' }} font-semibold">
                        {{ $income->status }}
                    </div>
                </div>
                <div class="text-sm text-gray-600 mb-1">
                    <span class="font-medium">Amount:</span> {{ number_format($income->amount, 2) }}
                </div>
                <div class="text-sm text-gray-600 mb-1">
                    <span class="font-medium">From:</span> {{ $income->sourceUser->name ?? 'Unknown' }}
                </div>
                <div class="text-sm text-gray-600 mb-1">
                    <span class="font-medium">Date:</span> {{ \Carbon\Carbon::parse($income->date)->format('M d, Y') }}
                </div>
                <div class="text-sm text-gray-600 mb-1">
                    <span class="font-medium">Dept:</span>
                    @if ($income->status === 'pending')
                        <span class="text-red-600 font-semibold">
                            {{ number_format($income->debt, 2) }} Br
                        </span>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </div>
                <div class="text-sm text-gray-600 mb-1">
                    <span class="font-medium">Created By:</span> {{ $income->createdByUser->name ?? 'Unknown' }}
                </div>
                <div class="flex items-center justify-end space-x-2 mt-2">
                    @can('update income')
                        @if($income->status === 'pending')
                            <form action="{{ route('income.approve', $income->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="w-8 h-8 rounded-full bg-green-100 text-green-600 hover:bg-green-200 flex items-center justify-center transition duration-300"
                                        title="Approve">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    @endcan

                    @can('view income')
                        <button type="button"
                                class="view-income-btn w-8 h-8 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 flex items-center justify-center transition duration-300"
                                title="View" data-id="{{ $income->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </button>
                    @endcan
                    @can('update income')
                        <button type="button"
                                class="edit-income-btn w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 hover:bg-yellow-200 flex items-center justify-center transition duration-300"
                                title="Edit" data-id="{{ $income->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </button>
                    @endcan

                    @can('delete income')
                        <form action="{{ route('income.destroy', $income->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="button"
                                    class="delete-income-btn w-8 h-8 rounded-full bg-red-100 text-red-600 hover:bg-red-200 flex items-center justify-center transition duration-300"
                                    title="Delete" data-id="{{ $income->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-4">No income records found.</div>
        @endforelse
    </div>
</div>

<div class="mt-4">
    {{ $incomes->links() }}
</div>

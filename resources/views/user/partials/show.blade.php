<div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 w-full max-w-4xl mx-auto">
    <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-6">User Profile</h2>

    {{-- Info Section --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <span class="text-gray-500 font-medium text-sm">Name</span>
            <p class="text-base sm:text-lg font-semibold text-gray-800">{{ $user->name ?? '-' }}</p>
        </div>

        <div>
            <span class="text-gray-500 font-medium text-sm">Email</span>
            <p class="text-base sm:text-lg font-medium text-gray-800">{{ $user->email ?? '-' }}</p>
        </div>

        <div class="sm:col-span-2">
            <span class="text-gray-500 font-medium text-sm">Roles</span>
            <div class="mt-2 flex flex-wrap gap-2">
                @forelse ($user->roles as $role)
                    <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-3 py-1 rounded-full">{{ $role->name }}</span>
                @empty
                    <span class="text-sm text-gray-400 italic">No roles assigned</span>
                @endforelse
            </div>
        </div>

        <div>
            <span class="text-gray-500 font-medium text-sm">Created At</span>
            <p class="text-base font-medium text-gray-700">
                {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y H:i A') }}
            </p>
        </div>

        <div>
            <span class="text-gray-500 font-medium text-sm">Debt</span>
            <p class="text-lg font-bold text-red-700">{{ number_format($totalDebt, 2) }} ETB</p>
        </div>
    </div>

 {{-- Income History Table --}}
<div class="mt-8">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">Recent Income Entries</h3>

    <div class="overflow-x-auto border rounded-xl shadow max-h-[20rem]">
        <table class="min-w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-100 text-xs text-gray-600 uppercase sticky top-0 z-10">
                <tr>
                    <th class="px-5 py-3 whitespace-nowrap">Title</th>
                    <th class="px-5 py-3 text-right whitespace-nowrap">Amount</th>
                    <th class="px-5 py-3 whitespace-nowrap">Date</th>
                    <th class="px-5 py-3 whitespace-nowrap">Status</th>
                    <th class="px-5 py-3 text-right whitespace-nowrap">Debt</th>
                    <th class="px-5 py-3 min-w-[240px]">Description</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @foreach ($income->take(5) as $entry)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 truncate max-w-[160px]" title="{{ $entry->title }}">
                            {{ $entry->title }}
                        </td>
                        <td class="px-5 py-3 text-right text-green-700 font-semibold">
                            {{ number_format($entry->amount, 2) }} ETB
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($entry->created_at)->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2">
                                @if(strtolower($entry->status) === 'paid')
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                @elseif(strtolower($entry->status) === 'pending')
                                    <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3"/>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" stroke-width="2"/>
                                    </svg>
                                @endif
                                <span class="capitalize">{{ $entry->status }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-right text-red-600 font-semibold">
                            {{ number_format($entry->debt, 2) }}
                        </td>
                        <td class="px-5 py-3 truncate max-w-[260px]" title="{{ $entry->description ?? '-' }}">
                            {{ $entry->description ?? '-' }}
                        </td>
                    </tr>
                @endforeach

                @if($income->count() < 5)
                    @for($i = $income->count(); $i < 5; $i++)
                        <tr>
                            <td colspan="6" class="px-5 py-3 text-center text-gray-400 italic">No data</td>
                        </tr>
                    @endfor
                @endif
            </tbody>
        </table>
    </div>
</div>



    {{-- Close Button --}}
    <div class="mt-6 text-right">
        <button class="close-modal bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-md shadow transition duration-300">
            Close
        </button>
    </div>
</div>

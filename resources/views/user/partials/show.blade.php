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

        {{-- Password Placeholder --}}
        <div class="sm:col-span-2" x-data="{ show: false }">
            <span class="text-gray-500 font-medium text-sm">Password</span>
            <div class="relative mt-1">
                <input :type="show ? 'text' : 'password'" value="********" readonly
                       class="w-full px-4 py-2 bg-gray-100 text-gray-700 border rounded-md focus:outline-none focus:ring focus:ring-indigo-300 pr-10">
                <button type="button" @click="show = !show"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7" />
                    </svg>
                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <p class="text-xs text-gray-400 mt-1">Password is hidden for security.</p>
        </div>
    </div>

    {{-- Income History Table --}}
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-700 mb-2">Recent Income Entries</h3>
        <div class="overflow-x-auto border rounded-lg shadow-sm max-h-60">
            <table class="min-w-[800px] w-full text-sm text-left text-gray-700 divide-y divide-gray-200">
                <thead class="bg-gray-100 text-xs text-gray-500 uppercase tracking-wide sticky top-0 z-10">
                    <tr>
                        <th class="px-3 py-2 whitespace-nowrap">Title</th>
                        <th class="px-3 py-2 whitespace-nowrap">Amount</th>
                        <th class="px-3 py-2 whitespace-nowrap">Date</th>
                        <th class="px-3 py-2 whitespace-nowrap">Status</th>
                        <th class="px-3 py-2 whitespace-nowrap">Debt</th>
                        <th class="px-3 py-2 min-w-[250px]">Description</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach ($income->take(4) as $entry)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-3 py-2 font-medium">{{ $entry->title }}</td>
                            <td class="px-3 py-2 text-green-700 font-semibold">{{ number_format($entry->amount, 2) }} ETB</td>
                            <td class="px-3 py-2">{{ \Carbon\Carbon::parse($entry->created_at)->format('M d, Y H:i A') }}</td>
                            <td class="px-3 py-2">{{ $entry->status }}</td>
                            <td class="px-3 py-2 text-red-700">{{ $entry->debt }}</td>
                            <td class="px-3 py-2 break-words text-gray-800">{{ $entry->description ?? '-' }}</td>
                        </tr>
                    @endforeach
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

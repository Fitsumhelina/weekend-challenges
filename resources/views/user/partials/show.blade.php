<div class="bg-white rounded-xl shadow-lg p-6 sm:p-8 max-w-4xl w-full mx-auto">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">User Profile</h2>

    {{-- Info Section --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        {{-- Name --}}
        <div>
            <label class="block text-sm font-medium text-gray-500">Name</label>
            <p class="text-base text-gray-900">{{ $user->name ?? '-' }}</p>
        </div>

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-500">Email</label>
            <p class="text-base text-gray-900">{{ $user->email ?? '-' }}</p>
        </div>

        {{-- Roles --}}
        <div class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-500">Roles</label>
            <div class="flex flex-wrap gap-2 mt-1">
                @forelse ($user->roles as $role)
                    <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $role->name }}
                    </span>
                @empty
                    <span class="text-sm italic text-gray-400">No roles assigned.</span>
                @endforelse
            </div>
        </div>

        {{-- Created At --}}
        <div>
            <label class="block text-sm font-medium text-gray-500">Created At</label>
            <p class="text-base text-gray-900">
                {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y H:i A') }}
            </p>
        </div>
         {{-- Dept --}}
        <div>
            <label class="block text-sm font-medium text-gray-500">Dept</label>
            <p class="font-bold text-base text-red-900">{{ number_format($totalDebt, 2)}} ETB</p>
        </div>
        

        {{-- Password Display --}}
        <div x-data="{ show: false }" class="sm:col-span-2">
            <label class="block text-sm font-medium text-gray-500">Password</label>
            <div class="relative mt-1">
                <input
                    :type="show ? 'text' : 'password'"
                    value="********"
                    readonly
                    class="w-full bg-gray-100 text-gray-800 border border-gray-300 rounded-md px-3 py-2 pr-10 text-sm focus:outline-none focus:ring focus:ring-indigo-200"
                >
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

{{-- Income Table Container --}}
<div class="rounded-lg shadow-sm border overflow-x-auto max-h-[220px] sm:max-h-[240px]">
    <table class="min-w-[900px] w-full text-sm text-left divide-y divide-gray-200">
        <thead class="sticky top-0 bg-gray-50 z-10 text-xs text-gray-500 uppercase tracking-wide">
            <tr>
                <th class="w-24 px-2 py-1.5">Title</th>
                <th class="w-20 px-2 py-1.5">Amount</th>
                <th class="w-36 px-2 py-1.5">Date</th>
                <th class="w-20 px-2 py-1.5">Status</th>
                <th class="w-20 px-2 py-1.5">Debt</th>
                <th class="min-w-[300px] px-3 py-1.5">Description</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @foreach ($income->take(4) as $entry)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-2 py-1 whitespace-nowrap">{{ $entry->title }}</td>
                    <td class="px-2 py-1 text-green-700 font-semibold whitespace-nowrap">
                        {{ number_format($entry->amount, 2) }} ETB
                    </td>
                    <td class="px-2 py-1 text-gray-600 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($entry->created_at)->format('M d, Y H:i A') }}
                    </td>
                    <td class="px-2 py-1 whitespace-nowrap">{{ $entry->status }}</td>
                    <td class="px-2 py-1 whitespace-nowrap">{{ $entry->debt }}</td>
                    <td class="px-3 py-1 text-gray-800 break-words">{{ $entry->description ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>



    {{-- Action --}}
    <div class="text-right mt-8">
        <button class="close-modal bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-md shadow transition duration-300">
            Close
        </button>
    </div>
</div>

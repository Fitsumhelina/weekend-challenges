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

    {{-- Income Section --}}
  <table class="w-full table-auto border-t text-sm text-left">
  <thead class="bg-gray-50 text-xs font-semibold text-gray-600 uppercase">
    <tr>
      <th class="px-4 py-2">Source</th>
      <th class="px-4 py-2">Amount</th>
      <th class="px-4 py-2">Date</th>
      <th class="px-4 py-2">Description</th>
    </tr>
  </thead>
  <tbody class="divide-y">
    @foreach($user->incomes as $income)
    <tr>
      <td class="px-4 py-2 text-gray-800">{{ $income->source->name ?? 'N/A' }}</td>
      <td class="px-4 py-2">
        <span class="text-green-600 font-semibold">{{ number_format($income->amount, 2) }}</span>
        <span class="text-xs text-gray-500">ETB</span>
      </td>
      <td class="px-4 py-2">
        <div class="font-medium text-gray-700">{{ $income->created_at->format('M d, Y') }}</div>
        <div class="text-xs text-gray-500">{{ $income->created_at->format('h:i A') }}</div>
      </td>
      <td class="px-4 py-2 text-gray-700 max-w-xs truncate" title="{{ $income->description }}">
        {{ Str::limit($income->description, 80) }}
      </td>
    </tr>
    @endforeach
  </tbody>
</table>


    {{-- Action --}}
    <div class="text-right mt-8">
        <button class="close-modal bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded-md shadow transition duration-300">
            Close
        </button>
    </div>
</div>

<div class="bg-white rounded-lg p-4">
    <div class="mb-4">
        <p class="text-gray-700 text-sm font-bold">Name:</p>
        <p class="text-gray-900 text-lg">{{ $user->name }}</p>
    </div>
    <div class="mb-4">
        <p class="text-gray-700 text-sm font-bold">Email:</p>
        <p class="text-gray-900 text-lg">{{ $user->email }}</p>
    </div>
    <div class="mb-4">
        <p class="text-gray-700 text-sm font-bold">Roles:</p>
        <div class="flex flex-wrap gap-2">
            @forelse ($user->roles as $role)
                <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full">
                    {{ $role->name }}
                </span>
            @empty
                <span class="text-gray-500 text-xs">No roles assigned.</span>
            @endforelse
        </div>
    </div>
    <div class="mb-4">
        <p class="text-gray-700 text-sm font-bold">Created At:</p>
        <p class="text-gray-900 text-lg">{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y H:i A') }}</p>
    </div>
    <div class="mb-4">
        <p class="text-gray-700 text-sm font-bold">Last Updated At:</p>
        <p class="text-gray-900 text-lg">{{ \Carbon\Carbon::parse($user->updated_at)->format('M d, Y H:i A') }}</p>
    </div>
    <div class="text-right">
        <button class="close-modal bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md focus:outline-none focus:shadow-outline transition duration-300 ease-in-out">
            Close
        </button>
    </div>
</div>

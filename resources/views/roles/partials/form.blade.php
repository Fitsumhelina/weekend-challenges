{{-- The form is designed to be used for both create and edit within the same modal --}}
<form id="roleForm" method="POST" action="{{ isset($role) ? route('roles.update', $role->id) : route('roles.store') }}">
    @csrf
    @if(isset($role))
        @method('PUT')
    @else
        @method('POST')
    @endif

    <div class="mb-4">
        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Role Name:</label>
        <input type="text" name="name" id="name"
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
               value="{{ old('name', $role->name ?? '') }}" required>
        @error('name')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2">Permissions:</label>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 max-h-60 overflow-y-auto border p-4 rounded-md bg-gray-50">
            @forelse ($allPermissions ?? [] as $permission)
                <div class="flex items-center">
                    <input type="checkbox" name="permissions[]" id="permission_{{ $permission->id }}" value="{{ $permission->name }}"
                           class="form-checkbox h-4 w-4 text-blue-600 rounded focus:ring-blue-500"
                           @if(isset($role) && $role->hasPermissionTo($permission->name)) checked @endif>
                    <label for="permission_{{ $permission->id }}" class="ml-2 text-gray-700 text-sm">{{ $permission->name }}</label>
                </div>
            @empty
                <p class="text-gray-500 text-sm col-span-full">No permissions available. Please create some permissions first.</p>
            @endforelse
        </div>
        @error('permissions')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end space-x-4">
        <button type="button" class="close-modal bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md focus:outline-none focus:shadow-outline transition duration-300 ease-in-out">
            Close
        </button>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md focus:outline-none focus:shadow-outline transition duration-300 ease-in-out">
            {{ isset($role) ? 'Save Changes' : 'Save Role' }}
        </button>
    </div>
</form>

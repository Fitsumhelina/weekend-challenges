{{-- The form is designed to be used for both create and edit within the same modal --}}
<form id="permissionForm" method="POST" action="{{ isset($permission) ? route('permission.update', $permission->id) : route('permission.store') }}">
    @csrf
    @if(isset($permission))
        @method('PUT')
        <input type="hidden" name="id" id="permission_id" value="{{ $permission->id }}">
    @else
        @method('POST')
        <input type="hidden" name="id" id="permission_id" value="">

    @endif

    <div class="mb-4">
        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Permission Name:</label>
        <input type="text" name="name" id="name"
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
               value="{{ old('name', $permission->name ?? '') }}" required>
        @error('name')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end space-x-4">
        <button type="button" class="close-modal bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md focus:outline-none focus:shadow-outline transition duration-300 ease-in-out">
            Close
        </button>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md focus:outline-none focus:shadow-outline transition duration-300 ease-in-out">
            {{ isset($permission) ? 'Save Changes' : 'Save Permission' }}
        </button>
    </div>
</form>

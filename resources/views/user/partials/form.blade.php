<form id="userForm" method="POST" action="{{ isset($user) ? route('user.update', $user->id) : route('user.store') }}">
    @csrf
    @if(isset($user))
        @method('PUT')
    @else
        @method('POST')
    @endif

    <div class="mb-4">
        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
        <input type="text" name="name" id="name"
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror"
               value="{{ old('name', $user->name ?? '') }}" required>
        @error('name')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
        <input type="email" name="email" id="email"
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror"
               value="{{ old('email', $user->email ?? '') }}" required>
        @error('email')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
        <input type="password" name="password" id="password"
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror"
               {{ isset($user) ? '' : 'required' }}> {{-- Password is required for create, optional for edit --}}
        @error('password')
            <p class="text-red-500 text-xs italic">{{ $message }}</p>
        @enderror
        @if(isset($user))
            <p class="text-gray-500 text-xs mt-1">Leave blank to keep current password.</p>
        @endif
    </div>

    <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2">Roles:</label>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 max-h-60 overflow-y-auto border p-4 rounded-md bg-gray-50">
            @forelse ($roles as $role)
                <div class="flex items-center">
                    <input type="checkbox" name="roles[]" id="role_{{ $role->id }}" value="{{ $role->name }}"
                           class="form-checkbox h-4 w-4 text-blue-600 rounded focus:ring-blue-500"
                           @if(isset($user) && $user->hasRole($role->name)) checked @endif>
                    <label for="role_{{ $role->id }}" class="ml-2 text-gray-700 text-sm">{{ $role->name }}</label>
                </div>
            @empty
                <p class="text-gray-500 text-sm col-span-full">No roles available. Please create some roles first.</p>
            @endforelse
        </div>
        @error('roles')
            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end space-x-4">
        <button type="button" class="close-modal bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md focus:outline-none focus:shadow-outline transition duration-300 ease-in-out">
            Close
        </button>
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md focus:outline-none focus:shadow-outline transition duration-300 ease-in-out">
            {{ isset($user) ? 'Save Changes' : 'Create User' }}
        </button>
    </div>
</form>

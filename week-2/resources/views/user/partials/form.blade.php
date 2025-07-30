<form id="userForm" method="POST" action="{{ isset($user) ? route('user.update', $user) : route('user.store') }}">
    @csrf
    @if(isset($user))
        @method('PUT')
    @endif

    <div class="space-y-6">

        {{-- Name --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <span class="text-red-500 text-xs italic" id="name-error"></span>
            
        </div>
        
        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}"  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <span class="text-red-500 text-xs italic" id="email-error"></span>
        </div>

        {{-- Password --}}
        <div x-data="{ show: false }">
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <div class="relative">
                <input :type="show ? 'text' : 'password'" name="password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    placeholder="{{ isset($user) ? 'Leave blank to keep current password' : 'Enter password' }}">
                <button type="button" x-on:click="show = !show"
                    class="absolute inset-y-0 right-0 px-3 flex items-center text-sm text-gray-600">
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-.174.57-.399 1.118-.669 1.639M15.5 19.04A9.953 9.953 0 0112 21c-4.477 0-8.268-2.943-9.542-7a9.953 9.953 0 011.356-2.618" />
                    </svg>
                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 0 011.663-3.043M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3l18 18" />
                    </svg>
                </button>
            </div>
             <span class="text-red-500 text-xs italic" id="password-error"></span>

        </div>

        {{-- Confirm Password --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" name="password_confirmation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        {{-- Roles --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Select Role</label>
            <div class="space-y-2">
                @foreach($roles as $role)
                    <label class="flex items-center space-x-3">
                        <input type="radio" name="roles[0]" value="{{ $role->name }}"
                            class="form-radio text-indigo-600"
                            {{ isset($user) && $user->roles->pluck('name')->contains($role->name) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">{{ $role->name }}</span>
                    </label>
                @endforeach
            </div>
            <span class="text-red-500 text-xs italic" id="role-error"></span>
        </div>

        {{-- Submit Button --}}
        <div class="text-right">
            <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-md shadow-md transition-all duration-300">
                {{ isset($user) ? 'Update User' : 'Create User' }}
            </button>
        </div>
    </div>
</form>

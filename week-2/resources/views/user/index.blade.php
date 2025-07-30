@extends('layouts.app')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-4 sm:py-8">
    <div class="bg-white shadow-lg rounded-lg p-3 sm:p-6 mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 space-y-2 sm:space-y-0">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">User Management</h1>
            @can('create user')
                <button id="createUserBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out w-full sm:w-auto">
                    Add New User
                </button>
            @endcan
        </div>

        {{-- Success/Error Alert Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-2 sm:px-4 py-2 sm:py-3 rounded relative mb-2 sm:mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-2 sm:px-4 py-2 sm:py-3">
                    <svg class="fill-current h-5 w-5 sm:h-6 sm:w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none';"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-2 sm:px-4 py-2 sm:py-3 rounded relative mb-2 sm:mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-2 sm:px-4 py-2 sm:py-3">
                    <svg class="fill-current h-5 w-5 sm:h-6 sm:w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none';"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </span>
            </div>
        @endif

        {{-- Search Form --}}
        <form id="user-search-form" action="{{ route('user.index') }}" method="GET" class="mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                <input type="text" name="search" id="user-search" placeholder="Search users..."
                       value="{{ request('search') }}"
                       class="flex-grow w-full sm:w-auto px-3 sm:px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                <select name="per_page"
                        class="w-full sm:w-auto px-3 sm:px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 per page</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per page</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per page</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 per page</option>
                </select>
                <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-300 ease-in-out w-full sm:w-auto">
                    Search
                </button>
            </div>
        </form>

        {{-- User List (result.blade.php) --}}
        <div id="user-list-container" class="overflow-x-auto">
            @include('user.result', ['users' => $users])
        </div>
    </div>

      {{-- Create/Edit user Modal --}}
    <div id="userFormModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center">
        <div class="relative mx-auto p-3 sm:p-5 border w-11/12 sm:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-2 sm:pb-3">
                <h3 class="text-xl sm:text-2xl leading-6 font-medium text-gray-900" id="userFormModalTitle"></h3>
                <button class="text-gray-400 hover:text-gray-600 close-modal mt-2 sm:mt-0" data-modal-id="userFormModal">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="mt-2 px-2 sm:px-7 py-2 sm:py-3" id="userFormModalContent">
            </div>
        </div>
    </div>

    {{-- View User Modal --}}
    <div id="viewUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 sm:top-20 mx-auto p-2 sm:p-5 border w-11/10 sm:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-2 sm:pb-3">
                <h3 class="text-xl sm:text-2xl leading-6 font-medium text-gray-900">User Details</h3>
                <button class="text-gray-400 hover:text-gray-600 close-modal mt-2 sm:mt-0" data-modal-id="viewUserModal">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="mt-2 px-2 sm:px-7 py-2 sm:py-3" id="viewUserContent">
            </div>
        </div>
    </div>

    
    <div class="modal hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50" id="deleteConfirmationModal">
        <div class="relative mx-auto top-10 sm:top-20 p-2 sm:p-5 border w-11/12 sm:w-96 shadow-lg rounded-md bg-white">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-2 sm:pb-3">
                <h3 class="text-lg sm:text-xl leading-6 font-medium text-gray-900">Confirm Deletion</h3>
                <button class="text-gray-400 hover:text-gray-600 close-modal mt-2 sm:mt-0" data-modal-id="deleteConfirmationModal">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="mt-2 px-2 sm:px-7 py-2 sm:py-3">
                <p class="text-gray-700">Are you sure you want to delete this user record?</p>
            </div>
            <div class="items-center px-2 sm:px-4 py-2 sm:py-3 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4">
                <button id="cancelDeleteBtn" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition duration-300 w-full sm:w-auto">Cancel</button>
                <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-300 w-full sm:w-auto">Delete</button>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
   <script>
        const AppData = {
            UserIndexRoute: "{{ route('user.index') }}",
            UserCreateRoute: "{{ route('user.create') }}",
            csrfToken: "{{ csrf_token() }}"
        };

        window.openModal = function(modalElement) {
            if (modalElement) {
                modalElement.classList.remove('hidden');
                modalElement.classList.add('flex');
            }
        };

        window.closeModal = function(modalElement) {
            if (modalElement) {
                modalElement.classList.add('hidden');
                modalElement.classList.remove('flex');
            }
        };

    </script>
@endsection

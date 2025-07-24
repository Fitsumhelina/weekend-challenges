@extends('layouts.app')

@section('content')
<div class="container mx-auto px-2 sm:px-4 py-6 sm:py-8">
    <div class="bg-white shadow-lg rounded-lg p-4 sm:p-6 mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-2 sm:gap-0">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Role Management</h1>
            @can('create role')
                <button id="createRoleBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out w-full sm:w-auto">
                    Add New Role
                </button>
            @endcan
        </div>

        {{-- Success/Error Alert Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-2 sm:px-4 py-2 sm:py-3 rounded relative mb-2 sm:mb-4 text-sm sm:text-base" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-2 sm:px-4 py-2 sm:py-3">
                    <svg class="fill-current h-5 w-5 sm:h-6 sm:w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none';"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-2 sm:px-4 py-2 sm:py-3 rounded relative mb-2 sm:mb-4 text-sm sm:text-base" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-2 sm:px-4 py-2 sm:py-3">
                    <svg class="fill-current h-5 w-5 sm:h-6 sm:w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none';"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </span>
            </div>
        @endif

        {{-- Search Form --}}
        <form id="role-search-form" action="{{ route('role.index') }}" method="GET" class="mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                <input type="text" name="search" id="role-search" placeholder="Search role..."
                       value="{{ request('search') }}"
                       class="flex-grow w-full sm:w-auto px-3 sm:px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm text-sm sm:text-base">
                
                <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-300 ease-in-out w-full sm:w-auto text-sm sm:text-base">
                    Search
                </button>
            </div>
        </form>

        {{-- Role List (result.blade.php) --}}
        <div id="role-list-container" class="overflow-x-auto">
            @include('role.result', ['roles' => $roles])
        </div>
    </div>

    {{-- Create/Edit Role Modal --}}
    <div id="roleFormModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 sm:top-20 mx-auto p-3 sm:p-5 border w-11/12 sm:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-2 sm:pb-3 gap-2 sm:gap-0">
                <h3 class="text-xl sm:text-2xl leading-6 font-medium text-gray-900" id="roleFormModalTitle"></h3>
                <button class="text-gray-400 hover:text-gray-600 close-modal">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="mt-2 px-2 sm:px-7 py-2 sm:py-3" id="roleFormModalContent">
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteConfirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-10 sm:top-20 mx-auto p-3 sm:p-5 border w-11/12 sm:w-96 shadow-lg rounded-md bg-white">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-2 sm:pb-3 gap-2 sm:gap-0">
                <h3 class="text-lg sm:text-xl leading-6 font-medium text-gray-900">Confirm Deletion</h3>
                <button class="text-gray-400 hover:text-gray-600 close-modal" data-modal-id="deleteConfirmationModal">
                    <svg class="h-5 w-5 sm:h-6 sm:w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="mt-2 px-2 sm:px-7 py-2 sm:py-3">
                <p class="text-gray-700 text-sm sm:text-base">Are you sure you want to delete this role?</p>
            </div>
            <div class="items-center px-2 sm:px-4 py-2 sm:py-3 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-4">
                <button id="cancelDeleteBtn" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition duration-300 w-full sm:w-auto text-sm sm:text-base">Cancel</button>
                <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-300 w-full sm:w-auto text-sm sm:text-base">Delete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const Data = {
        RoleIndexRoute: "{{ route('role.index') }}",
        RoleCreateRoute: "{{ route('role.create') }}",
        csrfToken: "{{ csrf_token() }}",
    };

    window.openModal = function(modalElement) {
        if (modalElement) {
            modalElement.classList.remove('hidden');
            modalElement.classList.add('flex');
            modalElement.style.display = 'flex'; // Ensure visible
        }
    };
    window.closeModal = function(modalElement) {
        if (modalElement) {
            modalElement.classList.add('hidden');
            modalElement.classList.remove('flex');
            modalElement.style.display = 'none'; // Ensure hidden
        }
    };

</script>
@endsection

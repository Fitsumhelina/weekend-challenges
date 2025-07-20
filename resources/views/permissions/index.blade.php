@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Permission Management</h1>
            @can('create permission')
                <button id="createPermissionBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                    Add New Permission
                </button>
            @endcan
        </div>

        {{-- Success/Error Alert Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none';"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.style.display='none';"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </span>
            </div>
        @endif

        {{-- Search Form --}}
        <form id="permission-search-form" action="{{ route('permissions.index') }}" method="GET" class="mb-6">
            <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <input type="text" name="search" id="permission-search" placeholder="Search permissions..."
                       value="{{ request('search') }}"
                       class="flex-grow w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                <select name="per_page" id="permission-per_page" class="px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    <option value="10" @if(request('per_page', 10) == 10) selected @endif>10 per page</option>
                    <option value="25" @if(request('per_page') == 25) selected @endif>25 per page</option>
                    <option value="50" @if(request('per_page') == 50) selected @endif>50 per page</option>
                </select>
                <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-300 ease-in-out w-full sm:w-auto">
                    Search
                </button>
            </div>
        </form>

        {{-- Permission List (result.blade.php) --}}
        <div id="permission-search-results">
            @include('permissions.result', ['permissions' => $permissions])
        </div>
    </div>

    {{-- Create/Edit Permission Modal --}}
    <div id="createPermissionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-2xl leading-6 font-medium text-gray-900" id="permissionModalTitle"></h3>
                <button class="text-gray-400 hover:text-gray-600 close-modal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="mt-2 px-7 py-3" id="permissionModalContent">
                {{-- Form content will be loaded here via AJAX for edit, or directly for create --}}
                @include('permissions.partials.form', ['permission' => null]) {{-- Pass null for create --}}
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div id="deleteConfirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-xl leading-6 font-medium text-gray-900">Confirm Deletion</h3>
                <button class="text-gray-400 hover:text-gray-600 close-modal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="mt-2 px-7 py-3">
                <p class="text-gray-700">Are you sure you want to delete this permission?</p>
            </div>
            <div class="items-center px-4 py-3 flex justify-end space-x-4">
                <button id="cancelDeleteBtn" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition duration-300">Cancel</button>
                <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-300">Delete</button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- For Swal.fire --}}

{{-- Pass data to JS --}}
<script>
    const Data = {
        PermissionIndexRoute: "{{ route('permissions.index') }}",
        csrfToken: "{{ csrf_token() }}",
    };
</script>

{{-- Import the ListHandler and PermissionListHandler --}}
<script type="module">
    import { PermissionListHandler } from "{{ asset('js/permission.js') }}"; // Adjust path as needed

    $(document).ready(function() {
        // Initialize the PermissionListHandler
        const permissionListHandler = new PermissionListHandler({
            indexRoute: Data.PermissionIndexRoute,
            csrfToken: Data.csrfToken,
            entityName: 'permission',
            routeName: '/permissions', // Matches your web.php route prefix
            modalAddFormId: 'createPermissionModal',
            modalEditFormId: 'createPermissionModal', // Re-use for edit
            modalViewFormId: 'viewPermissionModal' // Not used for permissions, but kept for ListHandler consistency
        });

        // Manual modal control for TailwindCSS
        const permissionModal = document.getElementById('createPermissionModal');
        const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
        const createPermissionBtn = document.getElementById('createPermissionBtn');
        const permissionModalTitle = document.getElementById('permissionModalTitle');
        const permissionModalContent = document.getElementById('permissionModalContent');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        let deleteForm = null;

        function openModal(modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'items-center', 'justify-center');
        }

        function closeModal(modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex', 'items-center', 'justify-center');
            // Clear form content on close for the combined modal
            if (modal.id === 'createPermissionModal') {
                // Reset form to its initial create state
                permissionModalContent.innerHTML = `@include('permissions.partials.form', ['permission' => null])`;
            }
        }

        document.querySelectorAll('.close-modal').forEach(button => {
            button.addEventListener('click', function() {
                closeModal(permissionModal);
                closeModal(deleteConfirmationModal);
            });
        });

        permissionModal.addEventListener('click', function(event) {
            if (event.target === permissionModal) {
                closeModal(permissionModal);
            }
        });

        deleteConfirmationModal.addEventListener('click', function(event) {
            if (event.target === deleteConfirmationModal) {
                closeModal(deleteConfirmationModal);
            }
        });

        if (createPermissionBtn) {
            createPermissionBtn.addEventListener('click', function () {
                permissionModalTitle.textContent = 'Create New Permission';
                // Ensure the form is reset to create state
                permissionModalContent.innerHTML = `@include('permissions.partials.form', ['permission' => null])`;
                openModal(permissionModal);
            });
        }

        // Delegated event listeners for edit and delete buttons
        document.getElementById('permission-search-results').addEventListener('click', function (event) {
            // Edit button
            if (event.target.closest('.permission-edit-btn')) {
                const permissionId = event.target.closest('.permission-edit-btn').dataset.id;
                permissionModalTitle.textContent = 'Edit Permission';

                // Load the edit form partial via AJAX
                fetch(`/permissions/${permissionId}/edit`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text(); // Expecting HTML partial
                    })
                    .then(html => {
                        permissionModalContent.innerHTML = html;
                        openModal(permissionModal);
                    })
                    .catch(error => {
                        console.error('Error fetching permission for edit:', error);
                        toastr.error('Failed to load permission for editing.');
                    });
            }

            // Delete button
            if (event.target.closest('.permission-delete-btn')) {
                deleteForm = event.target.closest('form');
                openModal(deleteConfirmationModal);
            }
        });

        // Confirm Delete Action
        confirmDeleteBtn.addEventListener('click', function () {
            if (deleteForm) {
                // Use the ListHandler's handleDelete for consistency
                permissionListHandler.handleDelete({ currentTarget: deleteForm.querySelector('button[type="button"]') });
            }
            closeModal(deleteConfirmationModal);
        });

        // Cancel Delete Action
        cancelDeleteBtn.addEventListener('click', 'click', function () {
            closeModal(deleteConfirmationModal);
        });

        // Override ListHandler's success callback for form submissions to refresh the list
        // This is a workaround since ListHandler expects a full page reload or a direct list update
        // and we're using custom modal handling.
        // For a more robust solution, you'd integrate ListHandler's modal logic directly
        // or ensure its callbacks are flexible enough.
        const originalHandleFormSubmit = permissionListHandler.handleFormSubmit;
        permissionListHandler.handleFormSubmit = async function(e, options) {
            try {
                const response = await originalHandleFormSubmit.call(this, e, options);
                closeModal(permissionModal); // Close the modal on success
                // Manually trigger a list refresh after successful submission
                await this.refreshList();
                return response;
            } catch (error) {
                // Error handling is already in originalHandleFormSubmit
                throw error;
            }
        };

        // Initialize event listeners for search and pagination via ListHandler
        permissionListHandler.setupEventListeners();
    });
</script>
@endpush

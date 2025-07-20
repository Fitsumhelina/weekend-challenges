@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">User Management</h1>
            @can('create user')
                <button id="createUserBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                    Add New User
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
        <form id="user-search-form" action="{{ route('users.index') }}" method="GET" class="mb-6">
            <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <input type="text" name="search" id="user-search" placeholder="Search users..."
                       value="{{ request('search') }}"
                       class="flex-grow w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                <select name="per_page" id="user-per_page" class="px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                    <option value="10" @if(request('per_page', 10) == 10) selected @endif>10 per page</option>
                    <option value="25" @if(request('per_page') == 25) selected @endif>25 per page</option>
                    <option value="50" @if(request('per_page') == 50) selected @endif>50 per page</option>
                </select>
                <button type="submit" class="bg-gray-700 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-300 ease-in-out w-full sm:w-auto">
                    Search
                </button>
            </div>
        </form>

        {{-- User List (result.blade.php) --}}
        <div id="user-search-results">
            @include('users.result', ['users' => $users])
        </div>
    </div>

    {{-- Create/Edit User Modal --}}
    <div id="createUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-2xl leading-6 font-medium text-gray-900" id="userModalTitle"></h3>
                <button class="text-gray-400 hover:text-gray-600 close-modal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="mt-2 px-7 py-3" id="userModalContent">
                {{-- Form content will be loaded here via AJAX for edit, or directly for create --}}
                @include('users.partials.form', ['user' => null, 'roles' => $roles])
            </div>
        </div>
    </div>

    {{-- View User Modal --}}
    <div id="viewUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-2xl leading-6 font-medium text-gray-900">User Details</h3>
                <button class="text-gray-400 hover:text-gray-600 close-modal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="mt-2 px-7 py-3" id="viewUserContent">
                {{-- Content will be loaded here via AJAX --}}
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
                <p class="text-gray-700">Are you sure you want to delete this user?</p>
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
        UserIndexRoute: "{{ route('users.index') }}",
        csrfToken: "{{ csrf_token() }}",
    };
</script>

{{-- Import the ListHandler and UserListHandler --}}
<script type="module">
    import UserListHandler from "{{ asset('js/user.js') }}"; // Adjust path as needed

    $(document).ready(function() {
        // Initialize the UserListHandler
        const userListHandler = new UserListHandler({
            indexRoute: Data.UserIndexRoute,
            csrfToken: Data.csrfToken,
            entityName: 'user',
            routeName: '/users', // Matches your web.php route prefix
            modalAddFormId: 'createUserModal',
            modalEditFormId: 'createUserModal', // Re-use for edit
            modalViewFormId: 'viewUserModal'
        });

        // Manual modal control for TailwindCSS
        const userModal = document.getElementById('createUserModal');
        const viewUserModal = document.getElementById('viewUserModal');
        const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
        const createUserBtn = document.getElementById('createUserBtn');
        const userModalTitle = document.getElementById('userModalTitle');
        const userModalContent = document.getElementById('userModalContent');
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
            if (modal.id === 'createUserModal') {
                // Reset form to its initial create state, passing roles
                // Note: $roles needs to be available in the initial index.blade.php render
                userModalContent.innerHTML = `{!! addslashes(view('users.partials.form', ['user' => null, 'roles' => $roles])->render()) !!}`;
            }
        }

        document.querySelectorAll('.close-modal').forEach(button => {
            button.addEventListener('click', function() {
                closeModal(userModal);
                closeModal(viewUserModal);
                closeModal(deleteConfirmationModal);
            });
        });

        userModal.addEventListener('click', function(event) {
            if (event.target === userModal) {
                closeModal(userModal);
            }
        });

        viewUserModal.addEventListener('click', function(event) {
            if (event.target === viewUserModal) {
                closeModal(viewUserModal);
            }
        });

        deleteConfirmationModal.addEventListener('click', function(event) {
            if (event.target === deleteConfirmationModal) {
                closeModal(deleteConfirmationModal);
            }
        });

        if (createUserBtn) {
            createUserBtn.addEventListener('click', function () {
                userModalTitle.textContent = 'Create New User';
                // Ensure the form is reset to create state, passing roles
                userModalContent.innerHTML = `{!! addslashes(view('users.partials.form', ['user' => null, 'roles' => $roles])->render()) !!}`;
                openModal(userModal);
            });
        }

        // Delegated event listeners for edit, view, and delete buttons
        document.getElementById('user-search-results').addEventListener('click', function (event) {
            // Edit button
            if (event.target.closest('.user-edit-btn')) {
                const userId = event.target.closest('.user-edit-btn').dataset.id;
                userModalTitle.textContent = 'Edit User';

                // Load the edit form partial via AJAX
                fetch(`/users/${userId}/edit`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text(); // Expecting HTML partial
                    })
                    .then(html => {
                        userModalContent.innerHTML = html;
                        openModal(userModal);
                    })
                    .catch(error => {
                        console.error('Error fetching user for edit:', error);
                        toastr.error('Failed to load user for editing.');
                    });
            }

            // View button
            if (event.target.closest('.user-view-btn')) {
                const userId = event.target.closest('.user-view-btn').dataset.id;
                fetch(`/users/${userId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text(); // Fetch HTML partial
                    })
                    .then(html => {
                        document.getElementById('viewUserContent').innerHTML = html;
                        openModal(viewUserModal);
                    })
                    .catch(error => console.error('Error fetching user for view:', error));
            }

            // Delete button
            if (event.target.closest('.user-delete-btn')) {
                deleteForm = event.target.closest('form');
                openModal(deleteConfirmationModal);
            }
        });

        // Confirm Delete Action
        confirmDeleteBtn.addEventListener('click', function () {
            if (deleteForm) {
                // Use the ListHandler's handleDelete for consistency
                userListHandler.handleDelete({ currentTarget: deleteForm.querySelector('button[type="button"]') });
            }
            closeModal(deleteConfirmationModal);
        });

        // Cancel Delete Action
        cancelDeleteBtn.addEventListener('click', function () {
            closeModal(deleteConfirmationModal);
        });

        // Override ListHandler's success callback for form submissions to refresh the list
        const originalHandleFormSubmit = userListHandler.handleFormSubmit;
        userListHandler.handleFormSubmit = async function(e, options) {
            try {
                const response = await originalHandleFormSubmit.call(this, e, options);
                closeModal(userModal); // Close the modal on success
                await this.refreshList(); // Manually trigger a list refresh
                return response;
            } catch (error) {
                throw error;
            }
        };

        // Initialize event listeners for search and pagination via ListHandler
        userListHandler.setupEventListeners();
    });
</script>
@endpush

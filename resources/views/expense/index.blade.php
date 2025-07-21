@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Expense Dashboard</h1>
            @can('create expense')
                <button id="createExpenseBtn" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out">
                    Add New Expense
                </button>
            @endcan
        </div>

        {{-- Success/Error Alert Messages (Keep these as they are handled by Laravel sessions) --}}
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

     {{-- Search Form and Items per page --}}
        <form action="{{ route('expense.index') }}" method="GET" class="mb-6" id="expense-search-form">
            <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <input type="text" name="search" placeholder="Search expenses..."
                       value="{{ request('search') }}"
                       class="flex-grow w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">

                <select name="per_page"
                        class="w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
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

        {{-- expense List (results.blade.php) --}}
        <div id="expense-list-container">
            @include('expense.result', ['expenses' => $expenses])
        </div>
    </div>

    {{-- Create/Edit expense Modal --}}
    <div id="expenseFormModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-2xl leading-6 font-medium text-gray-900" id="expenseFormModalTitle"></h3>
                <button class="text-gray-400 hover:text-gray-600 close-modal" data-modal-id="expenseFormModal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="mt-2 px-7 py-3" id="expenseFormModalContent">
            </div>
        </div>
    </div>

    {{-- View expense Modal --}}
    <div id="viewExpenseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-2xl leading-6 font-medium text-gray-900">Expense Details</h3>
                <button class="text-gray-400 hover:text-gray-600 close-modal" data-modal-id="viewExpenseModal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="mt-2 px-7 py-3" id="viewExpenseContent">
                {{-- Content will be loaded here via AJAX --}}
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div class="modal hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full items-center justify-center z-50" id="deleteConfirmationModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-xl leading-6 font-medium text-gray-900">Confirm Deletion</h3>
                <button class="text-gray-400 hover:text-gray-600 close-modal" data-modal-id="deleteConfirmationModal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="mt-2 px-7 py-3">
                <p class="text-gray-700">Are you sure you want to delete this expense record?</p>
            </div>
            <div class="items-center px-4 py-3 flex justify-end space-x-4">
                <button id="cancelDeleteBtn" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition duration-300">Cancel</button>
                <button id="confirmDeleteBtn" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-300">Delete</button>
            </div>
        </div>
    </div>

</div>
@endsection


@section('scripts')
    <script>
        const AppData = {
            ExpenseIndexRoute: "{{ route('expense.index') }}",
            ExpenseCreateRoute: "{{ route('expense.create') }}",
            ExpenseShowRoute: "{{ route('expense.show', ':id') }}", // Placeholder for ID
            ExpenseEditRoute: "{{ route('expense.edit', ':id') }}", // Placeholder for ID
            ExpenseStoreRoute: "{{ route('expense.store') }}",
            ExpenseUpdateRoute: "{{ route('expense.update', ':id') }}", // Placeholder for ID
            ExpenseDestroyRoute: "{{ route('expense.destroy', ':id') }}", // Placeholder for ID
            csrfToken: "{{ csrf_token() }}"
        };

        // These functions are now global and can be called from imported modules
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

        window.initSelect2ForCategory = function(selectElement, placeholderText = "Select a category") {
            if (typeof jQuery !== 'undefined' && $.fn.select2) {
                if (!$(selectElement).data('select2')) {
                    $(selectElement).select2({
                        placeholder: placeholderText,
                        allowClear: true,
                        dropdownParent: $(selectElement).closest('.modal') // Important for z-index issues in modals
                    });
                }
            } else {
                console.warn("jQuery or Select2 not loaded. Cannot initialize Select2.");
            }
        };

        document.addEventListener('DOMContentLoaded', function () {
            const expenseListContainer = document.getElementById('expense-list-container');
            const expenseFormModal = document.getElementById('expenseFormModal');
            const expenseFormModalContent = document.getElementById('expenseFormModalContent');
            const expenseFormModalTitle = document.getElementById('expenseFormModalTitle');
            const viewExpenseModal = document.getElementById('viewExpenseModal');
            const viewExpenseContent = document.getElementById('viewExpenseContent');
            const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

            let expenseToDeleteId = null; // Variable to store the ID of the expense to be deleted

            // Function to load expense list via AJAX
            function loadExpenseList(url = AppData.ExpenseIndexRoute, append = false) {
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    if (append) {
                        expenseListContainer.insertAdjacentHTML('beforeend', html);
                    } else {
                        expenseListContainer.innerHTML = html;
                    }
                    attachEventListeners(); // Re-attach event listeners after content update
                })
                .catch(error => console.error('Error loading expense list:', error));
            }

            // Function to attach all dynamic event listeners
            function attachEventListeners() {
                // Search form submission
                const searchForm = document.getElementById('expense-search-form');
                if (searchForm) {
                    searchForm.removeEventListener('submit', handleSearchSubmit); // Prevent duplicate listeners
                    searchForm.addEventListener('submit', handleSearchSubmit);
                    // Add listener for per_page select change
                    const perPageSelect = searchForm.querySelector('select[name="per_page"]');
                    if (perPageSelect) {
                        perPageSelect.removeEventListener('change', handleSearchSubmit);
                        perPageSelect.addEventListener('change', handleSearchSubmit);
                    }
                }

                // Create Expense Button
                const createExpenseBtn = document.getElementById('createExpenseBtn');
                if (createExpenseBtn) {
                    createExpenseBtn.removeEventListener('click', handleCreateExpense);
                    createExpenseBtn.addEventListener('click', handleCreateExpense);
                }

                // View Expense Buttons
                document.querySelectorAll('.view-expense-btn').forEach(button => {
                    button.removeEventListener('click', handleViewExpense);
                    button.addEventListener('click', handleViewExpense);
                });

                // Edit Expense Buttons
                document.querySelectorAll('.edit-expense-btn').forEach(button => {
                    button.removeEventListener('click', handleEditExpense);
                    button.addEventListener('click', handleEditExpense);
                });

                // Delete Expense Buttons
                document.querySelectorAll('.delete-expense-btn').forEach(button => {
                    button.removeEventListener('click', handleDeleteExpensePrompt);
                    button.addEventListener('click', handleDeleteExpensePrompt);
                });

                // Pagination Links (AJAX)
                document.querySelectorAll('#expense-list-container .pagination a').forEach(link => {
                    link.removeEventListener('click', handlePaginationClick);
                    link.addEventListener('click', handlePaginationClick);
                });

                // Close modal buttons
                document.querySelectorAll('.close-modal').forEach(button => {
                    button.removeEventListener('click', handleCloseModal);
                    button.addEventListener('click', handleCloseModal);
                });
            }

            function handleSearchSubmit(event) {
                event.preventDefault();
                const form = event.target.tagName === 'SELECT' ? event.target.form : event.target;
                const formData = new FormData(form);
                const queryString = new URLSearchParams(formData).toString();
                loadExpenseList(`${AppData.ExpenseIndexRoute}?${queryString}`);
            }

            function handleCreateExpense() {
                expenseFormModalTitle.textContent = 'Create New Expense';
                fetch(AppData.ExpenseCreateRoute, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    expenseFormModalContent.innerHTML = html;
                    window.openModal(expenseFormModal);
                    // Initialize Select2 after content is loaded
                    const categorySelect = expenseFormModalContent.querySelector('#category');
                    if (categorySelect) {
                        window.initSelect2ForCategory(categorySelect, "Select a category");
                    }
                    attachFormSubmissionListener();
                })
                .catch(error => console.error('Error loading create form:', error));
            }

            function handleViewExpense(event) {
                const expenseId = event.currentTarget.dataset.id;
                const url = AppData.ExpenseShowRoute.replace(':id', expenseId);
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    viewExpenseContent.innerHTML = html;
                    window.openModal(viewExpenseModal);
                })
                .catch(error => console.error('Error loading view details:', error));
            }

            function handleEditExpense(event) {
                const expenseId = event.currentTarget.dataset.id;
                expenseFormModalTitle.textContent = 'Edit Expense';
                const url = AppData.ExpenseEditRoute.replace(':id', expenseId);
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    expenseFormModalContent.innerHTML = html;
                    window.openModal(expenseFormModal);
                    // Initialize Select2 after content is loaded
                    const categorySelect = expenseFormModalContent.querySelector('#category');
                    if (categorySelect) {
                        window.initSelect2ForCategory(categorySelect, "Select a category");
                    }
                    attachFormSubmissionListener();
                })
                .catch(error => console.error('Error loading edit form:', error));
            }

            function handleDeleteExpensePrompt(event) {
                expenseToDeleteId = event.currentTarget.dataset.id;
                window.openModal(deleteConfirmationModal);
            }

            function handlePaginationClick(event) {
                event.preventDefault();
                const url = event.currentTarget.href;
                loadExpenseList(url);
            }

            function handleCloseModal(event) {
                const modalId = event.currentTarget.dataset.modalId;
                const modalElement = document.getElementById(modalId);
                if (modalElement) {
                    window.closeModal(modalElement);
                    // Clear form content if it's the expenseFormModal
                    if (modalId === 'expenseFormModal') {
                        expenseFormModalContent.innerHTML = '';
                    }
                }
            }

            function attachFormSubmissionListener() {
                const form = expenseFormModalContent.querySelector('form');
                if (form) {
                    form.removeEventListener('submit', handleFormSubmit); // Prevent multiple listeners
                    form.addEventListener('submit', handleFormSubmit);
                }
            }

            function handleFormSubmit(event) {
                event.preventDefault();
                const form = event.target;
                const formData = new FormData(form);
                const method = form.querySelector('input[name="_method"]') ? form.querySelector('input[name="_method"]').value : form.method;
                let actionUrl = form.action;

                // For update, replace :id with actual id
                if (method === 'PUT') {
                    const expenseId = form.querySelector('#expense_id').value;
                    actionUrl = AppData.ExpenseUpdateRoute.replace(':id', expenseId);
                }

                // Clear previous errors
                form.querySelectorAll('.text-red-500.text-xs.italic').forEach(errorSpan => {
                    errorSpan.textContent = '';
                });

                fetch(actionUrl, {
                    method: 'POST', // Always POST for Laravel with _method spoofing
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': AppData.csrfToken
                    },
                    body: formData
                })
                .then(response => {
                    if (response.ok) {
                        return response.json(); // Assuming JSON response for success/redirect
                    }
                    // Handle validation errors specifically
                    if (response.status === 422) {
                        return response.json().then(data => {
                            throw { status: 422, errors: data.errors };
                        });
                    }
                    throw new Error('Network response was not ok.');
                })
                .then(data => {
                    // Assuming success or redirect behavior
                    if (data.success) {
                        window.closeModal(expenseFormModal);
                        loadExpenseList(); // Refresh the list
                        // Show success message (can be adapted to display in a dedicated alert area)
                        alert(data.success);
                    } else if (data.redirect) {
                        window.location.href = data.redirect; // Or handle as needed
                    }
                })
                .catch(error => {
                    console.error('Form submission error:', error);
                    if (error.status === 422 && error.errors) {
                        // Display validation errors
                        for (const field in error.errors) {
                            const errorSpan = document.getElementById(`${field}-error`);
                            if (errorSpan) {
                                errorSpan.textContent = error.errors[field][0];
                            }
                        }
                    } else {
                        alert('An error occurred during submission. Please try again.');
                    }
                });
            }

            confirmDeleteBtn.addEventListener('click', function() {
                if (expenseToDeleteId) {
                    const url = AppData.ExpenseDestroyRoute.replace(':id', expenseToDeleteId);
                    fetch(url, {
                        method: 'POST', // Use POST for Laravel delete method spoofing
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': AppData.csrfToken,
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: '_method=DELETE' // Spoof DELETE method
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.json(); // Assuming JSON response for success/redirect
                        }
                        throw new Error('Network response was not ok.');
                    })
                    .then(data => {
                        if (data.success) {
                            window.closeModal(deleteConfirmationModal);
                            loadExpenseList(); // Refresh the list
                            alert(data.success);
                            expenseToDeleteId = null; // Reset
                        } else if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                    })
                    .catch(error => {
                        console.error('Delete error:', error);
                        alert('Error deleting record. Please try again.');
                    });
                }
            });

            cancelDeleteBtn.addEventListener('click', function() {
                window.closeModal(deleteConfirmationModal);
                expenseToDeleteId = null; // Reset
            });


            // Initial load and attach listeners
            attachEventListeners();
        });
    </script>
@endsection

@push('scripts')
@endpush
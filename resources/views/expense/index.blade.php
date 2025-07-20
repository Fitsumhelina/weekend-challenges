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
        <form action="{{ route('expense.index') }}" method="GET" class="mb-6">
            <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <input type="text" name="search" placeholder="Search expenses..."
                       value="{{ request('search') }}"
                       class="flex-grow w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">
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
    <div id="expenseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-2xl leading-6 font-medium text-gray-900" id="modalTitle"></h3>
                <button class="text-gray-400 hover:text-gray-600 close-modal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="mt-2 px-7 py-3">
                @include('expense.partials.form')
            </div>
        </div>
    </div>

    {{-- View expense Modal --}}
    <div id="viewexpenseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-2xl leading-6 font-medium text-gray-900">expense Details</h3>
                <button class="text-gray-400 hover:text-gray-600 close-modal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="mt-2 px-7 py-3" id="viewexpenseContent">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const expenseModal = document.getElementById('expenseModal');
        const viewexpenseModal = document.getElementById('viewexpenseModal');
        const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
        const createexpenseBtn = document.getElementById('createexpenseBtn');
        const modalTitle = document.getElementById('modalTitle');
        const expenseForm = document.getElementById('expenseForm');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        let deleteForm = null; // To store the form for deletion

        // Function to open modal
        function openModal(modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'items-center', 'justify-center');
        }

        // Function to close modal
        function closeModal(modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex', 'items-center', 'justify-center');
        }

        // Close modals when clicking outside or on close buttons
        document.querySelectorAll('.close-modal').forEach(button => {
            button.addEventListener('click', function() {
                closeModal(expenseModal);
                closeModal(viewexpenseModal);
                closeModal(deleteConfirmationModal);
            });
        });

        expenseModal.addEventListener('click', function(event) {
            if (event.target === expenseModal) {
                closeModal(expenseModal);
            }
        });

        viewexpenseModal.addEventListener('click', function(event) {
            if (event.target === viewexpenseModal) {
                closeModal(viewexpenseModal);
            }
        });

        deleteConfirmationModal.addEventListener('click', function(event) {
            if (event.target === deleteConfirmationModal) {
                closeModal(deleteConfirmationModal);
            }
        });

        // Create expense Button
        if (createexpenseBtn) {
            createexpenseBtn.addEventListener('click', function () {
                modalTitle.textContent = 'Create New expense';
                expenseForm.action = "{{ route('expense.store') }}";
                expenseForm.querySelector('input[name="_method"]').value = 'POST';
                expenseForm.reset(); // Clear form fields
                document.getElementById('expense_id').value = ''; // Clear ID for create
                openModal(expenseModal);
            });
        }

        // Edit expense Button (Delegated event listener)
        document.getElementById('expense-list-container').addEventListener('click', function (event) {
            if (event.target.classList.contains('edit-expense-btn')) {
                const expenseId = event.target.dataset.id;
                modalTitle.textContent = 'Edit expense';
                expenseForm.action = `/expense/${expenseId}`; // Laravel route will handle PUT
                expenseForm.querySelector('input[name="_method"]').value = 'PUT';

                fetch(`/expense/${expenseId}/edit`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('title').value = data.title;
                        document.getElementById('amount').value = data.amount;
                        document.getElementById('source').value = data.source;
                        document.getElementById('description').value = data.description;
                        document.getElementById('date').value = data.date;
                        document.getElementById('expense_id').value = data.id; // Set ID for update
                        openModal(expenseModal);
                    })
                    .catch(error => console.error('Error fetching expense for edit:', error));
            }

            // View expense Button (Delegated event listener)
            if (event.target.classList.contains('view-expense-btn')) {
                const expenseId = event.target.dataset.id;
                fetch(`/expense/${expenseId}`)
                    .then(response => response.text()) // Fetch HTML partial
                    .then(html => {
                        document.getElementById('viewexpenseContent').innerHTML = html;
                        openModal(viewexpenseModal);
                    })
                    .catch(error => console.error('Error fetching expense for view:', error));
            }

            // Delete expense Button (Delegated event listener)
            if (event.target.classList.contains('delete-expense-btn')) {
                deleteForm = event.target.closest('form');
                openModal(deleteConfirmationModal);
            }
        });

        // Confirm Delete Action
        confirmDeleteBtn.addEventListener('click', function () {
            if (deleteForm) {
                deleteForm.submit();
            }
            closeModal(deleteConfirmationModal);
        });

        // Cancel Delete Action
        cancelDeleteBtn.addEventListener('click', function () {
            closeModal(deleteConfirmationModal);
        });
    });
</script>
@endpush

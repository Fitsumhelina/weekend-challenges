@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Income Dashboard</h1>
            @can('create income')
            {{-- Add a specific ID for the create button that ListHandler can target --}}
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out" id="createIncomeBtn">
                    Add New Income
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
        <form action="{{ route('income.index') }}" method="GET" class="mb-6" id="income-search-form">
            <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <input type="text" name="search" placeholder="Search incomes..."
                       value="{{ request('search') }}"
                       class="flex-grow w-full sm:w-auto px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 shadow-sm">

                <select name="per_page" onchange="this.form.submit()"
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

        {{-- Income List (results.blade.php) --}}
        <div id="income-list-container">
            @include('income.result', ['incomes' => $incomes])
        </div>
    </div>

    {{-- Create/Edit Income Modal Container --}}
    {{-- This modal will dynamically load content for both create and edit forms --}}
    <div id="incomeFormModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 items-center justify-center">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-2xl leading-6 font-medium text-gray-900" id="incomeFormModalTitle"></h3>
                <button class="text-gray-400 hover:text-gray-600 close-modal" data-modal-id="incomeFormModal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            {{-- This div will be populated with the form.blade.php content via AJAX --}}
            <div class="mt-2 px-7 py-3" id="incomeFormModalContent">
                {{-- Initial content can be empty or a loading spinner --}}
            </div>
        </div>
    </div>

    {{-- View Income Modal --}}
    <div id="viewIncomeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 items-center justify-center">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-2xl leading-6 font-medium text-gray-900">Income Details</h3>
                <button class="text-gray-400 hover:text-gray-600 close-modal" data-modal-id="viewIncomeModal">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <div class="mt-2 px-7 py-3" id="viewIncomeContent">
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
                <p class="text-gray-700">Are you sure you want to delete this income record?</p>
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
            IncomeIndexRoute: "{{ route('income.index') }}",
            IncomeCreateRoute: "{{ route('income.create') }}",
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

        // This function needs to be globally accessible for income.js
        window.initSelect2ForSource = function(selectElement, placeholderText = "Select a source") {
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
    </script>
@endsection

@push('scripts')
@endpush

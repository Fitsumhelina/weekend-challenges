@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Expenses</h1>
            @can('create expense')
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105"
                    data-bs-toggle="modal" data-bs-target="#createExpenseModal"
                >
                    <i class="fas fa-plus mr-2"></i> Add New Expense
                </button>
            @endcan
        </div>

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.remove()">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">{{ session('error') }}</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" onclick="this.parentElement.parentElement.remove()">
                        <title>Close</title>
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </span>
            </div>
        @endif

        {{-- Search and Filter --}}
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <form id="expense-search-form" class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <div class="relative flex-grow w-full sm:w-auto">
                    <input type="text" name="search" id="expense-search" placeholder="Search expenses..."
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="w-full sm:w-auto">
                    <select name="per_page" id="expense-per_page" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="10">10 Per Page</option>
                        <option value="25">25 Per Page</option>
                        <option value="50">50 Per Page</option>
                        <option value="100">100 Per Page</option>
                    </select>
                </div>
            </form>
        </div>

        {{-- Expenses List --}}
        <div id="expense-search-results" class="bg-white rounded-lg shadow-md overflow-hidden">
            @include('expense.result', ['expenses' => $expenses])
        </div>
    </div>

    {{-- Create Expense Modal --}}
    <div class="modal fade" id="createExpenseModal" tabindex="-1" aria-labelledby="createExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-lg shadow-xl">
                {{-- Content will be loaded via AJAX --}}
            </div>
        </div>
    </div>

    {{-- Edit Expense Modal --}}
    <div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-lg shadow-xl">
                {{-- Content will be loaded via AJAX --}}
            </div>
        </div>
    </div>

    {{-- View Expense Modal --}}
    <div class="modal fade" id="viewExpenseModal" tabindex="-1" aria-labelledby="viewExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-lg shadow-xl">
                {{-- Content will be loaded via AJAX --}}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Data object to pass to ListHandler
        const Data = {
            ExpenseIndexRoute: "{{ route('expense.index') }}",
            csrfToken: "{{ csrf_token() }}",
        };
    </script>
    {{-- Assuming ListHandler.js and ExpenseListHandler.js are available globally or via module bundler --}}
    <script type="module">
        import ListHandler from "{{ asset('js/base/ListHandler.js') }}"; // Adjust path as needed
        import ExpenseListHandler from "{{ asset('js/expense.js') }}"; // Adjust path as needed

        $(document).ready(function() {
            window.expenseListHandler = new ExpenseListHandler({
                indexRoute: Data.ExpenseIndexRoute,
                csrfToken: Data.csrfToken,
                entityName: 'expense',
                routeName: '/expense', // This should match your web.php route prefix
                modalAddFormId: 'createExpenseModal',
                modalEditFormId: 'editExpenseModal',
                modalViewFormId: 'viewExpenseModal'
            });

            // Optional: If you need to refresh the page after a successful form submission
            // window.expenseListHandler.isRefreshAfterSubmit = true;
            // Optional: If your forms handle file uploads
            // window.expenseListHandler.hasFile = true;

            // Initial load of results
            window.expenseListHandler.refreshList();
        });
    </script>
@endpush

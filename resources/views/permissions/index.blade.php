@extends('layouts.app') {{-- Assuming your main layout is in resources/views/layouts/app.blade.php --}}

@section('styles')
    {{-- Add any specific styles here if needed --}}
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-dark">Permissions Management</h4>
                        {{-- Button to open the create permission modal --}}
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#permissionCreateModal">
                            <i class="mdi mdi-plus-circle-outline me-1"></i> Add New Permission
                        </button>
                    </div>
                    <div class="card-body border-bottom">
                        <form method="GET" id="permission-search-form" class="mb-3">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-5 col-lg-4">
                                    <input type="text" id="permission-search" name="search" class="form-control" placeholder="Search permission..." value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3 col-lg-2">
                                    <select name="per_page" id="permission-per_page" class="form-select">
                                        <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5 per page</option>
                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 per page</option>
                                        <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20 per page</option>
                                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50 per page</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-lg-2">
                                    <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary w-100">
                                        <i class="mdi mdi-filter-off me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- This div will be updated with AJAX results by PermissionListHandler --}}
                    <div id="permission-search-results">
                        {{-- Initial load of permission list, will be replaced by AJAX on search/pagination --}}
                        @include('user.permissions.result', ['permissions' => $permissions])
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Create Permission Modal --}}
    <div class="modal fade" id="permissionCreateModal" tabindex="-1" aria-labelledby="permissionCreateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {{-- Content for create form will be included directly as it's simple --}}
                @include('user.permissions.partials.form', ['permission' => null])
            </div>
        </div>
    </div>

    {{-- Edit Permission Modal --}}
    <div class="modal fade" id="permissionEditModal" tabindex="-1" aria-labelledby="permissionEditModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                {{-- Content for edit form will be loaded dynamically via AJAX by PermissionListHandler --}}
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- Pass necessary data to your JavaScript --}}
    <script>
        const AppData = {
            PermissionsIndexRoute: "{{ route('permissions.index') }}",
            csrfToken: "{{ csrf_token() }}"
        };
    </script>
    {{-- Ensure your app.js (which initializes PermissionListHandler) is loaded after AppData --}}
    @vite(['resources/js/app.js'])
@endsection
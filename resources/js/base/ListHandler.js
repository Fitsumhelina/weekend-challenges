// resources/js/base/ListHandler.js (Modified)
// Base list handler for all structure components
export default class ListHandler {
    constructor(options) {
        this.indexRoute = options.indexRoute;
        this.csrfToken = options.csrfToken;
        this.entityName = options.entityName; // e.g., 'office', 'district', 'branch'
        this.routeName = options.routeName; // e.g., '/location/offices', '/location/districts', 'location/branches'
        this.modalAddFormId = options.modalAddFormId; // e.g., 'createOfficeModal', 'createDistrictModal', 'createBranchModal'
        this.modalEditFormId = options.modalEditFormId; // e.g., 'editOfficeModal', 'editDistrictModal', 'editBranchModal'
        this.modalViewFormId = options.modalViewFormId; // e.g., 'viewOfficeModal', 'viewDistrictModal', 'viewBranchModal'
        this.initialized = false;
        this.initialize();
    }

    get loadingOverlay() {
        return `
            <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="top: 0; left: 0; background: rgba(255,255,255,0.7); z-index: 1000;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
    }

    initialize() {
        if (this.initialized) return;
        this.setupEventListeners();
        this.initialized = true;
    }

    // New method to be overridden by child classes for post-render initialization
    postFormRender() {
        // This method can be overridden by child classes (e.g., IncomeListHandler)
        // to initialize specific elements like Select2 after the form content
        // has been loaded into the modal.
    }

    setupEventListeners() {
        const namespace = `.${this.entityName}Handler`;
        // Remove all existing event listeners for this handler
        $(document).off(namespace);
        $(`#${this.modalAddFormId}`).off(namespace);
        $(`#${this.modalEditFormId}`).off(namespace);
        $(`#${this.modalViewFormId}`).off(namespace);

        // Handle search with debounce
        $(document).on(
            `keyup${namespace}`,
            `#${this.entityName}-search`,
            this.debounce(() => this.handleSearch(), 300)
        );

        // Handle per page change
        $(document).on(
            `change${namespace}`,
            `#${this.entityName}-per_page`,
            () => this.handlePerPageChange()
        );

        // Handle pagination links
        $(document).on(
            `click${namespace}`,
            '.pagination a',
            (e) => this.handlePagination(e)
        );

        // Handle create form submission (modified to call postFormRender)
        $(`#${this.modalAddFormId}`).on(
            `submit${namespace}`,
            '#incomeForm', // Changed from '#createForm' to '#incomeForm'
            (e) => {
                e.preventDefault();
                e.stopPropagation();
                return this.handleSubmitForm(e); // Assuming a generic submit handler
            }
        );
        // Handle edit form submission (modified to call postFormRender)
        $(`#${this.modalEditFormId}`).on(
            `submit${namespace}`,
            '#incomeForm', // Changed from '#editForm' to '#incomeForm'
            (e) => {
                e.preventDefault();
                e.stopPropagation();
                return this.handleSubmitForm(e); // Assuming a generic submit handler
            }
        );

        // Handle add new button click
        $(document).on(`click${namespace}`, '#createIncomeBtn', (e) => { // Using specific ID
            e.preventDefault();
            this.handleCreate();
        });


        // Handle edit button click
        $(document).on(
            `click${namespace}`,
            `.${this.entityName}-edit-btn`,
            (e) => this.loadEditForm(e)
        );

        // Handle view button click
        $(document).on(
            `click${namespace}`,
            `.${this.entityName}-view-btn`,
            (e) => this.loadViewForm(e)
        );

        // Handle delete button click
        $(document).on(
            `click${namespace}`,
            `.${this.entityName}-delete-btn`,
            (e) => {
                e.preventDefault(); // Prevent default form submission
                this.deleteForm = $(e.currentTarget).closest('form');
                this.openModal('deleteConfirmationModal'); // Use actual ID
            }
        );

        // Handle delete confirmation
        $(document).on(
            `click${namespace}`,
            '#confirmDeleteBtn', // Use actual ID
            () => this.handleDeleteConfirmation()
        );

        // Handle cancel delete
        $(document).on(
            `click${namespace}`,
            '#cancelDeleteBtn', // Use actual ID
            () => this.closeModal('deleteConfirmationModal')
        );

        // Close modal buttons
        $(document).on(`click${namespace}`, '.close-modal', (e) => {
            const modalId = $(e.currentTarget).closest('.fixed').attr('id'); // Get the ID of the parent modal
            this.closeModal(modalId);
        });
    }

    handleSearch() {
        const query = $(`#${this.entityName}-search`).val();
        const perPage = $(`#${this.entityName}-per_page`).val();
        this.fetchList(query, perPage);
    }

    handlePerPageChange() {
        const query = $(`#${this.entityName}-search`).val();
        const perPage = $(`#${this.entityName}-per_page`).val();
        this.fetchList(query, perPage);
    }

    handlePagination(e) {
        e.preventDefault();
        const url = $(e.currentTarget).attr('href');
        this.fetchList(null, null, url); // Pass url directly
    }

    // Generic form submission handler (for create and edit)
    handleSubmitForm(e) {
        const form = $(e.currentTarget);
        const actionUrl = form.attr('action');
        const method = form.attr('method'); // This will be POST or PUT/DELETE

        // Clear previous errors
        form.find('.text-red-500').text('');

        // Show loading overlay (optional, depends on your UI)
        // $(`#${this.modalAddFormId} .modal-content, #${this.modalEditFormId} .modal-content`).append(this.loadingOverlay);

        $.ajax({
            url: actionUrl,
            type: method,
            data: form.serialize(),
            headers: {
                'X-CSRF-TOKEN': this.csrfToken
            },
            success: (response) => {
                toastr.success(response.message || `${this.entityName} saved successfully!`);
                this.closeModal(this.modalAddFormId); // Close the relevant modal
                this.closeModal(this.modalEditFormId); // Close the relevant modal
                this.fetchList(); // Refresh list
            },
            error: (xhr) => {
                // Remove loading overlay
                // $(`#${this.modalAddFormId} .loading-overlay, #${this.modalEditFormId} .loading-overlay`).remove();
                if (xhr.status === 422) {
                    this.handleFormError(xhr.responseJSON, form);
                } else {
                    this.handleAjaxError(xhr);
                }
            }
        });
    }

    handleCreate() {
        const modalElement = document.getElementById(this.modalAddFormId);
        const formContentArea = modalElement.querySelector('#incomeFormContent'); // Specific ID for income form content
        const modalTitle = modalElement.querySelector('#modalTitle'); // Get the modal title element

        if (modalTitle) {
            modalTitle.textContent = `Add New ${this.entityName.charAt(0).toUpperCase() + this.entityName.slice(1)}`;
        }

        fetch(`/${this.routeName}/create`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                formContentArea.innerHTML = html;
                this.openModal(this.modalAddFormId);
                this.postFormRender(); // Call the post-render hook
            })
            .catch(error => console.error('Error fetching create form:', error));
    }

    loadEditForm(e) {
        const id = $(e.currentTarget).data('id');
        const modalElement = document.getElementById(this.modalEditFormId);
        const formContentArea = modalElement.querySelector('#incomeFormContent'); // Specific ID for income form content
        const modalTitle = modalElement.querySelector('#modalTitle'); // Get the modal title element

        if (modalTitle) {
            modalTitle.textContent = `Edit ${this.entityName.charAt(0).toUpperCase() + this.entityName.slice(1)}`;
        }

        fetch(`/${this.routeName}/${id}/edit`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                formContentArea.innerHTML = html;
                this.openModal(this.modalEditFormId);
                this.postFormRender(); // Call the post-render hook
            })
            .catch(error => console.error('Error fetching edit form:', error));
    }

    loadViewForm(e) {
        const id = $(e.currentTarget).data('id');
        const modalElement = document.getElementById(this.modalViewFormId);
        const viewContentArea = modalElement.querySelector('#viewIncomeContent'); // Specific ID for income view content

        fetch(`/${this.routeName}/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                viewContentArea.innerHTML = html;
                this.openModal(this.modalViewFormId);
                // Call postFormRender if there are specific JS init for view modal, otherwise it might not be needed
                // this.postFormRender();
            })
            .catch(error => console.error('Error fetching view details:', error));
    }

    handleDeleteConfirmation() {
        if (this.deleteForm) {
            // Submit the form for actual deletion
            this.deleteForm.submit();
        }
        this.closeModal('deleteConfirmationModal');
    }

    fetchList(search = null, perPage = null, url = null) {
        let fetchUrl = url || this.indexRoute;
        const params = new URLSearchParams();

        if (search !== null) {
            params.append('search', search);
        } else if ($(`#${this.entityName}-search`).val()) {
            params.append('search', $(`#${this.entityName}-search`).val());
        }

        if (perPage !== null) {
            params.append('per_page', perPage);
        } else if ($(`#${this.entityName}-per_page`).val()) {
            params.append('per_page', $(`#${this.entityName}-per_page`).val());
        }

        const queryString = params.toString();
        if (queryString) {
            fetchUrl = `${fetchUrl.split('?')[0]}?${queryString}`;
        }

        fetch(fetchUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Important for Laravel's isAjax()
                }
            })
            .then(response => response.text())
            .then(html => {
                // Assuming you have a container with this ID in your index.blade.php
                document.getElementById(`${this.entityName}-list-container`).innerHTML = html;
            })
            .catch(error => console.error('Error fetching list:', error));
    }

    openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex'); // Assuming Tailwind 'flex' for display
        }
    }

    closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            // Clear form/content when closing if needed
            const formContentArea = modal.querySelector('#incomeFormContent');
            if (formContentArea) {
                formContentArea.innerHTML = '';
            }
            const viewContentArea = modal.querySelector('#viewIncomeContent');
            if (viewContentArea) {
                viewContentArea.innerHTML = '';
            }
            // Clear validation errors on modal close
            $(modal).find('.text-red-500').text('');
        }
    }

    debounce(func, delay) {
        let timeout;
        return function(...args) {
            const context = this;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), delay);
        };
    }

    handleAjaxError(error) {
        console.error('AJAX Error:', error);
        let errorMessage = 'An error occurred. Please try again.';

        if (error.responseJSON) {
            if (error.responseJSON.message) {
                errorMessage = error.responseJSON.message;
            } else if (error.responseJSON.errors) {
                const errorMessages = Object.values(error.responseJSON.errors)
                    .flat()
                    .filter(message => message);
                if (errorMessages.length > 0) {
                    errorMessage = errorMessages[0];
                }
            }
        }

        window.toastr.error(errorMessage);
    }

    handleFormError(errors, form) { // Renamed parameter from 'error' to 'errors' for clarity
        console.error('Form Errors:', errors);
        // Clear all previous error messages
        form.find('span.text-red-500').text('');

        if (errors && errors.errors) {
            let hasFieldErrors = false;
            for (const field in errors.errors) {
                const errorMessages = errors.errors[field];
                const errorElement = form.find(`#${field}-error`);
                if (errorElement.length) {
                    errorElement.text(errorMessages[0]);
                    hasFieldErrors = true;
                }
            }

            // Show general error message if there are no specific field errors or if a general message is present
            if (!hasFieldErrors && errors.message) {
                window.toastr.error(errors.message);
            }
        } else if (errors.message) {
            window.toastr.error(errors.message);
        } else {
            window.toastr.error('An unknown error occurred during form submission.');
        }
    }

    // Add this property to allow refresh after submit (default: false)
    get isRefreshAfterSubmit() {
        return this._isRefreshAfterSubmit || false;
    }

    set isRefreshAfterSubmit(value) {
        this._isRefreshAfterSubmit = value;
    }

    // Add this property to allow file upload (default: false)
    get hasFile() {
        return this._hasFile || false;
    }

    set hasFile(value) {
        this._hasFile = value;
    }
}
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

        // Handle create form submission
        $(`#${this.modalAddFormId}`).on(
            `submit${namespace}`,
            '#createForm',
            (e) => {
                e.preventDefault();
                e.stopPropagation();
                return this.handleCreate(e);
            }
        );

        // Handle edit form submission
        $(`#${this.modalEditFormId}`).on(
            `submit${namespace}`,
            '#editForm',
            (e) => {
                e.preventDefault();
                e.stopPropagation();
                return this.handleEdit(e);
            }
        );

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
            (e) => this.handleDelete(e)
        );

        // Clear form fields when modal is closed
        const modalSelector = `#${this.modalAddFormId}, #${this.modalEditFormId}, #${this.modalViewFormId}`;
        $(document).on(`hidden.bs.modal${namespace}`, modalSelector, () => {
            const modal = document.getElementById(this.modalAddFormId);
            if (modal) {
                const form = modal.querySelector('form');
                if (form) {
                    try {
                        // Try using form.reset() first
                        form.reset();
                    } catch (e) {
                        // If form.reset() fails, manually reset fields
                        form.querySelectorAll('input, select, textarea').forEach(input => {
                            switch (input.type) {
                                case 'checkbox':
                                case 'radio':
                                    input.checked = false;
                                    break;
                                case 'select-one':
                                case 'select-multiple':
                                    input.selectedIndex = 0;
                                    break;
                                default:
                                    if (input.name === 'year') {
                                        input.value = new Date().getFullYear().toString().slice(-2);
                                    } else if (input.name === 'last_serial') {
                                        input.value = '0';
                                    } else {
                                        input.value = '';
                                    }
                            }
                        });
                    }
                }
            }
            $(modalSelector).find('.error').text('');
        });
    }

    get capitalizedName() {
        return this.entityName.charAt(0).toUpperCase() + this.entityName.slice(1);
    }

    handleSearch() {
        this.performAjaxRequest(
            this.indexRoute,
            'GET',
            $(`#${this.entityName}-search-form`).serialize()
        );
    }

    handlePerPageChange() {
        const perPage = $(`#${this.entityName}-per_page`).val();
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('per_page', perPage);
        currentUrl.searchParams.set('page', '1');
        this.performAjaxRequest(currentUrl.toString(), 'GET');
    }

    handlePagination(e) {
        e.preventDefault();
        const url = $(e.target).attr('href');
        this.performAjaxRequest(url, 'GET');
    }

    async handleFormSubmit(e, options) {
        e.preventDefault();
        const {
            form,
            method,
            successMessage,
            errorMessage,
            modalId,
            hasFile = false
        } = options;

        const submitButton = form.find('button[type="submit"]');
        const buttonState = this.toggleButtonLoading(submitButton);

        try {
            let data;
            let contentType = 'application/x-www-form-urlencoded';
            let processData = true;

            if (hasFile) {
                data = new FormData(form[0]);
                if (method === 'PUT') {
                    data.append('_method', 'PUT');
                }
                contentType = false;
                processData = false;
            } else {
                data = form.serialize();
                if (method === 'PUT') {
                    data += '&_method=PUT';
                }
            }

            const response = await $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: data,
                processData: processData,
                contentType: contentType,
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                cache: false
            });

            $(`#${modalId}`).modal('hide');
            window.toastr.success(successMessage);
            
            if (this.isRefreshAfterSubmit) {
                window.location.reload();
            } else {
                await this.refreshList();
            }
        } catch (error) {
            console.error('Form Submit Error:', error);
            
            // Handle validation errors first
            if (error.responseJSON?.errors) {
                this.handleFormError(error, form);
            } 
            // Then handle general error messages
            else if (error.responseJSON?.message) {
                window.toastr.error(error.responseJSON.message);
            } 
            // Fallback to default error message
            else {
                window.toastr.error(errorMessage || 'An error occurred while processing your request.');
            }
        } finally {
            this.toggleButtonLoading(submitButton, buttonState);
        }
    }

    handleCreate(e) {
        return this.handleFormSubmit(e, {
            form: $(e.currentTarget),
            method: 'POST',
            successMessage: `${this.capitalizedName} created successfully.`,
            errorMessage: `An error occurred while saving the ${this.entityName}.`,
            modalId: this.modalAddFormId,
            hasFile: this.hasFile
        });
    }

    handleEdit(e) {
        return this.handleFormSubmit(e, {
            form: $(e.currentTarget),
            method: 'PUT',
            successMessage: `${this.capitalizedName} updated successfully.`,
            errorMessage: `An error occurred while updating the ${this.entityName}.`,
            modalId: this.modalEditFormId,
            hasFile: this.hasFile
        });
    }

    handleView(e) {
        return this.handleFormSubmit(e, {
            form: $(e.currentTarget),
            method: 'GET',
            successMessage: `${this.capitalizedName} details loaded successfully.`,
            errorMessage: `An error occurred while loading the ${this.entityName} details.`,
            modalId: this.modalViewFormId
        });
    }

    loadEditForm(e) {
        e.preventDefault();
        const id = $(e.currentTarget).data('id');
        if (!id) {
            console.error(`${this.capitalizedName} ID not found`);
            return;
        }

        const editButton = $(e.currentTarget);
        const buttonState = this.toggleButtonLoading(editButton);

        const modalSelector = `#${this.modalEditFormId}`;

        // Ensure routeName starts with a slash and doesn't end with one
        const routeName = this.routeName.startsWith('/') ? this.routeName : `/${this.routeName}`;
        const cleanRouteName = routeName.endsWith('/') ? routeName.slice(0, -1) : routeName;

        $.ajax({
            url: `${cleanRouteName}/${id}/edit`,
            type: 'GET',
            headers: { 'X-CSRF-TOKEN': this.csrfToken },
            success: (response) => {
                $(modalSelector).modal('show');
                $(`${modalSelector} .modal-content`).html(response);
            },
            error: (error) => {
                window.toastr.error(`Failed to load ${this.entityName} details.`);
                console.error(`Error loading ${this.entityName} edit form:`, error);
            },
            complete: () => this.toggleButtonLoading(editButton, buttonState)
        });
    }

    loadViewForm(e) {
        e.preventDefault();
        const id = $(e.currentTarget).data('id');
        if (!id) {
            console.error(`${this.capitalizedName} ID not found`);
            return;
        }

        const viewButton = $(e.currentTarget);
        const buttonState = this.toggleButtonLoading(viewButton);

        const modalSelector = `#${this.modalViewFormId}`;

        // Ensure routeName starts with a slash and doesn't end with one
        const routeName = this.routeName.startsWith('/') ? this.routeName : `/${this.routeName}`;
        const cleanRouteName = routeName.endsWith('/') ? routeName.slice(0, -1) : routeName;

        $.ajax({
            url: `${cleanRouteName}/${id}/show`,
            type: 'GET',
            headers: { 'X-CSRF-TOKEN': this.csrfToken },
            success: (response) => {
                $(modalSelector).modal('show');
                $(`${modalSelector} .modal-content`).html(response);
            },
            error: (error) => {
                window.toastr.error(`Failed to load ${this.entityName} details.`);
                console.error(`Error loading ${this.entityName} view form:`, error);
            },
            complete: () => this.toggleButtonLoading(viewButton, buttonState)
        });
    }

    handleDelete(e) {
        e.preventDefault();
        const form = $(e.currentTarget).closest('form');

        window.Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await this.performAjaxRequest(
                        form.attr('action'),
                        'PATCH',
                        form.serialize() + '&_method=PATCH'
                    );
                    window.toastr.success(response.message);
                    await this.refreshList();
                } catch (error) {
                    window.toastr.error(`Failed to delete ${this.entityName}.`);
                }
            }
        });
    }

    toggleButtonLoading(button, state = null) {
        if (state) {
            return button
                .prop('disabled', false)
                .attr('title', state.title)
                .html(state.html);
        }

        const currentState = {
            html: button.html(),
            title: button.attr('title')
        };

        button
            .prop('disabled', true)
            .attr('title', '')
            .html(`
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                ${button.data('loading-text') || 'Loading...'}
            `);

        return currentState;
    }

    async refreshList() {
        await this.performAjaxRequest(
            this.indexRoute,
            'GET',
            $(`#${this.entityName}-search-form`).serialize()
        );
    }

    debounce(func, delay) {
        let debounceTimer;
        return (...args) => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(this, args), delay);
        };
    }

    async performAjaxRequest(url, type, data = {}) {
        const searchResults = $(`#${this.entityName}-search-results`);
        searchResults.prepend(this.loadingOverlay);

        try {
            const response = await $.ajax({
                url,
                type,
                data,
                headers: { 'X-CSRF-TOKEN': this.csrfToken }
            });
            this.handleAjaxResponse(response);
            return response;
        } catch (error) {
            this.handleAjaxError(error);
            throw error;
        } finally {
            searchResults.find('.position-absolute').remove();
        }
    }

    handleAjaxResponse(response) {
        $(`#${this.entityName}-search-results`).html(response);
    }

    handleAjaxError(error) {
        console.error('Ajax Error:', error);
        let errorMessage = 'An error occurred. Please try again.';

        if (error.responseJSON) {
            // Prioritize user-friendly message if available
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

    handleFormError(error, form) {
        console.error('Form Error:', error);
        let errorMessage = 'An error occurred. Please try again.';

        if (error.responseJSON) {
            // Handle field-specific errors first
            if (error.responseJSON.errors) {
                let hasFieldErrors = false;
                $.each(error.responseJSON.errors, (field, messages) => {
                    const errorElement = form.find(`#${field}-error`);
                    if (errorElement.length) {
                        errorElement.text(messages[0]);
                        hasFieldErrors = true;
                    }
                });
                
                // If we have field errors, don't show the general error message
                if (hasFieldErrors) {
                    return;
                }
            }

            // Show general error message if available
            if (error.responseJSON.message) {
                errorMessage = error.responseJSON.message;
            }
        }

        window.toastr.error(errorMessage);
    }
}

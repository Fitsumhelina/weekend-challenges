// resources/js/base/ListHandler.js (Modified)
export default class ListHandler {
    constructor(options) {
        this.indexRoute = options.indexRoute;
        this.csrfToken = options.csrfToken;
        this.entityName = options.entityName;
        this.routeName = options.routeName;
        this.modalAddFormId = options.modalAddFormId; 
        this.modalEditFormId = options.modalEditFormId; 
        this.modalViewFormId = options.modalViewFormId; 
        this.modalFormContentId = options.modalFormContentId; 
        this.modalFormTitleId = options.modalFormTitleId; 
        this.initialized = false;
        this.currentDeleteForm = null; 

        this.openModal = options.openModal || window.openModal; 
        this.closeModal = options.closeModal || window.closeModal; 

        this.initialize();
    }

    get loadingOverlay() {
        return `
            <div class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-70 z-10">
                <div class="spinner-border text-blue-500" role="status">
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
        // Listener for the "Add New" button
        const createButton = document.getElementById(`create${this.entityName.charAt(0).toUpperCase() + this.entityName.slice(1)}Btn`);
        if (createButton) {
            createButton.addEventListener('click', this.handleCreate.bind(this));
        }

        // Event delegation for edit, view, delete buttons within the list container
        const listContainer = document.getElementById(`${this.entityName}-list-container`);
        if (listContainer) {
            listContainer.addEventListener('click', (event) => {
                const target = event.target.closest('button');
                if (!target) return;
                const id = target.dataset.id;

                // Income buttons
                if (target.classList.contains('edit-income-btn')) {
                    this.loadForm(this.modalEditFormId, `/${this.routeName}/${id}/edit`, 'edit');
                } else if (target.classList.contains('view-income-btn')) {
                    this.loadForm(this.modalViewFormId, `/${this.routeName}/${id}`, 'view');
                } else if (target.classList.contains('delete-income-btn')) {
                    const form = target.closest('form');
                    this.handleDelete(form);
                }
                // Expense buttons
                else if (target.classList.contains('edit-expense-btn')) {
                    this.loadForm(this.modalEditFormId, `/${this.routeName}/${id}/edit`, 'edit');
                } else if (target.classList.contains('view-expense-btn')) {
                    this.loadForm(this.modalViewFormId, `/${this.routeName}/${id}`, 'view');
                } else if (target.classList.contains('delete-expense-btn')) {
                    const form = target.closest('form');
                    this.handleDelete(form);
                }
               
                // Role buttons
                else if (target.classList.contains('edit-role-btn')) {
                    this.loadForm(this.modalEditFormId, `/${this.routeName}/${id}/edit`, 'edit');
                } else if (target.classList.contains('view-role-btn')) {
                    this.loadForm(this.modalViewFormId, `/${this.routeName}/${id}`, 'view');
                } else if (target.classList.contains('delete-role-btn')) {
                    const form = target.closest('form');
                    this.handleDelete(form);
                }

                //user button
                else if (target.classList.contains('edit-user-btn')) {
                    this.loadForm(this.modalEditFormId, `/${this.routeName}/${id}/edit`, 'edit');
                } else if (target.classList.contains('view-user-btn')) {
                    this.loadForm(this.modalViewFormId, `/${this.routeName}/${id}`, 'view');
                } else if (target.classList.contains('delete-user-btn')) {
                    const form = target.closest('form');
                    this.handleDelete(form);
                }
            });
        }


        

        // Search form and per_page select
        const searchForm = document.getElementById(`${this.entityName}-search-form`);
        if (searchForm) {
            searchForm.addEventListener('submit', this.handleSearch.bind(this));
            const perPageSelect = searchForm.querySelector('select[name="per_page"]');
            if (perPageSelect) {
                perPageSelect.addEventListener('change', this.handleSearch.bind(this));
            }
        }

        const formModalContentDiv = document.getElementById(this.modalFormContentId);
        if (formModalContentDiv) {
            formModalContentDiv.addEventListener('submit', (event) => {
                const form = event.target.closest('form');
                if (form) {
                    event.preventDefault(); // Prevent default form submission
                    this.handleFormSubmission(form);
                }
            });
        }

        document.addEventListener('click', (event) => {
            const closeButton = event.target.closest('.close-modal');
            if (closeButton) {
                const modalIdToClose = closeButton.dataset.modalId || closeButton.closest('.fixed.inset-0')?.id;
                if (modalIdToClose) {
                    const modalElement = document.getElementById(modalIdToClose);
                    if (modalElement) {
                        this.closeModal(modalElement);
                    }
                }
            }
        });

        // Delete confirmation buttons
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', () => {
                if (this.currentDeleteForm) {
                    this.currentDeleteForm.submit();
                }
                this.closeModal(document.getElementById('deleteConfirmationModal'));
            });
        }
        if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', () => {
                this.closeModal(document.getElementById('deleteConfirmationModal'));
            });
        }
    }

    loadForm(modalId, url, type) {
        const modalElement = document.getElementById(modalId);
        const modalContentDiv = document.getElementById(this.modalFormContentId); 
        const modalViewContentDiv =
            document.getElementById('viewIncomeContent') ||
            document.getElementById('viewExpenseContent') ||
            document.getElementById('viewUserContent');

        if (!modalElement) {
            console.error(`Modal element with ID '${modalId}' not found.`);
            window.toastr.error('Modal container not found.');
            return;
        }

        // Determine where to inject content and set title
        let targetContentDiv;
        let modalTitleElement;

        if (type === 'create' || type === 'edit') {
            targetContentDiv = modalContentDiv;
            modalTitleElement = document.getElementById(this.modalFormTitleId);
            if (modalTitleElement) {
                modalTitleElement.textContent = type === 'create' ? `Add New ${this.entityName.charAt(0).toUpperCase() + this.entityName.slice(1)}` : `Edit ${this.entityName.charAt(0).toUpperCase() + this.entityName.slice(1)}`;
            }
        } else if (type === 'view') {
            targetContentDiv = modalViewContentDiv;
        }

        if (!targetContentDiv) {
            console.error(`Target content div not found for modal type '${type}'.`);
            window.toastr.error('Modal content area not found.');
            return;
        }

        // Show loading overlay
        targetContentDiv.innerHTML = this.loadingOverlay;
        this.openModal(modalElement); // Open the modal container

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest' // Important for Laravel to detect AJAX request
            }
        })
        .then(response => {
            if (!response.ok) {
                // If the response is not OK, try to parse JSON for errors, otherwise throw generic error
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    return response.json().then(errorData => {
                        throw new Error(errorData.message || 'Server error occurred.');
                    });
                } else {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
            }
            return response.text();
        })
        .then(html => {
            targetContentDiv.innerHTML = html; // Inject the fetched HTML
            // Call postFormRender if it's a create/edit form, allowing child classes to init components
            if ((type === 'create' || type === 'edit') && typeof this.postFormRender === 'function') {
                this.postFormRender();
            }
        })
        .catch(error => {
            console.error(`Error fetching ${type} form:`, error);
            window.toastr.error(`Failed to load ${type} form: ${error.message || 'An unknown error occurred.'}`);
            this.closeModal(modalElement); // Close modal on error
        });
    }

    handleCreate() {
        // Call the generic loadForm method for create
        this.loadForm(this.modalAddFormId, `/${this.routeName}/create`, 'create');
    }

    // loadEditForm and loadViewForm are now handled by the generic loadForm
    // but the event listeners in setupEventListeners will call loadForm directly.
    // So these specific methods are not strictly needed anymore if setupEventListeners is updated.

    handleDelete(form) {
        this.currentDeleteForm = form;
        const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
        this.openModal(deleteConfirmationModal);
    }

    handleFormSubmission(form) {
        const formData = new FormData(form);
        const method = form.querySelector('input[name="_method"]')?.value || form.method;
        const url = form.action;

        this.clearFormErrors(form); // Clear previous errors

        fetch(url, {
            method: 'POST', // Always POST for _method spoofing
            headers: {
                'X-CSRF-TOKEN': this.csrfToken,
                'X-Requested-With': 'XMLHttpRequest' // Important for Laravel AJAX detection
            },
            body: formData
        })
        .then(response => {
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                return response.json().then(data => {
                    if (!response.ok) {
                        this.handleFormErrors(form, data);
                        throw new Error(data.message || 'Form submission failed.');
                    }
                    return data;
                });
            } else {
                if (!response.ok) {
                    window.toastr.error('An unexpected error occurred. Please try again.');
                    throw new Error('Non-JSON error response');
                }
                return response.text();
            }
        })
        .then(data => {
            // Check if the response is an HTML partial (e.g., updated list)
            if (typeof data === 'string' && data.includes(`${this.entityName}-list-container`)) {
                document.getElementById(`${this.entityName}-list-container`).innerHTML = data;
                window.toastr.success(`${this.entityName.charAt(0).toUpperCase() + this.entityName.slice(1)} saved successfully!`);
            } else if (data && data.message) {
                window.toastr.success(data.message);
            } else {
                window.toastr.success(`${this.entityName.charAt(0).toUpperCase() + this.entityName.slice(1)} saved successfully!`);
            }
            this.refreshList(); // Ensure list is refreshed
            this.closeModal(document.getElementById(this.modalAddFormId)); // Close the unified form modal
        })
        .catch(error => {
            console.error('Form Submission Error:', error);
            if (!error.responseJSON && !error.message) {
                 window.toastr.error('Network error or server unreachable.');
            }
        });
    }

    handleSearch(event) {
        event.preventDefault();
        const form = event.target.closest('form');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);

        window.history.pushState({}, '', `${this.indexRoute}?${params.toString()}`);

        fetch(`${this.indexRoute}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById(`${this.entityName}-list-container`).innerHTML = html;
        })
        .catch(error => {
            console.error('Search error:', error);
            window.toastr.error('Failed to perform search.');
        });
    }

    refreshList() {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('per_page', currentUrl.searchParams.get('per_page') || '10');
        currentUrl.searchParams.set('search', currentUrl.searchParams.get('search') || '');

        fetch(currentUrl.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            document.getElementById(`${this.entityName}-list-container`).innerHTML = html;
        })
        .catch(error => {
            console.error('Error refreshing list:', error);
            window.toastr.error('Failed to refresh list.');
        });
    }

    clearFormErrors(form) {
        if (!form) return;
        form.querySelectorAll('.text-red-500.text-xs.italic').forEach(span => {
            span.textContent = '';
        });
    }

    handleFormErrors(form, errors) {
        console.error('Form Errors:', errors);
        this.clearFormErrors(form);

        if (errors && errors.errors) {
            let hasFieldErrors = false;
            for (const field in errors.errors) {
                const errorMessages = errors.errors[field];
                const errorElement = form.querySelector(`#${field}-error`);
                if (errorElement) {
                    errorElement.textContent = errorMessages[0];
                    hasFieldErrors = true;
                }
            }

            if (!hasFieldErrors && errors.message) {
                window.toastr.error(errors.message);
            }
        } else if (errors.message) {
            window.toastr.error(errors.message);
        } else {
            window.toastr.error('An unknown error occurred during form submission.');
        }
    }
}
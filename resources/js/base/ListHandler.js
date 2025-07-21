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
        this.initialized = false;
        this.initialize();

        // Bind custom modal functions
        this.openModal = options.openModal || this._defaultOpenModal;
        this.closeModal = options.closeModal || this._defaultCloseModal;
    }

    // Default modal open/close using Tailwind CSS classes
    _defaultOpenModal(modalElement) {
        if (modalElement) {
            modalElement.classList.remove('hidden');
            modalElement.classList.add('flex'); // Assuming 'flex' makes it visible
        }
    }

    _defaultCloseModal(modalElement) {
        if (modalElement) {
            modalElement.classList.add('hidden');
            modalElement.classList.remove('flex');
        }
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
        this.initialized = true; // Set initialized to true
    }

    setupEventListeners() {
        const createButton = document.getElementById(`create${this.entityName.charAt(0).toUpperCase() + this.entityName.slice(1)}Btn`);
        if (createButton) {
            createButton.addEventListener('click', this.handleCreate.bind(this));
        }

        const listContainer = document.getElementById(`${this.entityName}-list-container`);
        if (listContainer) {
            listContainer.addEventListener('click', (event) => {
                // Edit Button
                if (event.target.closest('.edit-income-btn')) {
                    const button = event.target.closest('.edit-income-btn');
                    const id = button.dataset.id;
                    this.loadEditForm(id);
                }

                // View Button
                if (event.target.closest('.view-income-btn')) {
                    const button = event.target.closest('.view-income-btn');
                    const id = button.dataset.id;
                    this.loadViewForm(id);
                }

                // Delete Button
                if (event.target.closest('.delete-income-btn')) {
                    const form = event.target.closest('form');
                    this.handleDelete(form);
                }
            });
        }

        const searchForm = document.getElementById(`${this.entityName}-search-form`);
        if (searchForm) {
            searchForm.addEventListener('submit', this.handleSearch.bind(this));
            const perPageSelect = searchForm.querySelector('select[name="per_page"]');
            if (perPageSelect) {
                perPageSelect.addEventListener('change', this.handleSearch.bind(this));
            }
        }

        // Handle form submission inside the modal
        const modalFormContent = document.getElementById(`${this.entityName}FormContent`);
        if (modalFormContent) {
            modalFormContent.addEventListener('submit', (event) => {
                const form = event.target.closest('form');
                if (form && (form.id === 'incomeForm' || form.id === 'editForm' || form.id === 'createForm')) {
                    event.preventDefault();
                    this.handleFormSubmission(form);
                }
            });
        }

        // Modal close buttons
        const modalElements = document.querySelectorAll(`#${this.modalAddFormId}, #${this.modalViewFormId}, #deleteConfirmationModal`);
        modalElements.forEach(modal => {
            const closeButtons = modal.querySelectorAll('.close-modal');
            closeButtons.forEach(button => {
                button.addEventListener('click', () => this.closeModal(modal));
            });
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

    handleCreate() {
        const modalElement = document.getElementById(this.modalAddFormId);
        const formContentDiv = document.getElementById(`${this.entityName}FormContent`);
        const modalTitle = document.getElementById('modalTitle');

        if (modalTitle) modalTitle.textContent = `Add New ${this.entityName.charAt(0).toUpperCase() + this.entityName.slice(1)}`;

        // Clear previous form errors
        this.clearFormErrors(formContentDiv ? formContentDiv.querySelector('form') : null);

        // Fetch the fresh form content via AJAX
        fetch(`/${this.routeName}/create`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                if (formContentDiv) {
                    formContentDiv.innerHTML = html;
                    this.openModal(modalElement);
                    this.postFormRender(); // Call hook after content is rendered
                }
            })
            .catch(error => {
                console.error('Error fetching create form:', error);
                window.toastr.error('Failed to load create form.');
            });
    }

    loadEditForm(id) {
        const modalElement = document.getElementById(this.modalEditFormId);
        const formContentDiv = document.getElementById(`${this.entityName}FormContent`);
        const modalTitle = document.getElementById('modalTitle');

        if (modalTitle) modalTitle.textContent = `Edit ${this.entityName.charAt(0).toUpperCase() + this.entityName.slice(1)}`;

        // Clear previous form errors
        this.clearFormErrors(formContentDiv ? formContentDiv.querySelector('form') : null);

        fetch(`/${this.routeName}/${id}/edit`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                if (formContentDiv) {
                    formContentDiv.innerHTML = html;
                    this.openModal(modalElement);
                    this.postFormRender(); // Call hook after content is rendered
                }
            })
            .catch(error => {
                console.error('Error fetching edit form:', error);
                window.toastr.error('Failed to load edit form.');
            });
    }

    loadViewForm(id) {
        const modalElement = document.getElementById(this.modalViewFormId);
        const viewContentDiv = document.getElementById('viewIncomeContent'); // Specific ID for view content

        fetch(`/${this.routeName}/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                if (viewContentDiv) {
                    viewContentDiv.innerHTML = html;
                    this.openModal(modalElement);
                }
            })
            .catch(error => {
                console.error('Error fetching view form:', error);
                window.toastr.error('Failed to load details.');
            });
    }

    handleDelete(form) {
        this.currentDeleteForm = form;
        const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
        this.openModal(deleteConfirmationModal);
    }

    handleFormSubmission(form) {
        const formData = new FormData(form);
        const method = form.querySelector('input[name="_method"]')?.value || form.method;
        const url = form.action;

        // Clear previous errors before new submission
        this.clearFormErrors(form);

        fetch(url, {
            method: 'POST', // Always POST for _method spoofing
            headers: {
                'X-CSRF-TOKEN': this.csrfToken,
                // 'Content-Type': 'application/json', // Only if sending JSON
                'X-Requested-With': 'XMLHttpRequest' // Important for Laravel AJAX detection
            },
            body: formData
        })
        .then(response => {
            // Check if it's a JSON response (for validation errors) or a redirect
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                return response.json().then(data => {
                    if (!response.ok) {
                        this.handleFormErrors(form, data); // Pass the errors to a dedicated handler
                        throw new Error(data.message || 'Form submission failed.');
                    }
                    return data;
                });
            } else {
                // Assume it's a redirect or success HTML response
                if (!response.ok) {
                    // Handle non-JSON error responses (e.g., 403, 500 HTML pages)
                    window.toastr.error('An unexpected error occurred. Please try again.');
                    throw new Error('Non-JSON error response');
                }
                return response.text(); // Return text to allow refresh or partial update
            }
        })
        .then(data => {
            if (typeof data === 'string' && data.includes('income-list-container')) {
                // If the response is the updated list HTML, update the list
                document.getElementById('income-list-container').innerHTML = data;
            } else if (data && data.message) {
                // If data is JSON with a message (e.g., success message from store/update)
                window.toastr.success(data.message);
                this.refreshList(); // Refresh list after success
            } else {
                // Fallback for success without explicit message or list update
                window.toastr.success(`${this.entityName.charAt(0).toUpperCase() + this.entityName.slice(1)} saved successfully!`);
                this.refreshList();
            }
            this.closeModal(document.getElementById(this.modalAddFormId)); // Close modal on success
        })
        .catch(error => {
            console.error('Form Submission Error:', error);
            // Error handling is done in handleFormErrors, so no need for general toastr here
            // unless it's a network error not caught by handleFormErrors
            if (!error.responseJSON && !error.message) { // Generic network error not handled by validation
                 window.toastr.error('Network error or server unreachable.');
            }
        });
    }

    handleSearch(event) {
        event.preventDefault();
        const form = event.target.closest('form');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);

        // Update the URL in the browser's address bar
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
        currentUrl.searchParams.set('per_page', currentUrl.searchParams.get('per_page') || '10'); // Maintain current per_page or default
        currentUrl.searchParams.set('search', currentUrl.searchParams.get('search') || ''); // Maintain search term

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

    // New method to clear previous error messages from the form
    clearFormErrors(form) {
        if (!form) return;
        form.querySelectorAll('.text-red-500.text-xs.italic').forEach(span => {
            span.textContent = '';
        });
    }

    // Renamed handleFormError to handleFormErrors for clarity and consistency
    handleFormErrors(form, errors) { // Changed parameter from 'error' to 'errors' for clarity
        console.error('Form Errors:', errors);
        // Clear all previous error messages
        this.clearFormErrors(form);

        if (errors && errors.errors) {
            let hasFieldErrors = false;
            for (const field in errors.errors) {
                const errorMessages = errors.errors[field];
                const errorElement = form.querySelector(`#${field}-error`); // Use querySelector for specific IDs
                if (errorElement) {
                    errorElement.textContent = errorMessages[0]; // Use textContent for setting text
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
}
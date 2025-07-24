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

        this.openModal = options.openModal || window.openModal;
        this.closeModal = options.closeModal || window.closeModal;

        this.currentDeleteForm = null;
        this.initialized = false;

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
        const createButton = document.getElementById(`create${this.ucFirst(this.entityName)}Btn`);
        if (createButton) {
            createButton.addEventListener('click', () => this.handleCreate());
        }

        const listContainer = document.getElementById(`${this.entityName}-list-container`);
        if (listContainer) {
            listContainer.addEventListener('click', (event) => {
                const button = event.target.closest('button[data-action]');
                if (!button) return;

                const action = button.dataset.action;
                const id = button.dataset.id;
                const form = button.closest('form');

                switch (action) {
                    case 'edit':
                        this.loadForm(this.modalEditFormId, `/${this.routeName}/${id}/edit`, 'edit');
                        break;
                    case 'view':
                        this.loadForm(this.modalViewFormId, `/${this.routeName}/${id}`, 'view');
                        break;
                    case 'delete':
                        this.handleDelete(form);
                        break;
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

        const formModalContentDiv = document.getElementById(this.modalFormContentId);
        if (formModalContentDiv) {
            formModalContentDiv.addEventListener('submit', (event) => {
                const form = event.target.closest('form');
                if (form) {
                    event.preventDefault();
                    this.handleFormSubmission(form);
                }
            });
        }

        document.addEventListener('click', (event) => {
            const closeButton = event.target.closest('.close-modal');
            if (closeButton) {
                const modalId = closeButton.dataset.modalId || closeButton.closest('.fixed.inset-0')?.id;
                if (modalId) {
                    this.closeModal(document.getElementById(modalId));
                }
            }
        });

        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', () => {
                if (this.currentDeleteForm) this.currentDeleteForm.submit();
                this.closeModal(document.getElementById('deleteConfirmationModal'));
            });
        }

        if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', () => {
                this.closeModal(document.getElementById('deleteConfirmationModal'));
            });
        }
    }

    ucFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    handleCreate() {
        this.loadForm(this.modalAddFormId, `/${this.routeName}/create`, 'create');
    }

    loadForm(modalId, url, type) {
        const modalElement = document.getElementById(modalId);
        const contentDiv = document.getElementById(this.modalFormContentId);
        const viewDiv = document.getElementById('viewIncomeContent') || document.getElementById('viewExpenseContent');

        const targetDiv = (type === 'view') ? viewDiv : contentDiv;
        const modalTitle = document.getElementById(this.modalFormTitleId);

        if (!modalElement || !targetDiv) {
            window.toastr.error('Modal container or content not found.');
            return;
        }

        if (modalTitle && type !== 'view') {
            modalTitle.textContent = `${this.ucFirst(type)} ${this.ucFirst(this.entityName)}`;
        }

        targetDiv.innerHTML = this.loadingOverlay;
        this.openModal(modalElement);

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => res.ok ? res.text() : res.text().then(txt => Promise.reject(txt)))
            .then(html => {
                targetDiv.innerHTML = html;
                if ((type === 'create' || type === 'edit') && typeof this.postFormRender === 'function') {
                    this.postFormRender();
                }
            })
            .catch(error => {
                console.error('Modal load error:', error);
                window.toastr.error(`Failed to load ${type} form`);
                this.closeModal(modalElement);
            });
    }

    handleDelete(form) {
        this.currentDeleteForm = form;
        this.openModal(document.getElementById('deleteConfirmationModal'));
    }

    handleFormSubmission(form) {
        const formData = new FormData(form);
        const method = form.querySelector('input[name="_method"]')?.value || form.method;
        const url = form.action;

        this.clearFormErrors(form);

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': this.csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
            .then(res => res.json().then(data => {
                if (!res.ok) {
                    this.handleFormErrors(form, data);
                    throw new Error(data.message || 'Form failed');
                }
                return data;
            }))
            .then(data => {
                window.toastr.success(data.message || `${this.ucFirst(this.entityName)} saved!`);
                this.refreshList();
                this.closeModal(document.getElementById(this.modalAddFormId));
            })
            .catch(error => {
                console.error('Submit error:', error);
                window.toastr.error(error.message || 'Network error.');
            });
    }

    handleSearch(event) {
        event.preventDefault();
        const form = event.target.closest('form');
        const params = new URLSearchParams(new FormData(form));

        window.history.pushState({}, '', `${this.indexRoute}?${params}`);
        fetch(`${this.indexRoute}?${params}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => res.text())
            .then(html => {
                document.getElementById(`${this.entityName}-list-container`).innerHTML = html;
            })
            .catch(err => {
                console.error('Search failed:', err);
                window.toastr.error('Search error.');
            });
    }

    refreshList() {
        const currentUrl = new URL(window.location.href);

        fetch(currentUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => res.text())
            .then(html => {
                document.getElementById(`${this.entityName}-list-container`).innerHTML = html;
            })
            .catch(err => {
                console.error('List refresh failed:', err);
                window.toastr.error('Could not refresh list.');
            });
    }

    clearFormErrors(form) {
        form.querySelectorAll('.text-red-500.text-xs.italic').forEach(span => span.textContent = '');
    }

    handleFormErrors(form, errors) {
        this.clearFormErrors(form);
        if (errors?.errors) {
            Object.entries(errors.errors).forEach(([field, messages]) => {
                const errorEl = form.querySelector(`#${field}-error`);
                if (errorEl) errorEl.textContent = messages[0];
            });
        } else {
            window.toastr.error(errors.message || 'Form validation error');
        }
    }
}

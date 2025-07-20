// resources/js/income/IncomeListHandler.js
import ListHandler from "../base/ListHandler"; // Adjust path as needed

export class IncomeListHandler extends ListHandler {
    constructor(options) {
        super({
            ...options,
            entityName: 'income',
            routeName: 'income', // Matches your Laravel route prefix
            modalAddFormId: 'incomeModal', // The ID of your create/edit modal
            modalEditFormId: 'incomeModal', // Same modal for edit
            modalViewFormId: 'viewIncomeModal', // The ID of your view modal
        });
    }

    // You can add income-specific methods or override base methods here if needed.
    // For example, if 'source' is a Select2 field that needs initialization:
    // initSourceSelect2() {
    //     const $select = $('#source');
    //     if ($select.length) {
    //         // Initialize Select2 here if you are using it for the 'source' field
    //         // e.g., $select.select2({ /* options */ });
    //     }
    // }

    // Override setupEventListeners if you need to add specific event listeners
    // or re-initialize components (like Select2) when modals are shown.
    // setupEventListeners() {
    //     super.setupEventListeners(); // Call the parent's event listeners first
    //     const namespace = `.${this.entityName}Handler`;

    //     // Re-initialize Select2 or other components when the income modal is shown
    //     $(document).on('shown.bs.modal', `#${this.modalAddFormId}`, () => {
    //         this.initSourceSelect2();
    //     });
    // }
}
import ListHandler from "../base/ListHandler";
import $ from 'jquery'; // Ensure jQuery is imported if you're using it directly here

export class ExpenseListHandler extends ListHandler {
    constructor(options) {
        super({
            ...options,
            entityName: 'expense',
            routeName: 'expense',
            // Use the unified modal ID for both create and edit forms
            modalAddFormId: 'expenseFormModal',
            modalEditFormId: 'expenseFormModal',
            modalViewFormId: 'viewExpenseModal', // This remains separate for viewing
            // Specify the content container for the form modal
            modalFormContentId: 'expenseFormModalContent',
            // Specify the title element for the form modal
            modalFormTitleId: 'expenseFormModalTitle',
        });
    }

    // This method will be called by ListHandler after the form HTML is injected
    postFormRender() {
        // Initialize Select2 for the 'source' dropdown within the *currently loaded* form
        // Ensure the select element has the ID 'source' and the class 'select2-ajax'
        const $select = $('#source.select2-ajax');
        if ($select.length) {
            // Call the globally defined initSelect2ForSource function
            window.initSelect2ForSource($select, "Select a source user");
        } else {
            console.warn("Select2 element with ID 'source' and class 'select2-ajax' not found for initialization.");
        }
    }

    setupEventListeners() {
        super.setupEventListeners();
        // No need for specific 'shown.bs.modal' listeners here anymore,
        // as postFormRender handles Select2 initialization after AJAX content load.
    }
}

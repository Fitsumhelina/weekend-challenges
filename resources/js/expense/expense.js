import ListHandler from "../base/ListHandler";
import $ from 'jquery'; 

export class ExpenseListHandler extends ListHandler {
    constructor(options) {
        super({
            ...options,
            entityName: 'expense',
            routeName: 'expense',
            modalAddFormId: 'expenseFormModal',
            modalEditFormId: 'expenseFormModal',
            modalViewFormId: 'viewExpenseModal', 
            modalFormContentId: 'expenseFormModalContent',
            modalFormTitleId: 'expenseFormModalTitle',
        });
    }

    postFormRender() {
        const $select = $('#source.select2-ajax');
        if ($select.length) {
            window.initSelect2ForSource($select, "Select a source user");
        } else {
            console.warn("Select2 element with ID 'source' and class 'select2-ajax' not found for initialization.");
        }
    }

    setupEventListeners() {
        super.setupEventListeners();
    }
}

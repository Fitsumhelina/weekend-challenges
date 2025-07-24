import ListHandler from "../base/ListHandler";
import $ from 'jquery'; 

export class IncomeListHandler extends ListHandler {
    constructor(options) {
        super({
            ...options,
            entityName: 'income',
            routeName: 'income',
            modalAddFormId: 'incomeFormModal',
            modalEditFormId: 'incomeFormModal',
            modalViewFormId: 'viewIncomeModal',
            modalFormContentId: 'incomeFormModalContent',
            modalFormTitleId: 'incomeFormModalTitle',
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

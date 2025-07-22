import ListHandler from "../base/ListHandler";
import $ from 'jquery'; // Ensure jQuery is imported if you're using it directly here

export class IncomeListHandler extends ListHandler {
    constructor(options) {
        super({
            ...options,
            entityName: 'permissions',
            routeName: 'permissions',
            modalAddFormId: 'permissionFormModal',
            modalEditFormId: 'permissionFormModal',
            modalViewFormId: 'viewPermissionModal',
            modalFormContentId: 'permissionFormModalContent',
            modalFormTitleId: 'permissionFormModalTitle',
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

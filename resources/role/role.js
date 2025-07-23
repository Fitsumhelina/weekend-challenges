import ListHandler from "../base/ListHandler";
import $ from 'jquery'; // Ensure jQuery is imported if you're using it directly here

export class RoleListHandler extends ListHandler {
    constructor(options) {
        super({
            ...options,
            entityName: 'role',
            routeName: 'role',
            modalAddFormId: 'roleFormModal',
            modalEditFormId: 'roleFormModal',
            modalViewFormId: 'viewroleModal',
            modalFormContentId: 'roleFormModalContent',
            modalFormTitleId: 'roleFormModalTitle',
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

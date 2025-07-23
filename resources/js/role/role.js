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
        
    }

    setupEventListeners() {
        super.setupEventListeners();
    }
}

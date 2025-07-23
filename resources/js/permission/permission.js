import ListHandler from "../base/ListHandler";
import $ from 'jquery'; // Ensure jQuery is imported if you're using it directly here

export class PermissionListHandler extends ListHandler {
    constructor(options) {
        super({
            ...options,
            entityName: 'permission',
            routeName: 'permission',
            modalAddFormId: 'permissionFormModal',
            modalEditFormId: 'permissionFormModal',
            modalViewFormId: 'viewPermissionModal',
            modalFormContentId: 'permissionFormModalContent',
            modalFormTitleId: 'permissionFormModalTitle',
        });
    }

    postFormRender() {
        
    }

    setupEventListeners() {
        super.setupEventListeners();
    }
}

import ListHandler from "../base/ListHandler";

export class RoleListHandler extends ListHandler {
    constructor(options) {
        super({
            ...options,
            entityName: 'role',
            routeName: 'role',
            modalAddFormId: 'roleFormModal',
            modalEditFormId: 'roleFormModal',
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

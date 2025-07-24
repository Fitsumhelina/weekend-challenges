import ListHandler from "../base/ListHandler";





export class UserListHandler extends ListHandler {
    constructor(options) {
        super({
            ...options,
            entityName: 'user',
            routeName: 'user',
            modalAddFormId: 'userFormModal',
            modalEditFormId: 'userFormModal',
            modalViewFormId: 'viewUserModal',
            modalFormContentId: 'userFormModalContent',
            modalFormTitleId: 'userFormModalTitle',
        });
    }

    postFormRender() {

    }
    setupEventListeners() {
        super.setupEventListeners();
    }


}
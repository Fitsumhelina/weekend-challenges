import ListHandler from "../base/ListHandler";
import $ from 'jquery'; 





export class UserListHandler extends ListHandler {
    constructor(options) {
        super({
            ...options,
            entityName: 'user',
            routeName: 'user',
            modalAddFormId: 'createUserModal',
            modalEditFormId: 'editUserModal',
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
import ListHandler from "../base/ListHandler";



export class UserListHandler extends ListHandler {
    constructor(options) {
        super({
            ...options,
            indexRoute: Data.UserIndexRoute,
            csrfToken: Data.csrfToken,
            entityName: 'user',
            routeName: '/user',
            modalAddFormId: 'createUserModal',
            modalEditFormId: 'editUserModal',
            modalViewFormId: 'viewUserModal'
        });
    }
}

export default UserListHandler;

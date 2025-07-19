import ListHandler from "../base/ListHandler";


export class PermissionListHandler extends ListHandler {
    constructor(options) {
        super({
            ...options,
            indexRoute: Data.PermissionIndexRoute,
            csrfToken: Data.csrfToken,
            entityName: 'permission',
            routeName: '/permission',
            modalAddFormId: 'createPermissionModal',
            modalEditFormId: 'editPermissionModal',
            modalViewFormId: 'viewPermissionModal'
        });
    }
}

export class RoleListHandler extends ListHandler {
    constructor(options) {
        super({
            ...options,
            indexRoute: Data.RoleIndexRoute,
            csrfToken: Data.csrfToken,
            entityName: 'role',
            routeName: '/role',
            modalAddFormId: 'createRoleModal',
            modalEditFormId: 'editRoleModal',
            modalViewFormId: 'viewRoleModal'
        });
    }
}

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
import ListHandler from "../base/ListHandler";

class IncomeListHandler extends ListHandler {
    constructor(options) {
        super({
            ...options,
            indexRoute: Data.IncomeIndexRoute,
            csrfToken: Data.csrfToken,
            entityName: 'income',
            routeName: '/income',
            modalAddFormId: 'createIncomeModal',
            modalEditFormId: 'editIncomeModal',
            modalViewFormId: 'viewIncomeModal'
        });
    }

}

export default IncomeListHandler;
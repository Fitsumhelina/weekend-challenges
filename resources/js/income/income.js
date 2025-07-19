import ListHandler from "../base/ListHandler";

class IncomeListHandler extends ListHandler {
    constructor(options) {
        super({
            indexRoute: options.indexRoute,
            csrfToken: options.csrfToken,
            entityName: 'income',
            routeName: '/income',
            modalAddFormId: 'createIncomeModal',
            modalEditFormId: 'editIncomeModal',
            modalViewFormId: 'viewIncomeModal'
        });
    }

}

export default IncomeListHandler;
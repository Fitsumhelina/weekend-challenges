import ListHandler from "../base/ListHandler";

class ExpenseListHandler extends ListHandler {
    constructor(options) {
        super({
            ...options,
            indexRoute: Data.ExpenseIndexRoute,
            csrfToken: Data.csrfToken,
            entityName: 'expense',
            routeName: '/expense',
            modalAddFormId: 'createExpenseModal',
            modalEditFormId: 'editExpenseModal',
            modalViewFormId: 'viewExpenseModal'
        });
    }
}

export default ExpenseListHandler;

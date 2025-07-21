import ListHandler from "../base/ListHandler"; 

export class IncomeListHandler extends ListHandler {
    constructor(options) {
        super({
            ...options,
            entityName: 'income',
            routeName: 'income', 
            modalAddFormId: 'incomeModal', 
            modalEditFormId: 'incomeModal', 
            modalViewFormId: 'viewIncomeModal', 
        });
    }

     initSourceSelect2() {
        // Use the generic function for this modal
        const $select = $('.select2-ajax[name="user_id"]');
        initSourceSelect2($select);
    }

    postFormRender() {
        this.initSourceSelect2(); 
    }

    setupEventListeners() {
        super.setupEventListeners();
        const namespace = `.${this.entityName}Handler`;

    } 
}
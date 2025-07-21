// resources/js/init.js
import { IncomeListHandler } from './income/income';
import { ExpenseListHandler } from './expense/expense';

const openModal = (modalElement) => {
    modalElement.classList.remove('hidden');
    modalElement.classList.add('flex');
};

const closeModal = (modalElement) => {
    modalElement.classList.add('hidden');
    modalElement.classList.remove('flex');
};

if (document.getElementById('income-list-container')) {
    new IncomeListHandler({
        indexRoute: AppData.IncomeIndexRoute,
        csrfToken: AppData.csrfToken,
    });
}

if (document.getElementById('expense-list-container')) {
    new ExpenseListHandler({
        indexRoute: AppData.ExpenseIndexRoute,
        csrfToken: AppData.csrfToken,
    });
}
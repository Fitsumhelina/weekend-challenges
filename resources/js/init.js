// resources/js/init.js
import { IncomeListHandler } from './income/income';
import { ExpenseListHandler } from './expense/expense';
import { PermissionListHandler } from './permission/permission'; 
import { RoleListHandler } from './role/role'; 

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

if (document.getElementById('permission-list-container')) {
    // Assuming you have a PermissionListHandler similar to IncomeListHandler
    new PermissionListHandler({
        indexRoute: AppData.PermissionIndexRoute,
        csrfToken: AppData.csrfToken,
    });
}
if (document.getElementById('role-list-container')) {
    new RoleListHandler({
        indexRoute: AppData.RoleIndexRoute,
        csrfToken: AppData.csrfToken,
    });
}
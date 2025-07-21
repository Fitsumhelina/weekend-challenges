// resources/js/init.js
import { IncomeListHandler } from './income';

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
        openModal: openModal,
        closeModal: closeModal
    });
}
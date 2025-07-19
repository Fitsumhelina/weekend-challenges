function showTable(type) {
    const expensesTable = document.getElementById('expenses-table');
    const incomesTable = document.getElementById('incomes-table');
    const tabExpenses = document.getElementById('tab-expenses-table');
    const tabIncomes = document.getElementById('tab-incomes-table');

    expensesTable.classList.add('hidden');
    incomesTable.classList.add('hidden');
    tabExpenses.classList.remove('border-b-2', 'border-red-600', 'text-red-700');
    tabExpenses.classList.add('border-b-2', 'border-transparent', 'text-gray-600');
    tabIncomes.classList.remove('border-b-2', 'border-green-600', 'text-green-700');
    tabIncomes.classList.add('border-b-2', 'border-transparent', 'text-gray-600');

    if (type === 'expenses') {
        expensesTable.classList.remove('hidden');
        tabExpenses.classList.add('border-b-2', 'border-red-600', 'text-red-700');
        tabExpenses.classList.remove('border-transparent', 'text-gray-600');
    }
    if (type === 'incomes') {
        incomesTable.classList.remove('hidden');
        tabIncomes.classList.add('border-b-2', 'border-green-600', 'text-green-700');
        tabIncomes.classList.remove('border-transparent', 'text-gray-600');
    }
}

function toggleTransactionForm() {
    const form = document.getElementById('transaction-form');
    form.classList.toggle('hidden');
    if (!form.classList.contains('hidden')) {
        form.scrollIntoView({ behavior: 'smooth' });
    }
}

function showForm(type) {
    const incomeForm = document.getElementById('income-form');
    const expenseForm = document.getElementById('expense-form');
    incomeForm.classList.add('hidden');
    expenseForm.classList.add('hidden');

    if (type === 'income') incomeForm.classList.remove('hidden');
    if (type === 'expense') expenseForm.classList.remove('hidden');
}
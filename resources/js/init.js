// resources/js/init.js
import { IncomeListHandler } from './income/IncomeListHandler'; // New import


// Initialize IncomeListHandler if on the income dashboard
if (document.getElementById('income-list-container')) { // Or your search form ID
    new IncomeListHandler({
        indexRoute: AppData.IncomeIndexRoute, // From your AppData in index.blade.php
        csrfToken: AppData.csrfToken,
    });
}
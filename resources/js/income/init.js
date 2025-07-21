// resources/js/init.js
import { IncomeListHandler } from './income'; // New import



if (document.getElementById('income-list-container')) { 
    new IncomeListHandler({
        indexRoute: AppData.IncomeIndexRoute,
        csrfToken: AppData.csrfToken,
    });
}
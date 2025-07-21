// resources/js/income/IncomeListHandler.js (Modified)
import ListHandler from "../base/ListHandler"; // Adjust path as needed

export class IncomeListHandler extends ListHandler {
    constructor(options) {
        super({
            ...options,
            entityName: 'income',
            routeName: 'income', // Matches your Laravel route prefix
            modalAddFormId: 'incomeModal', // The ID of your create/edit modal shell in index.blade.php
            modalEditFormId: 'incomeModal', // Same modal for edit
            modalViewFormId: 'viewIncomeModal', // The ID of your view modal shell in index.blade.php
        });
    }

    // Example: Initialize Select2 for the 'source' field in the income form
    initSourceSelect2() {
        // Ensure jQuery is available or adapt to vanilla JS for Select2 initialization
        if (typeof jQuery !== 'undefined' && $.fn.select2) {
            const $select = $('#source'); // ID of the select element in your form partial
            if ($select.length && !$select.data('select2')) { // Check if Select2 is already initialized
                $select.select2({
                    placeholder: "Select a source user",
                    allowClear: true,
                    // Add AJAX options here if your source list is dynamic and large
                    // ajax: {
                    //     url: '/api/users', // Example API endpoint for users
                    //     dataType: 'json',
                    //     delay: 250,
                    //     data: function (params) {
                    //         return {
                    //             q: params.term, // search term
                    //             page: params.page
                    //         };
                    //     },
                    //     processResults: function (data) {
                    //         return {
                    //             results: data.map(user => ({ id: user.id, text: user.name }))
                    //         };
                    //     },
                    //     cache: true
                    // }
                });
            }
        } else {
            console.warn("Select2 or jQuery not available. 'initSourceSelect2' will not run.");
        }
    }

    // Implement postFormRender to initialize Select2 after the form is loaded
    postFormRender() {
        this.initSourceSelect2(); // Initialize Select2 specifically for the 'source' field
    }

    setupEventListeners() {
        super.setupEventListeners(); // Call the parent's event listeners first
        // No specific additional event listeners needed here as postFormRender handles Select2 init
    }
}
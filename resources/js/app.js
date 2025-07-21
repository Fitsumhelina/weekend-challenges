import './bootstrap';
import './init';


import 'toastr/build/toastr.min.css';
import toastr from 'toastr';

import Alpine from 'alpinejs';

window.Alpine = Alpine; 

Alpine.start();

// Make jQuery globally available (if you use it, ensure it's properly imported/aliased by bootstrap.js)
// window.$ = window.jQuery = $;

// Make toastr and Swal globally available
window.toastr = toastr;
// window.Swal = Swal; // Uncomment if you are using SweetAlert2

// Initialize toastr options
toastr.options = {
    closeButton: true,
    debug: false,
    newestOnTop: true,
    progressBar: true,
    positionClass: "toast-top-right",
    preventDuplicates: false,
    onclick: null,
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "5000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut"
};

// Register Service Worker (keep if you have a PWA setup)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('ServiceWorker registration successful');
            })
            .catch(err => {
                console.log('ServiceWorker registration failed: ', err);
            });
    });
}

// Handle Install Prompt (keep if you have a PWA setup)
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
});

// Import the main initializer
import './init'; // This line is crucial for initializing your ListHandlers
// ... rest of app.js content ...

// If you have an `init()` function at the end of app.js, ensure it's called
// or remove it if your modules handle their own initialization via imports.
// (function ($) {
//     "use strict";
//     // ... other init functions ...
//     function init() {
//         // ... calls to other init functions ...
//     }
//     init();
// })(jQuery);
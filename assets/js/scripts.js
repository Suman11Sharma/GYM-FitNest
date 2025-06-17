/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
// 
// Scripts
// 

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});



// Set today's date in 'from' input as min
document.addEventListener('DOMContentLoaded', function () {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('dateFrom').setAttribute('min', today);

    // When "From Date" changes, update min for "To Date"
    document.getElementById('dateFrom').addEventListener('change', function () {
        const fromDate = new Date(this.value);
        if (!isNaN(fromDate)) {
            const toDate = new Date(fromDate);
            toDate.setDate(toDate.getDate() + 3); // add 3 days
            document.getElementById('dateTo').setAttribute('min', toDate.toISOString().split('T')[0]);
        }
    });
});

// Final form validation before submit
function validateForm(event) {
    const from = new Date(document.getElementById('dateFrom').value);
    const to = new Date(document.getElementById('dateTo').value);

    const minTo = new Date(from);
    minTo.setDate(minTo.getDate() + 3);

    if (to < minTo) {
        alert("To Date must be at least 3 days after the From Date.");
        event.preventDefault();
        return false;
    }

    return true;
}


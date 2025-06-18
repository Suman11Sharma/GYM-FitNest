

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



// ads index table js
const adsData = Array.from({
    length: 50
}, (_, i) => ({
    adsId: 'AD' + (1000 + i),
    companyName: 'Company ' + (i + 1),
    duration: (10 + (i % 5)) + ' days',
    visibility: true,
    image: '../Uploads/gym.jpg'
}));

let currentPage = 1;
const rowsPerPage = 15;

function displayTable(data, page = 1) {
    const tbody = document.getElementById('adsTableBody');
    tbody.innerHTML = '';

    const start = (page - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const pageData = data.slice(start, end);

    pageData.forEach((item, index) => {
        const row = document.createElement('tr');

        row.innerHTML = `
          <td>${start + index + 1}</td>
          <td>${item.adsId}</td>
          <td><img src="${item.image}" alt="Ad Image" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;"></td>
          <td>${item.companyName}</td>
          <td>${item.duration}</td>
          <td>
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" ${item.visibility ? 'checked' : ''} onchange="toggleVisibility(${start + index})">
            </div>
          </td>
          <td class="table-actions">
            <button class="btn btn-sm btn-warning" onclick="editAd(${start + index})">Edit</button>
            <button class="btn btn-sm btn-danger" onclick="deleteAd(${start + index})">Delete</button>
          </td>
        `;

        tbody.appendChild(row);
    });

    displayPagination(data.length);
}

function displayPagination(totalRows) {
    const pageCount = Math.ceil(totalRows / rowsPerPage);
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';

    for (let i = 1; i <= pageCount; i++) {
        const li = document.createElement('li');
        li.className = 'page-item' + (i === currentPage ? ' active' : '');
        li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
        li.addEventListener('click', (e) => {
            e.preventDefault();
            currentPage = i;
            filterAndDisplay();
        });
        pagination.appendChild(li);
    }
}

function toggleVisibility(index) {
    adsData[index].visibility = !adsData[index].visibility;
    console.log(`Ad ${adsData[index].adsId} visibility: ${adsData[index].visibility}`);
}

function editAd(index) {
    alert(`Edit clicked for ${adsData[index].adsId}`);
}

function deleteAd(index) {
    if (confirm(`Are you sure to delete ${adsData[index].adsId}?`)) {
        adsData.splice(index, 1);
        const maxPage = Math.ceil(adsData.length / rowsPerPage);
        if (currentPage > maxPage) currentPage = maxPage;
        filterAndDisplay();
    }
}

function filterAndDisplay() {
    const query = document.getElementById('searchInput').value.toLowerCase();
    const filtered = adsData.filter(item => item.companyName.toLowerCase().includes(query));
    displayTable(filtered, currentPage);
}

document.getElementById('searchInput').addEventListener('input', () => {
    currentPage = 1;
    filterAndDisplay();
});

document.getElementById('btnNavbarSearch').addEventListener('click', () => {
    currentPage = 1;
    filterAndDisplay();
});

filterAndDisplay();



<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Ads Table</h3>

            <!-- Search Input -->
            <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search Company Name">

            <!-- Table -->
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>SN</th>
                        <th>AdsId</th>
                        <th>Company Name</th>
                        <th>Duration</th>
                        <th>Visibility</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="adsTableBody">
                    <!-- JS will populate rows -->
                </tbody>
            </table>

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center" id="pagination">
                    <!-- JS will populate pagination -->
                </ul>
            </nav>
        </div>

        <script>
            // Dummy data
            const adsData = Array.from({
                length: 50
            }, (_, i) => ({
                adsId: 'AD' + (1000 + i),
                companyName: 'Company ' + (i + 1),
                duration: (10 + i % 5) + ' days',
                visibility: true
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
                    filterAndDisplay();
                }
            }

            function filterAndDisplay() {
                const query = document.getElementById('searchInput').value.toLowerCase();
                const filtered = adsData.filter(item =>
                    item.companyName.toLowerCase().includes(query)
                );
                displayTable(filtered, currentPage);
            }

            document.getElementById('searchInput').addEventListener('input', () => {
                currentPage = 1;
                filterAndDisplay();
            });

            // Initialize
            filterAndDisplay();
        </script>
    </main>
    <?php require("../layouts/footer.php"); ?>
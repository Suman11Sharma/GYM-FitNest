    <?php require("../sidelayout.php"); ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container mt-4">
                <h3 class="mb-3">Contact Us Messages</h3>

                <!-- Table -->
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Received At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="contactTableBody">
                        <!-- JS populates rows -->
                    </tbody>
                </table>

                <!-- Pagination -->
                <nav>
                    <ul class="pagination justify-content-center" id="contactPagination">
                        <!-- JS populates pagination -->
                    </ul>
                </nav>
            </div>

            <!-- View Modal -->
            <div class="modal fade" id="viewContactModal" tabindex="-1" aria-labelledby="viewContactModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewContactModalLabel">Contact Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Full Name:</strong> <span id="modalFullName"></span></p>
                            <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                            <p><strong>Message:</strong> <span id="modalMessage"></span></p>
                            <p><strong>Received At:</strong> <span id="modalTimestamp"></span></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Script -->
            <script>
                const contactData = Array.from({
                    length: 28
                }, (_, i) => ({
                    fullName: `User ${i + 1}`,
                    email: `user${i + 1}@example.com`,
                    message: `Sample message from user ${i + 1}. Lorem ipsum dolor sit amet.`,
                    timestamp: new Date().toLocaleString()
                }));

                let currentPage = 1;
                const rowsPerPage = 15;

                function displayContactTable(data, page = 1) {
                    const tbody = document.getElementById('contactTableBody');
                    tbody.innerHTML = '';

                    const start = (page - 1) * rowsPerPage;
                    const end = start + rowsPerPage;
                    const pageData = data.slice(start, end);

                    pageData.forEach((item, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
          <td>${start + index + 1}</td>
          <td>${item.fullName}</td>
          <td>${item.email}</td>
          <td>${item.message}</td>
          <td>${item.timestamp}</td>
          <td>
            <button class="btn btn-sm btn-info me-1" onclick="viewMessage(${start + index})" data-bs-toggle="modal" data-bs-target="#viewContactModal">
              <i class="fas fa-eye"></i>
            </button>
            <button class="btn btn-sm btn-danger" onclick="deleteMessage(${start + index})">
              <i class="fas fa-trash"></i>
            </button>
          </td>
        `;
                        tbody.appendChild(row);
                    });

                    displayPagination(data.length);
                }

                function displayPagination(totalRows) {
                    const pageCount = Math.ceil(totalRows / rowsPerPage);
                    const pagination = document.getElementById('contactPagination');
                    pagination.innerHTML = '';

                    for (let i = 1; i <= pageCount; i++) {
                        const li = document.createElement('li');
                        li.className = 'page-item' + (i === currentPage ? ' active' : '');
                        li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                        li.addEventListener('click', (e) => {
                            e.preventDefault();
                            currentPage = i;
                            displayContactTable(contactData, currentPage);
                        });
                        pagination.appendChild(li);
                    }
                }

                function viewMessage(index) {
                    const data = contactData[index];
                    document.getElementById('modalFullName').textContent = data.fullName;
                    document.getElementById('modalEmail').textContent = data.email;
                    document.getElementById('modalMessage').textContent = data.message;
                    document.getElementById('modalTimestamp').textContent = data.timestamp;
                }

                function deleteMessage(index) {
                    if (confirm(`Delete message from ${contactData[index].fullName}?`)) {
                        contactData.splice(index, 1);
                        if ((currentPage - 1) * rowsPerPage >= contactData.length) {
                            currentPage = Math.max(currentPage - 1, 1);
                        }
                        displayContactTable(contactData, currentPage);
                    }
                }

                displayContactTable(contactData);
            </script>
        </main>
        <?php require("../assets/link.php"); ?>
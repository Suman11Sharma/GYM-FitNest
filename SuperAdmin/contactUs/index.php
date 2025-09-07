<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Contact Us Messages</h3>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>SN</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Reply</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="contactTableBody">
                        <!-- JS populates rows -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <nav>
                <ul class="pagination justify-content-center" id="contactPagination">
                    <!-- JS populates pagination -->
                </ul>
            </nav>
        </div>

        <!-- View Modal -->
        <div class="modal fade" id="viewContactModal" tabindex="-1" aria-labelledby="viewContactModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewContactModalLabel">Contact Details</h5>
                    </div>
                    <div class="modal-body">
                        <p><strong>Name:</strong> <span id="modalName"></span></p>
                        <p><strong>Email:</strong> <span id="modalEmail"></span></p>
                        <p><strong>Contact:</strong> <span id="modalContact"></span></p>
                        <p><strong>Subject:</strong> <span id="modalSubject"></span></p>
                        <p><strong>Message:</strong> <span id="modalMessage"></span></p>
                        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                        <p><strong>Reply:</strong> <span id="modalReply"></span></p>
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
                name: `User ${i + 1}`,
                email: `user${i + 1}@example.com`,
                contact: `9800000${i+10}`,
                subject: `Subject ${i + 1}`,
                message: `Sample message from user ${i + 1}. Lorem ipsum dolor sit amet.`,
                status: ["pending", "replied", "closed"][i % 3],
                reply: `Reply for message ${i + 1}`,
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
                        <td>${item.name}</td>
                        <td>${item.email}</td>
                        <td>${item.contact}</td>
                        <td>${item.subject}</td>
                        <td>${item.message}</td>
                        <td>${item.status}</td>
                        <td>${item.reply}</td>
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
                document.getElementById('modalName').textContent = data.name;
                document.getElementById('modalEmail').textContent = data.email;
                document.getElementById('modalContact').textContent = data.contact;
                document.getElementById('modalSubject').textContent = data.subject;
                document.getElementById('modalMessage').textContent = data.message;
                document.getElementById('modalStatus').textContent = data.status;
                document.getElementById('modalReply').textContent = data.reply;
            }

            function deleteMessage(index) {
                if (confirm(`Delete message from ${contactData[index].name}?`)) {
                    contactData.splice(index, 1);
                    if ((currentPage - 1) * rowsPerPage >= contactData.length) {
                        currentPage = Math.max(currentPage - 1, 1);
                    }
                    displayContactTable(contactData, currentPage);
                }
            }

            displayContactTable(contactData);
        </script>

        <!-- FontAwesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    </main>
    <?php require("../assets/link.php"); ?>
</div>
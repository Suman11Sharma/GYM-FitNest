<?php require("../sidelayout.php"); ?>
<div id="layoutSidenav_content">
    <main>
        <div class="container mt-4">
            <h3 class="mb-3">Ads Table</h3>

            <!-- Search Form and Add Button -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Search form -->
                <form class="form-inline d-flex" id="searchForm" onsubmit="return false;">
                    <div class="input-group">
                        <input
                            id="searchInput"
                            class="form-control"
                            type="text"
                            placeholder="Search for..."
                            aria-label="Search for..."
                            aria-describedby="btnNavbarSearch" />
                        <button class="btn btn-our" id="btnNavbarSearch" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- A tag styled as button -->
                <a href="create.php" class="btn btn-our ms-3">
                    <i class="fas fa-plus me-1"></i> Add New
                </a>
            </div>

            <!-- Table -->
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>SN</th>
                        <th>AdsId</th>
                        <th>Image</th>
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



        <!-- FontAwesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
    </main>


    <?php require("../assets/link.php"); ?>
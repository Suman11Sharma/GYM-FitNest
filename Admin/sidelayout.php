<?php require_once('Layouts/header.php'); ?>
<?php require_once('Layouts/navbar.php'); ?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu bg-dark">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Home</div>
                    <a class="nav-link" href="../adminPage.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>
                    <div class="sb-sidenav-menu-heading">Management</div>

                    <!-- Paid-Ads  -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapsePaidAds" aria-expanded="false" aria-controls="collapsePaidAds">
                        <div class="sb-nav-link-icon"><i class="fas fa-ad"></i></div>
                        Paid-Ads
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapsePaidAds" aria-labelledby="headingPaidAds" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../Paid-Ads/index.php">Index</a>
                            <a class="nav-link" href="../Paid-Ads/create.php">Create</a>
                        </nav>
                    </div>

                    <!-- Gym-Fees  -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseGymFees" aria-expanded="false" aria-controls="collapseGymFees">
                        <div class="sb-nav-link-icon"><i class="fas fa-money-bill-wave"></i></div>
                        Gym-Fees
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseGymFees" aria-labelledby="headingGymFees" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../Gym-Fees/index.php">Index</a>
                            <a class="nav-link" href="../Gym-Fees/create.php">Create</a>
                        </nav>
                    </div>

                    <!-- Gym-Subscriptions  -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseGymSubscriptions" aria-expanded="false" aria-controls="collapseGymSubscriptions">
                        <div class="sb-nav-link-icon"><i class="fas fa-calendar-alt"></i></div>
                        Gym-Subscriptions
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseGymSubscriptions" aria-labelledby="headingGymSubscriptions" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../Gym-Subscriptions/index.php">Index</a>
                            <a class="nav-link" href="../Gym-Subscriptions/create.php">Create</a>
                        </nav>
                    </div>


                    <!-- Customer-Plans  -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseCustomerPlans" aria-expanded="false" aria-controls="collapseCustomerPlans">
                        <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                        Customer-Plans
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseCustomerPlans" aria-labelledby="headingCustomerPlans" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../Customer-Plans/index.php">Index</a>
                            <a class="nav-link" href="../Customer-Plans/create.php">Create</a>
                        </nav>
                    </div>

                    <!-- Customer-Subscription  -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseCustomerSubscription" aria-expanded="false" aria-controls="collapseCustomerSubscription">
                        <div class="sb-nav-link-icon"><i class="fas fa-calendar-check"></i></div>
                        Customer-Subscription
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseCustomerSubscription" aria-labelledby="headingCustomerSubscription" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../Customer-Subscription/index.php">Index</a>
                            <a class="nav-link" href="../Customer-Subscription/create.php">Create</a>
                        </nav>
                    </div>
                    <!-- Visitor_Plans -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseVisitorPlans" aria-expanded="false" aria-controls="collapseVisitorPlans">
                        <div class="sb-nav-link-icon"><i class="fas fa-id-badge"></i></div>
                        Visitor_Plans
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseVisitorPlans" aria-labelledby="headingVisitorPlans" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../Visitor_Plans/index.php">Index</a>
                            <a class="nav-link" href="../Visitor_Plans/create.php">Create</a>
                        </nav>
                    </div>
                    <!-- Visitor_Passes -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseVisitorPasses" aria-expanded="false" aria-controls="collapseVisitorPasses">
                        <div class="sb-nav-link-icon"><i class="fas fa-ticket-alt"></i></div>
                        Visitor_Passes
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseVisitorPasses" aria-labelledby="headingVisitorPasses" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../Visitor_Passes/index.php">Index</a>
                        </nav>
                    </div>
                    <!-- Create Customers -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                        data-bs-target="#collapseCreateCustomers" aria-expanded="false" aria-controls="collapseCreateCustomers">
                        <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                        Create Customers
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>

                    <div class="collapse" id="collapseCreateCustomers" aria-labelledby="headingCreateCustomers" data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../Create-Customers/index.php">Index</a>
                            <a class="nav-link" href="../Create-Customers/create.php">Create</a>
                        </nav>
                    </div>
                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as:</div>
                <?php
                // Use name/fullname from session
                if (isset($_SESSION['name'])) {
                    echo htmlspecialchars($_SESSION['name']);
                } elseif (isset($_SESSION['fullname'])) {
                    echo htmlspecialchars($_SESSION['fullname']);
                } else {
                    echo "<span class='text-muted'>Guest</span>";
                }
                ?>
            </div>
        </nav>
    </div>
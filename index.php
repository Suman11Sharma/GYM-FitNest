<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FitNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/landing.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

</head>
<style>
    .footer-text {
        color: #fff !important;
    }
</style>

<body>
    <nav class="navbar navbar-expand-sm navbar-dark bg-primary">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand" href="#">
                <img src="uploads/logo.png" alt="Logo" height="30">
            </a>

            <!-- Toggler -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars text-white"></i>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <!-- Left Links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdownId" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Dropdown
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownId">
                            <li><a class="dropdown-item" href="superAdmin/login.php">SuperAdminPage</a></li>
                            <li><a class="dropdown-item" href="#">Action 2</a></li>
                        </ul>
                    </li>
                </ul>

                <!-- Search Form -->
                <form class="d-flex me-3" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>

                <!-- User Dropdown -->
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user fa-fw"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#!">Settings</a></li>
                            <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="login.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <header>
        <div class="hero">
            <div class="ads">
                <h1>
                    here the ads will run
                </h1>
            </div>
            <div class="aboutus">
                <h1>This will present the about us </h1>
            </div>
        </div>
    </header>

    <main>
        <div class="card_header">
            <h1>Recommendation</h1>
            <hr>
        </div>
        <div class="card-container">
            <div class="card">
                <div class="card_image">
                    <img src="assets/images/solution.jpg" alt="">
                </div>
                <div class="card-body">
                    <div class="card-subcontent">
                        <h2>Fancy Product</h2>
                        <span>Rs999</span>
                    </div>
                    <a href="">
                        <button class="btn-add-cart ">Add to cart</button>
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card_image">
                    <img src="assets/images/solution.jpg" alt="">
                </div>
                <div class="card-body">
                    <div class="card-subcontent">
                        <h2>Fancy Product</h2>
                        <span>Rs999</span>
                    </div>
                    <a href="">
                        <button class="btn-add-cart ">Add to cart</button>
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card_image">
                    <img src="" alt="">
                </div>
                <div class="card-body">
                    <div class="card-subcontent">
                        <h2>Fancy Product</h2>
                        <span>Rs999</span>
                    </div>
                    <a href="">
                        <button class="btn-add-cart ">Add to cart</button>
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card_image">
                    <img src="" alt="">
                </div>
                <div class="card-body">
                    <div class="card-subcontent">
                        <h2>Fancy Product</h2>
                        <span>Rs999</span>
                    </div>
                    <a href="">
                        <button class="btn-add-cart ">Add to cart</button>
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card_image">
                    <img src="" alt="">
                </div>
                <div class="card-body">
                    <div class="card-subcontent">
                        <h2>Fancy Product</h2>
                        <span>Rs999</span>
                    </div>
                    <a href="">
                        <button class="btn-add-cart ">Add to cart</button>
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card_image">
                    <img src="" alt="">
                </div>
                <div class="card-body">
                    <div class="card-subcontent">
                        <h2>Fancy Product</h2>
                        <span>Rs999</span>
                    </div>
                    <a href="">
                        <button class="btn-add-cart ">Add to cart</button>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="py-4 mt-auto">
        <div class="container-fluid px-4 ">
            <div class="d-flex justify-content-between footer-text">
                <span>Copyright &copy; Your Website 2023</span>
                <span>fitnest@gmail.com</span>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>

</html>
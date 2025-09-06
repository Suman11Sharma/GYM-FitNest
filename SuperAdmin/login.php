<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login - FitNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <style>
        /* Two-color diagonal background */
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: linear-gradient(135deg, #000 50%, #f0f0f0 50%);
        }

        /* Top bar for logo */
        .top-bar {
            display: flex;
            justify-content: flex-start;
            /* padding: 20px 40px; */
        }

        .logo img {
            height: 150px;
        }

        /* Centering login container */
        .login-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-grow: 1;
        }

        /* Login card */
        .card-login {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-our {
            color: #fff;
            background-color: #20677c;
            border: 1px solid #20677c;
            border-radius: 16px;
            padding: 0.375rem 0.75rem;
            font-size: 1.1rem;
            line-height: 1.5;
            text-align: center;
            display: inline-block;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            text-decoration: none;
            user-select: none;
        }

        .btn-our:hover {
            background-color: #1a5c6a;
            border-color: #175463;
            color: #fff;
            transform: scale(1.03);
        }

        .btn-our:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(32, 103, 124, 0.5);
        }

        .btn-our:active {
            background-color: #175463;
            border-color: #144c5a;
            box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.125);
            color: #fff;
        }

        .btn-our:disabled {
            background-color: #20677c;
            border-color: #20677c;
            color: #fff;
            opacity: 0.65;
            pointer-events: none;
        }


        footer {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <!-- Logo on top right -->
    <div class="top-bar">
        <div class="logo">
            <img src="../uploads/logo_transparent.png" alt="FitNest Logo">
        </div>
    </div>

    <div class="login-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="card shadow border-0 rounded-lg card-login p-4">
                        <div class="card-header d-flex justify-content-between align-items-center border-0">
                            <h3 class="text-center font-weight-light my-2">Welcome Back!</h3>
                            <a href="../index.php" class="btn btn-light btn-sm border" title="Back to Home">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="inputEmail" type="email"
                                        placeholder="name@example.com" />
                                    <label for="inputEmail">Email address</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="inputPassword" type="password"
                                        placeholder="Password" />
                                    <label for="inputPassword">Password</label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" id="inputRememberPassword" type="checkbox" />
                                    <label class="form-check-label" for="inputRememberPassword">Remember
                                        Password</label>
                                </div>
                                <div class="d-grid">
                                    <a class="btn btn-our" href="superAdminPage.php">Login</a>
                                </div>
                            </form>
                        </div>
                        <div class="card-footer text-center border-0">
                            <small><a href="register.php">Don't have an account? Sign up</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-3 mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted"> &copy; 2025 FitNest. All Rights Reserved.</div>
                <div>
                    <a href="#">Privacy Policy</a>
                    &middot;
                    <a href="#">Terms &amp; Conditions</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
</body>

</html>
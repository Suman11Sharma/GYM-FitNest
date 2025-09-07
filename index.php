<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FitNest</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/landing.css">
    <link rel="stylesheet" href="assets/css/contactus.css">
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
            <a class="navbar-brand" href="index.php">
                <img src="uploads/logo_transparent.png" alt="Logo">
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
                    <li class="nav-item navbar-content">
                        <a class="nav-link " href="#">
                            <i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item navbar-content">
                        <a class="nav-link" href="#recommendation">
                            <i class="fas fa-location-arrow"></i> Recommendation</a>
                    </li>
                    <li class="nav-item navbar-content">
                        <button type="button" class="btn btn-link nav-link" data-bs-toggle="modal" data-bs-target="#contactModal">
                            <i class="fas fa-envelope"></i> Contact Us
                        </button>
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
                            <li><a class="dropdown-item" href="login.php">Customer</a></li>
                            <li><a class="dropdown-item" href="Admin/adminPage.php">Admin</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="SuperAdmin/login.php">Super Admin</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <header>
        <div class="hero">
            <div class="ads">
                <div class="ad-slider">
                    <img src="uploads/gym-ads.png" alt="Ad 1" class="ad-img">
                    <img src="uploads/gym-ads (1).png" alt="Ad 2" class="ad-img">
                    <img src="uploads/gym-ad-3.jpg" alt="Ad 3" class="ad-img">
                </div>
            </div>
            <div class="aboutus">
                <div class="aboutus-card">
                    <h2>Features We Provide :</h2>
                    <ul>
                        <li>Membership Tracking</li>
                        <li>Real-time Attendance</li>
                        <li>Class Scheduling</li>
                        <li>Billing & Invoices</li>
                        <li>Staff Management</li>
                    </ul>
                    <p class="card-subtitle">"All-in-one gym control center."</p>
                </div>

                <div class="aboutus-card">
                    <h2>Why Choose Us?</h2>
                    <ul>
                        <li>Easy to Use</li>
                        <li>Cloud-Based Access</li>
                        <li>Customizable Plans</li>
                        <li>24/7 Support</li>
                        <li>Built for Nepali Gyms</li>
                    </ul>
                    <p class="card-subtitle">"Simple. Scalable. Localized."</p>
                </div>

                <div class="aboutus-card">
                    <h2>Ready to Launch</h2>
                    <ul>
                        <li>Quick Setup</li>
                        <li>Runs on Any Device</li>
                        <li>No Installation Needed</li>
                        <li>Secure & Fast</li>
                        <li>Launch in Minutes</li>
                    </ul>
                    <p class="card-subtitle">"Be live in no time, stress-free."</p>
                </div>

            </div>
        </div>
    </header>


    <!-- Contact Us Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-3" style="border-radius: 20px; overflow: hidden;">
                <div class="contact_inner" style="height: auto; border-radius: 0;">
                    <!-- Contact Form -->
                    <div class="contact_field">
                        <h3 class="contact_title">Contact Us</h3>
                        <p class="contact_subtitle">Feel free to contact us any time. We'll get back to you as soon as we can!</p>

                        <!-- Name -->
                        <input type="text" class="form-control" name="name" placeholder="Name" required />

                        <!-- Email -->
                        <input type="email" class="form-control" name="email" placeholder="Email" required />

                        <!-- Contact -->
                        <input type="tel" class="form-control" name="contact" placeholder="Contact Number" pattern="[0-9]{10}" required />
                        <!-- Subject -->
                        <input type="text" class="form-control" name="subject" placeholder="Subject" required />

                        <!-- Message -->
                        <textarea class="form-control message_box" name="message" placeholder="Message" rows="4" required></textarea>

                        <button class="btn-contact_form_submit">Send</button>
                    </div>

                    <!-- Contact Info -->
                    <div class="contact_info_sec">
                        <div>
                            <h4>Contact Info</h4>
                            <div class="info_single"><i class="fas fa-headset"></i><span>‪+977 9825160781‬</span></div>
                            <div class="info_single"><i class="fas fa-envelope-open-text"></i><span>fitnest@gmail.com</span></div>
                            <div class="info_single"><i class="fas fa-map-marked-alt"></i><span>Pokhara, Nepal</span></div>
                        </div>
                        <ul class="socil_item_inner">
                            <li><a href="#"><i class="fab fa-facebook-square"></i></a></li>
                            <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                            <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main>

        <!-- more detail model -->

        <div class="modal fade" id="gymDetailModal" tabindex="-1" aria-labelledby="gymDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="gymDetailModalLabel">Gym Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <h6>🏋️ FitNest Gym, Pokhara</h6>
                        <p>FitNest Gym offers a complete fitness solution with modern equipment, professional trainers, and various classes including strength training, cardio, Zumba, and yoga.</p>
                        <ul>
                            <li>Opening Hours: 5:00 AM – 9:00 PM</li>
                            <li>Location: Lakeside, Pokhara</li>
                            <li>Contact: +977 9825160781</li>
                            <li>Email: info@fitnest.com</li>
                        </ul>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- mode for visitor pass -->
        <div class="modal fade" id="getPassModal" tabindex="-1" aria-labelledby="getPassModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="getPassModalLabel">Get Your Gym Pass</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form id="gymPassForm" onsubmit="return validateForm(event)">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="passName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="passName" required>
                            </div>

                            <div class="mb-3">
                                <label for="passContact" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" id="passContact" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="dateFrom" class="form-label">From Date</label>
                                    <input type="date" class="form-control" id="dateFrom" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="dateTo" class="form-label">To Date</label>
                                    <input type="date" class="form-control" id="dateTo" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="timeSlot" class="form-label">Preferred Time</label>
                                <select class="form-select" id="timeSlot" required>
                                    <option selected disabled value="">Select Time</option>
                                    <option>Morning (5:00 AM – 9:00 AM)</option>
                                    <option>Day (10:00 AM – 2:00 PM)</option>
                                    <option>Evening (4:00 PM – 9:00 PM)</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="paymentMethod" class="form-label">Payment Method</label>
                                <select class="form-select" id="paymentMethod" required>
                                    <option selected disabled value="">Select Method</option>
                                    <option>eSewa</option>
                                    <option>Khalti</option>
                                    <option>Cash</option>
                                    <option>Bank Transfer</option>
                                </select>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-our">Submit Request</button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>



        <div class="card_header" id="recommendation">
            <h1>Recommendation</h1>
            <hr>
        </div>
        <div class="card-container">
            <div class="card custom-card">
                <div class="card-image">
                    <img src="uploads/gym.jpg" alt="Gym Name">
                </div>

                <div class="card-body card-body-custom">
                    <div>
                        <h5 class="card-title">Gym Name</h5>

                    </div>

                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary btn-cool w-100 " data-bs-toggle="modal" data-bs-target="#gymDetailModal">
                            More Detail
                        </a>
                        <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor" data-bs-toggle="modal" data-bs-target="#getPassModal">
                            Get Pass
                        </a>
                    </div>
                </div>
            </div>
            <div class="card custom-card">
                <div class="card-image">
                    <img src="uploads/gym.jpg" alt="Gym Name">
                </div>

                <div class="card-body card-body-custom">
                    <div>
                        <h5 class="card-title">Gym Name</h5>

                    </div>

                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary btn-cool w-100">More Detail</a>
                        <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor">Get Pass</a>
                    </div>
                </div>
            </div>
            <div class="card custom-card">
                <div class="card-image">
                    <img src="uploads/gym.jpg" alt="Gym Name">
                </div>

                <div class="card-body card-body-custom">
                    <div>
                        <h5 class="card-title">Gym Name</h5>

                    </div>

                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary btn-cool w-100">More Detail</a>
                        <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor">Get Pass</a>
                    </div>
                </div>
            </div>
            <div class="card custom-card">
                <div class="card-image">
                    <img src="uploads/gym.jpg" alt="Gym Name">
                </div>

                <div class="card-body card-body-custom">
                    <div>
                        <h5 class="card-title">Gym Name</h5>

                    </div>

                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary btn-cool w-100">More Detail</a>
                        <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor">Get Pass</a>
                    </div>
                </div>
            </div>
            <div class="card custom-card">
                <div class="card-image">
                    <img src="uploads/gym.jpg" alt="Gym Name">
                </div>

                <div class="card-body card-body-custom">
                    <div>
                        <h5 class="card-title">Gym Name</h5>

                    </div>

                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary btn-cool w-100">More Detail</a>
                        <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor">Get Pass</a>
                    </div>
                </div>
            </div>
            <div class="card custom-card">
                <div class="card-image">
                    <img src="uploads/gym.jpg" alt="Gym Name">
                </div>

                <div class="card-body card-body-custom">
                    <div>
                        <h5 class="card-title">Gym Name</h5>

                    </div>

                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary btn-cool w-100">More Detail</a>
                        <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor">Get Pass</a>
                    </div>
                </div>
            </div>
            <div class="card custom-card">
                <div class="card-image">
                    <img src="uploads/gym.jpg" alt="Gym Name">
                </div>

                <div class="card-body card-body-custom">
                    <div>
                        <h5 class="card-title">Gym Name</h5>

                    </div>

                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary btn-cool w-100">More Detail</a>
                        <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor">Get Pass</a>
                    </div>
                </div>
            </div>
            <div class="card custom-card">
                <div class="card-image">
                    <img src="uploads/gym.jpg" alt="Gym Name">
                </div>

                <div class="card-body card-body-custom">
                    <div>
                        <h5 class="card-title">Fitnest Gym</h5>

                    </div>

                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary btn-cool w-100">More Detail</a>
                        <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor">Get Pass</a>
                    </div>
                </div>
            </div>
            <div class="card custom-card">
                <div class="card-image">
                    <img src="uploads/gym.jpg" alt="Gym Name">
                </div>

                <div class="card-body card-body-custom">
                    <div>
                        <h5 class="card-title">Gym Name</h5>

                    </div>

                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary btn-cool w-100">More Detail</a>
                        <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor">Get Pass</a>
                    </div>
                </div>
            </div>
            <div class="card custom-card">
                <div class="card-image">
                    <img src="uploads/gym.jpg" alt="Gym Name">
                </div>

                <div class="card-body card-body-custom">
                    <div>
                        <h5 class="card-title">Gym Name</h5>

                    </div>

                    <div class="d-flex gap-2">
                        <a href="#" class="btn btn-outline-primary btn-cool w-100">More Detail</a>
                        <a href="#" class="btn btn-primary btn-cool w-100 btn-bgcolor">Get Pass</a>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="py-4 mt-auto">
        <div class="container-fluid px-4 ">
            <div class="d-flex justify-content-between footer-text">
                <span> &copy; 2025 FitNest. All Rights Reserved.</span>
                <span>fitnest@gmail.com</span>
            </div>
        </div>
    </footer>
    <script src="assets/js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>

</html>
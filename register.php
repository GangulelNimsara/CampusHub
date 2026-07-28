<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusHub Register</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
</head>

<body class="m-0 bg-white">

<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">

        <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center px-4 px-md-5 py-4 page-content">

            <div class="w-100" style="max-width:400px;">

                <div class="text-center mb-3">
                    <img src="assets/images/logo.png" width="130">
                </div>

                <h2 class="text-center fw-bold text-primary mb-3">
                    Register
                </h2>

                <div id="registerMessage"></div>

                <div class="mb-2">
                    <label class="form-label">Username</label>
                    <input type="text" id="username" class="form-control">
                </div>

                <div class="mb-2">
                    <label class="form-label">First Name</label>
                    <input type="text" id="firstName" class="form-control">
                </div>

                <div class="mb-2">
                    <label class="form-label">Last Name</label>
                    <input type="text" id="lastName" class="form-control">
                </div>

                <div class="mb-2">
                    <label class="form-label">Password</label>
                    <input type="password" id="password" class="form-control">
                </div>

                <div class="mb-2">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" id="confirmPassword" class="form-control">
                </div>

                <div class="mb-2">
                    <label class="form-label">Email</label>
                    <input type="email" id="email" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Mobile</label>
                    <input type="tel" id="mobile" class="form-control w-100">
                </div>

                <button class="btn btn-primary w-100 py-2 fw-bold" onclick="registerProcess();">
                    Create Account
                </button>

                <div class="text-center mt-3 d-lg-none">
                    Already have an account?
                    <a href="login.php" class="fw-bold text-decoration-none">
                        Sign In
                    </a>
                </div>

            </div>

        </div>

        <div class="d-none d-lg-block col-lg-6 position-relative image-section">

            <div class="split-bg"></div>

            <div class="position-absolute top-0 end-0 m-4">
                <a href="login.php" class="slide-arrow-btn d-inline-flex align-items-center gap-2 px-4 py-2 text-decoration-none">
                    <svg class="arrow-icon-left" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                    </svg>
                    <span class="small fw-semibold text-white">Sign In</span>
                </a>
            </div>

        </div>

    </div>
</div>

<script src="assets/js/bootstrap.bundle.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script src="assets/js/scripts.js"></script>

</body>
</html>
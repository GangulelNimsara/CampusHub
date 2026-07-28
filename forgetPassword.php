<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>CampusHub Reset Password</title>
</head>
<body class="m-0 bg-white">

<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">

        <div class="d-none d-lg-block col-lg-6 position-relative image-section">
            <div class="split-bg"></div>

            <div class="position-absolute top-0 start-0 m-4">
                <a href="login.php" class="slide-arrow-btn d-inline-flex align-items-center gap-2 px-4 py-2 text-decoration-none">
                    <svg class="arrow-icon-left" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                    </svg>
                    <span class="small fw-semibold text-white">Back to Login</span>
                </a>
            </div>
        </div>

        <div class="col-12 col-lg-6 d-flex align-items-center justify-content-center px-4 px-md-5 py-4 page-content bg-white">
            <div class="w-100" style="max-width: 400px;">

                <div class="text-center mb-3">
                    <img src="assets/images/logo.png" width="130">
                </div>

                <h2 class="text-center fw-bold text-primary mb-2">Forgot Password?</h2>
                <p class="text-center text-muted small mb-4">Enter your registered email address to receive a verification code.</p>

                <div id="forgotMessage" class="w-100"></div>

                <form id="forgotPasswordForm" onsubmit="event.preventDefault(); forgetPassword();">
                    <div class="mb-4">
                        <label for="email" class="form-label text-muted">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" id="forgetBtn">
                        <span id="btnText">Send Reset Code</span>
                        <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="login.php" class="small fw-bold text-decoration-none">Remembered your password? Login</a>
                </div>

            </div>
        </div>

    </div>
</div>

<script src="assets/js/bootstrap.bundle.js"></script>
<script src="assets/js/scripts.js"></script>
</body>
</html>
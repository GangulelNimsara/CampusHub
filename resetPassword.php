<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <title>CampusHub Reset Password</title>

</head>

<body class="m-0 bg-light d-flex align-items-center justify-content-center min-vh-100">

    <div class="card shadow-lg border-0 p-4 p-md-5" style="max-width: 520px; width: 100%; border-radius: 16px;">

        <div class="text-center mb-3">
            <img src="assets/images/logo.png" width="120" alt="Logo">
        </div>

        <h3 class="text-center fw-bold text-primary mb-2">Reset Password</h3>
        <p class="text-center text-muted small mb-4">Enter the 8-digit verification code sent to your email and your new password.</p>

        <div id="resetMessage" class="w-100"></div>

        <form id="resetPasswordForm" onsubmit="event.preventDefault(); resetPasswordProcess();">
            <div class="d-flex justify-content-center gap-2" id="otpInputs">

                <input type="text" class="form-control otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                <input type="text" class="form-control otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                <input type="text" class="form-control otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                <input type="text" class="form-control otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                <input type="text" class="form-control otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                <input type="text" class="form-control otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                <input type="text" class="form-control otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                <input type="text" class="form-control otp-input" maxlength="1" pattern="[0-9]" inputmode="numeric" required>

            </div>

            <div class="mb-3">
                <label for="newPassword" class="form-label text-muted small fw-semibold">New Password</label>
                <input type="password" id="newPassword" class="form-control" required>
            </div>

            <div class="mb-4">
                <label for="confirmPassword" class="form-label text-muted small fw-semibold">Confirm Password</label>
                <input type="password" id="confirmPassword" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm" onclick="resetPasswordProcess();">Update Password</button>
        </form>

        <div class="text-center mt-4">
            <a href="login.php" class="small fw-bold text-decoration-none">Back to Login</a>
        </div>

    </div>

    <script src="assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/scripts.js"></script>
</body>

</html>
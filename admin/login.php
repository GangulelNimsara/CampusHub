<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["admin"])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Campus Hub</title>

    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body class="bg-light d-flex align-items-center justify-content-center min-vh-100">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                <div class="card border-2 border-dark rounded-4 shadow-sm p-4 bg-white">
                    <div class="text-center mb-4">
                        <img src="../assets/images/logo.png" alt="Campus Hub" style="max-height: 60px;" class="mb-2">
                        <h4 class="fw-bold mb-1">Admin Portal 🔒</h4>
                        <p class="text-muted small">Enter your credentials to access the control panel.</p>
                    </div>

                    <form id="adminLoginForm">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Username or Email</label>
                            <input type="text" class="form-control rounded-3 border-dark" id="admin-username" name="username" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Password</label>
                            <input type="password" class="form-control rounded-3 border-dark" id="admin-password" name="password" required>
                        </div>

                        <button type="button" onclick="adminLoginProcess();" class="btn btn-dark w-100 rounded-pill fw-semibold py-2">
                            Login to Control Panel
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="../index.php" class="text-muted small text-decoration-none">&larr; Back to Student Portal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../assets/js/scripts.js"></script>

</body>

</html>
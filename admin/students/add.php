<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../../includes/db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $studentId = isset($_POST["studentId"]) ? trim($_POST["studentId"]) : "";
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
    $first_name = isset($_POST["first_name"]) ? trim($_POST["first_name"]) : "";
    $last_name = isset($_POST["last_name"]) ? trim($_POST["last_name"]) : "";
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $mobile = isset($_POST["mobile"]) ? trim($_POST["mobile"]) : "";
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

    if (empty($studentId) || empty($first_name) || empty($last_name) || empty($email) || empty($mobile) || empty($password)) {
        echo "Please fill in all required fields.";
        exit();
    }

    $escaped_sid = addslashes($studentId);
    $escaped_un = addslashes($username);
    $escaped_fn = addslashes($first_name);
    $escaped_ln = addslashes($last_name);
    $escaped_email = addslashes($email);
    $escaped_mobile = addslashes($mobile);
    $escaped_pwd = addslashes($password);
    $joined_date = date("Y-m-d");

    $emailCheck = Database::search("SELECT * FROM `users` WHERE `email` = '" . $escaped_email . "'");
    if ($emailCheck && $emailCheck->num_rows > 0) {
        echo "Email address is already registered.";
        exit();
    }

    Database::iud("INSERT INTO `users` (`status_id`, `studentId`, `username`, `first_name`, `last_name`, `email`, `mobile`, `password`, `joined_date`) 
                   VALUES ('1', '" . $escaped_sid . "', '" . $escaped_un . "', '" . $escaped_fn . "', '" . $escaped_ln . "', '" . $escaped_email . "', '" . $escaped_mobile . "', '" . $escaped_pwd . "', '" . $joined_date . "')");

    echo "success";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Admin Panel</title>

    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "../includes/navbar.php"; ?>

    <main class="container px-4 py-5 mt-5" style="max-width: 600px;">
        <div class="mb-4">
            <a href="index.php" class="text-dark small fw-semibold text-decoration-none">&larr; Back to Students List</a>
            <h2 class="fw-bold mb-0 mt-2">Add New Student 👤</h2>
        </div>

        <div class="card border-2 border-dark rounded-4 shadow-sm p-4 bg-white">
            <form id="addStudentForm">
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Student ID</label>
                        <input type="text" class="form-control rounded-3 border-dark" name="studentId" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Username</label>
                        <input type="text" class="form-control rounded-3 border-dark" name="username">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold">First Name</label>
                        <input type="text" class="form-control rounded-3 border-dark" name="first_name" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Last Name</label>
                        <input type="text" class="form-control rounded-3 border-dark" name="last_name" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Email Address</label>
                    <input type="email" class="form-control rounded-3 border-dark" name="email" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Mobile Number</label>
                    <input type="text" class="form-control rounded-3 border-dark" name="mobile" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Password</label>
                    <input type="password" class="form-control rounded-3 border-dark" name="password" required>
                </div>

                <button type="button" onclick="addStudentProcess();" class="btn btn-dark w-100 rounded-pill fw-semibold py-2">
                    Add Student Account
                </button>
            </form>
        </div>
    </main>

    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/js/scripts.js"></script>

</body>

</html>
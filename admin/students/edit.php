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
    $id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
    $studentId = isset($_POST["studentId"]) ? trim($_POST["studentId"]) : "";
    $username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
    $first_name = isset($_POST["first_name"]) ? trim($_POST["first_name"]) : "";
    $last_name = isset($_POST["last_name"]) ? trim($_POST["last_name"]) : "";
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $mobile = isset($_POST["mobile"]) ? trim($_POST["mobile"]) : "";
    $password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

    if ($id <= 0 || empty($studentId) || empty($first_name) || empty($last_name) || empty($email) || empty($mobile)) {
        echo "Please fill in all required fields.";
        exit();
    }

    $escaped_sid = addslashes($studentId);
    $escaped_un = addslashes($username);
    $escaped_fn = addslashes($first_name);
    $escaped_ln = addslashes($last_name);
    $escaped_email = addslashes($email);
    $escaped_mobile = addslashes($mobile);

    $emailCheck = Database::search("SELECT * FROM `users` WHERE `email` = '" . $escaped_email . "' AND `id` != '" . $id . "'");
    if ($emailCheck && $emailCheck->num_rows > 0) {
        echo "Email address is already in use by another student.";
        exit();
    }

    if (!empty($password)) {
        $escaped_pwd = addslashes($password);
        Database::iud("UPDATE `users` SET `studentId` = '" . $escaped_sid . "', `username` = '" . $escaped_un . "', `first_name` = '" . $escaped_fn . "', `last_name` = '" . $escaped_ln . "', `email` = '" . $escaped_email . "', `mobile` = '" . $escaped_mobile . "', `password` = '" . $escaped_pwd . "' WHERE `id` = '" . $id . "'");
    } else {
        Database::iud("UPDATE `users` SET `studentId` = '" . $escaped_sid . "', `username` = '" . $escaped_un . "', `first_name` = '" . $escaped_fn . "', `last_name` = '" . $escaped_ln . "', `email` = '" . $escaped_email . "', `mobile` = '" . $escaped_mobile . "' WHERE `id` = '" . $id . "'");
    }

    echo "success";
    exit();
}

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$studentQuery = Database::search("SELECT * FROM `users` WHERE `id` = '" . $id . "'");

if (!$studentQuery || $studentQuery->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$student = $studentQuery->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Admin Panel</title>

    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "../includes/navbar.php"; ?>

    <main class="container px-4 py-5 mt-5" style="max-width: 600px;">
        <div class="mb-4">
            <a href="index.php" class="text-dark small fw-semibold text-decoration-none">&larr; Back to Students List</a>
            <h2 class="fw-bold mb-0 mt-2">Edit Student Account</h2>
        </div>

        <div class="card border-2 border-dark rounded-4 shadow-sm p-4 bg-white">
            <form id="editStudentForm">
                <input type="hidden" name="id" value="<?php echo $student['id']; ?>">

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Student ID</label>
                        <input type="text" class="form-control rounded-3 border-dark" name="studentId" value="<?php echo htmlspecialchars($student['studentId'] ?? ''); ?>" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Username</label>
                        <input type="text" class="form-control rounded-3 border-dark" name="username" value="<?php echo htmlspecialchars($student['username'] ?? ''); ?>">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold">First Name</label>
                        <input type="text" class="form-control rounded-3 border-dark" name="first_name" value="<?php echo htmlspecialchars($student['first_name'] ?? ''); ?>" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Last Name</label>
                        <input type="text" class="form-control rounded-3 border-dark" name="last_name" value="<?php echo htmlspecialchars($student['last_name'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Email Address</label>
                    <input type="email" class="form-control rounded-3 border-dark" name="email" value="<?php echo htmlspecialchars($student['email'] ?? ''); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Mobile Number</label>
                    <input type="text" class="form-control rounded-3 border-dark" name="mobile" value="<?php echo htmlspecialchars($student['mobile'] ?? ''); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">New Password (leave blank to keep existing)</label>
                    <input type="password" class="form-control rounded-3 border-dark" name="password">
                </div>

                <button type="button" onclick="editStudentProcess();" class="btn btn-dark w-100 rounded-pill fw-semibold py-2">
                    Update Student Account
                </button>
            </form>
        </div>
    </main>

    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/js/scripts.js"></script>

</body>

</html>
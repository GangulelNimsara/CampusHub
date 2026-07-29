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
    $title = isset($_POST["title"]) ? trim($_POST["title"]) : "";
    $content = isset($_POST["content"]) ? trim($_POST["content"]) : "";

    if (empty($title) || empty($content)) {
        echo "Please fill in all required fields.";
        exit();
    }

    $admin_id = null;
    
    if (isset($_SESSION["admin"]["id"])) {
        $sessId = (int)$_SESSION["admin"]["id"];
        $userCheck = Database::search("SELECT `id` FROM `users` WHERE `id` = '" . $sessId . "'");
        if ($userCheck && $userCheck->num_rows > 0) {
            $admin_id = $sessId;
        }
    }

    if ($admin_id === null) {
        $userCheck = Database::search("SELECT `id` FROM `users` ORDER BY `id` ASC LIMIT 1");
        if ($userCheck && $userCheck->num_rows > 0) {
            $row = $userCheck->fetch_assoc();
            $admin_id = (int)$row['id'];
        }
    }

    if ($admin_id === null) {
        echo "No valid user account found in database for foreign key constraint.";
        exit();
    }

    $escaped_title = addslashes($title);
    $escaped_content = addslashes($content);

    Database::iud("INSERT INTO `announcements` (`admin_id`, `title`, `content`) VALUES ('" . $admin_id . "', '" . $escaped_title . "', '" . $escaped_content . "')");

    echo "success";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Announcement - Admin Panel</title>

    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "../includes/navbar.php"; ?>

    <main class="container px-4 py-5 mt-5" style="max-width: 600px;">
        <div class="mb-4">
            <a href="index.php" class="text-dark small fw-semibold text-decoration-none">&larr; Back to Announcements List</a>
            <h2 class="fw-bold mb-0 mt-2">Add Announcement 📢</h2>
        </div>

        <div class="card border-2 border-dark rounded-4 shadow-sm p-4 bg-white">
            <form id="addAnnouncementForm">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Title</label>
                    <input type="text" class="form-control rounded-3 border-dark" name="title" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Content</label>
                    <textarea class="form-control rounded-3 border-dark" name="content" rows="5" required></textarea>
                </div>

                <button type="button" onclick="addAnnouncementProcess();" class="btn btn-dark w-100 rounded-pill fw-semibold py-2">
                    Publish Announcement
                </button>
            </form>
        </div>
    </main>

    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/js/scripts.js"></script>

</body>

</html>
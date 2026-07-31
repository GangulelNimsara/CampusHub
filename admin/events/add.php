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
    $category_id = isset($_POST["category_id"]) ? (int)$_POST["category_id"] : 0;
    $description = isset($_POST["description"]) ? trim($_POST["description"]) : "";
    $event_date = isset($_POST["event_date"]) ? trim($_POST["event_date"]) : "";
    $venue = isset($_POST["venue"]) ? trim($_POST["venue"]) : "";

    if (empty($title) || $category_id <= 0 || empty($event_date) || empty($venue)) {
        echo "Please fill in all required fields.";
        exit();
    }

    $admin_id = 0;
    if (isset($_SESSION["admin"]["id"])) {
        $admin_id = (int)$_SESSION["admin"]["id"];
    } else {
        $adminCheck = Database::search("SELECT `id` FROM `admins` ORDER BY `id` ASC LIMIT 1");
        if ($adminCheck && $adminCheck->num_rows > 0) {
            $adminRow = $adminCheck->fetch_assoc();
            $admin_id = (int)$adminRow['id'];
        }
    }

    $db_banner_path = "";
    if (isset($_FILES["banner"]) && $_FILES["banner"]["error"] === UPLOAD_ERR_OK) {
        $file = $_FILES["banner"];
        $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
        $filename = "event_" . time() . "_" . uniqid() . "." . $ext;
        $target_dir = "../../uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        if (move_uploaded_file($file["tmp_name"], $target_dir . $filename)) {
            $db_banner_path = "uploads/" . $filename;
        }
    }

    $escaped_title = addslashes($title);
    $escaped_desc = addslashes($description);
    $escaped_date = addslashes($event_date);
    $escaped_venue = addslashes($venue);
    $escaped_banner = addslashes($db_banner_path);

    Database::iud("INSERT INTO `events` (`admin_id`, `catogaryId`, `title`, `description`, `event_date`, `venue`, `bannerPath`) 
                   VALUES ('" . $admin_id . "', '" . $category_id . "', '" . $escaped_title . "', '" . $escaped_desc . "', '" . $escaped_date . "', '" . $escaped_venue . "', '" . $escaped_banner . "')");

    echo "success";
    exit();
}

$categoriesQuery = Database::search("SELECT * FROM `categories` ORDER BY `id` ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event - Admin Panel</title>

    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "../includes/navbar.php"; ?>

    <main class="container px-4 py-5 mt-5" style="max-width: 600px;">
        <div class="mb-4">
            <a href="index.php" class="text-dark small fw-semibold text-decoration-none">&larr; Back to Events List</a>
            <h2 class="fw-bold mb-0 mt-2">Add New Event ➕</h2>
        </div>

        <div class="card border-2 border-dark rounded-4 shadow-sm p-4 bg-white">
            <form id="addEventForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Event Title</label>
                    <input type="text" class="form-control rounded-3 border-dark" name="title" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Category</label>
                    <select name="category_id" id="category_id" class="form-select rounded-3">
                        <option value="">Select Category</option>
                        <?php
                        $categoriesQuery = Database::search("SELECT * FROM `categories` ORDER BY `category` ASC");
                        if ($categoriesQuery && $categoriesQuery->num_rows > 0) {
                            while ($cat = $categoriesQuery->fetch_assoc()) {
                                $catName = $cat['category'] ?? $cat['name'] ?? $cat['title'] ?? 'Category ' . $cat['id'];
                                echo '<option value="' . htmlspecialchars($cat['id']) . '">' . htmlspecialchars($catName) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Date & Time</label>
                        <input type="datetime-local" class="form-control rounded-3 border-dark" name="event_date" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Venue</label>
                        <input type="text" class="form-control rounded-3 border-dark" name="venue" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Description</label>
                    <textarea class="form-control rounded-3 border-dark" name="description" rows="3"></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Event Banner Image</label>
                    <input type="file" class="form-control rounded-3 border-dark" name="banner" accept="image/*">
                </div>

                <button type="button" onclick="addEventProcess();" class="btn btn-dark w-100 rounded-pill fw-semibold py-2">
                    Create Event
                </button>
            </form>
        </div>
    </main>

    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/js/scripts.js"></script>

</body>

</html>
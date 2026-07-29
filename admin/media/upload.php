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

    if (!isset($_FILES["media_file"]) || $_FILES["media_file"]["error"] !== UPLOAD_ERR_OK) {
        echo "Please select a valid media file.";
        exit();
    }

    $file = $_FILES["media_file"];
    $allowed_images = ["image/jpeg", "image/png", "image/webp", "image/jpg"];
    $allowed_videos = ["video/mp4", "video/webm", "video/ogg", "video/quicktime"];
    
    $mime_type = $file["type"];
    $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    
    if (in_array($mime_type, $allowed_images)) {
        $sub_dir = "assets/images/";
    } elseif (in_array($mime_type, $allowed_videos) || in_array($ext, ['mp4', 'webm', 'ogg', 'mov'])) {
        $sub_dir = "assets/videos/";
    } else {
        echo "Only JPG, PNG, WEBP images, and MP4, WEBM, MOV videos are allowed.";
        exit();
    }

    $filename = "media_" . time() . "_" . uniqid() . "." . $ext;
    $target_dir = "../../" . $sub_dir;
    
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . $filename;
    $db_path = $sub_dir . $filename;

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        $escaped_title = addslashes($title);
        $escaped_path = addslashes($db_path);
        
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
            echo "No valid user account found in database.";
            exit();
        }

        Database::iud("INSERT INTO `gallery` (`admin_id`, `title`, `file_path`) VALUES ('" . $admin_id . "', '" . $escaped_title . "', '" . $escaped_path . "')");
        echo "success";
    } else {
        echo "Failed to upload file.";
    }
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Media - Admin Panel</title>

    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "../includes/navbar.php"; ?>

    <main class="container px-4 py-5 mt-5" style="max-width: 600px;">
        <div class="mb-4">
            <a href="index.php" class="text-dark small fw-semibold text-decoration-none">&larr; Back to Media Gallery</a>
            <h2 class="fw-bold mb-0 mt-2">Upload New Media 📤</h2>
        </div>

        <div class="card border-2 border-dark rounded-4 shadow-sm p-4 bg-white">
            <form id="uploadMediaForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label small fw-semibold">Title / Caption</label>
                    <input type="text" class="form-control rounded-3 border-dark" name="title" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Select File (Image or Video)</label>
                    <input type="file" class="form-control rounded-3 border-dark" name="media_file" accept="image/*,video/*" required>
                   
                </div>

                <button type="button" onclick="uploadMediaProcess();" class="btn btn-dark w-100 rounded-pill fw-semibold py-2">
                    Upload Media
                </button>
            </form>
        </div>
    </main>

    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/js/scripts.js"></script>

</body>

</html>
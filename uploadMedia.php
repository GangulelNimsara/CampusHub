<?php
include "includes/session.php";
include "includes/db.php";

$message = "";
$status = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $caption = $_POST["caption"] ?? "";
    $adminId = $_SESSION["user"]["id"] ?? 1;

    if (empty($caption) || !isset($_FILES["media_file"]) || $_FILES["media_file"]["error"] !== UPLOAD_ERR_OK) {
        $message = "Please select a valid image and enter a title.";
        $status = "error";
    } else {
        $file = $_FILES["media_file"];
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        $allowedExts = ["png", "jpg", "jpeg", "webp"];

        if (!in_array($ext, $allowedExts)) {
            $message = "Only PNG, JPG, JPEG, and WEBP formats are allowed.";
            $status = "error";
        } else {
            $targetDir = "assets/images/gallery/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileName = time() . "_" . uniqid() . "." . $ext;
            $filePath = $targetDir . $fileName;

            if (move_uploaded_file($file["tmp_name"], $filePath)) {
                Database::iud("INSERT INTO `gallery` (`admin_id`, `title`, `file_path`) VALUES ('" . $adminId . "', '" . $caption . "', '" . $filePath . "')");
                $message = "Media uploaded successfully!";
                $status = "success";
            } else {
                $message = "Failed to upload image file.";
                $status = "error";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Media - Campus Hub</title>

    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "includes/navbar.php"; ?>

    <main class="py-5 mt-4 min-vh-100">
        <div class="container-fluid px-4 px-md-5">

            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h3 class="fw-bold mb-0">Upload Gallery Media</h3>
                            <p class="text-muted small mb-0">Share photo memories with the campus community.</p>
                        </div>
                        <a href="gallery.php" class="btn btn-outline-dark btn-sm rounded-pill px-3">← Gallery</a>
                    </div>

                    <div class="card border-2 border-dark rounded-4 p-4 shadow-sm bg-white">
                        
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?php echo $status === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show rounded-3 small" role="alert">
                                <?php echo htmlspecialchars($message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="" enctype="multipart/form-data">

                            <!-- Image Selection -->
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Select Media File</label>
                                <div class="border border-2 border-dashed border-dark rounded-4 p-4 bg-light text-center">
                                    <div class="fs-1 mb-2">📸</div>
                                    <input type="file" id="gallery-file-input" name="media_file" class="form-control" accept="image/*" required>
                                    <p class="extra-small text-muted mt-2 mb-0">Supported formats: PNG, JPG, WEBP</p>
                                </div>
                            </div>

                            <!-- Caption / Title -->
                            <div class="mb-4">
                                <label class="form-label small fw-semibold">Caption / Title</label>
                                <input type="text" class="form-control rounded-3" id="media-caption" name="caption" placeholder="Short description of this photo" required>
                            </div>

                            <button type="submit" class="btn btn-primary rounded-pill w-100 fw-semibold">Upload Photo</button>

                        </form>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <script src="assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/scripts.js"></script>

</body>

</html>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../../includes/db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit();
}

$mediaQuery = Database::search("SELECT * FROM `gallery` ORDER BY `id` DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Gallery - Admin Panel</title>

    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "../includes/navbar.php"; ?>

    <main class="container-fluid px-4 py-5 mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Media Gallery 🖼️🎥</h2>
                <p class="text-muted small mb-0">Manage uploaded images and videos.</p>
            </div>
            <a href="upload.php" class="btn btn-dark rounded-pill px-4 fw-medium">
                <i class="bi bi-cloud-upload me-1"></i> Upload Media
            </a>
        </div>

        <div class="row g-4">
            <?php if ($mediaQuery && $mediaQuery->num_rows > 0): ?>
                <?php while ($item = $mediaQuery->fetch_assoc()): 
                    $filePath = "../../" . ltrim($item['file_path'], '/');
                    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                    $isVideo = in_array($ext, ['mp4', 'webm', 'ogg', 'mov']);
                ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card h-100 border-2 border-dark rounded-4 shadow-sm overflow-hidden bg-white">
                            <div class="ratio ratio-4x3 bg-black">
                                <?php if ($isVideo): ?>
                                    <video src="<?php echo htmlspecialchars($filePath); ?>" controls class="w-100 h-100 object-fit-cover"></video>
                                <?php else: ?>
                                    <img src="<?php echo htmlspecialchars($filePath); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="w-100 h-100 object-fit-cover">
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <h6 class="fw-bold mb-1 text-truncate"><?php echo htmlspecialchars($item['title']); ?></h6>
                                <p class="text-muted small mb-3"><?php echo htmlspecialchars($item['upload_date'] ?? ''); ?></p>
                                <div class="d-flex gap-2">
                                    <a href="<?php echo htmlspecialchars($filePath); ?>" download class="btn btn-dark btn-sm rounded-pill flex-fill">
                                        <i class="bi bi-download me-1"></i> Download
                                    </a>
                                    <button onclick="deleteMedia(<?php echo $item['id']; ?>);" class="btn btn-outline-danger btn-sm rounded-pill flex-fill">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-5">
                    No media items uploaded yet.
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/js/scripts.js"></script>

</body>

</html>
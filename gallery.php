<?php
include "includes/session.php";
include "includes/db.php";

$isLoggedIn = isset($_SESSION["user"]);

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 8;
$offset = ($page - 1) * $limit;

$totalQuery = Database::search("SELECT COUNT(*) AS `total` FROM `gallery`");
$totalResult = $totalQuery->fetch_assoc();
$totalImages = $totalResult['total'];
$totalPages = ceil($totalImages / $limit);

$galleryQuery = "SELECT * FROM `gallery` ORDER BY `id` DESC LIMIT {$limit} OFFSET {$offset}";
$galleryResult = Database::search($galleryQuery);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery - Campus Hub</title>

    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "includes/navbar.php"; ?>

    <main class="py-4 bg-light min-vh-100 dashboard-container">
        <div class="container">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h3 class="fw-bold mb-0">Campus Gallery</h3>
                    <p class="text-muted small mb-0">Highlights and media coverage from past campus activities.</p>
                </div>
                <div>
                    <?php if ($isLoggedIn): ?>
                        <a href="uploadMedia.php" class="btn btn-primary btn-sm rounded-pill px-3 fw-medium">+ Upload Media</a>
                        <a href="dashboard.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-medium ms-1">Dashboard</a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row g-3" id="gallery-container">
                <?php
                $galleryItemsList = array();
                if ($galleryResult && $galleryResult->num_rows > 0) {
                    $index = 0;
                    while ($image = $galleryResult->fetch_assoc()) {
                        $imagePath = !empty($image['file_path']) ? $image['file_path'] : (!empty($image['path']) ? $image['path'] : (!empty($image['imagePath']) ? $image['imagePath'] : (!empty($image['image_path']) ? $image['image_path'] : 'assets/images/defaultEvent.png')));
                        $title = !empty($image['title']) ? $image['title'] : 'Campus Memory';
                        $galleryItemsList[] = array('src' => $imagePath, 'title' => $title);
                        ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="card h-100 border-2 border-dark rounded-4 shadow-sm bg-white overflow-hidden cursor-pointer" 
                                 style="cursor: pointer;"
                                 onclick="openImagePreview(<?php echo $index; ?>)">
                                <img src="<?php echo htmlspecialchars($imagePath); ?>" class="card-img-top" alt="Gallery Image" style="height: 200px; object-fit: cover;">
                                
                                <div class="card-body p-2 text-center d-flex flex-column justify-content-center">
                                    <h6 class="fw-bold text-truncate mb-0 small"><?php echo htmlspecialchars($title); ?></h6>
                                </div>
                            </div>
                        </div>
                        <?php
                        $index++;
                    }
                } else {
                    ?>
                    <div class="col-12 text-center text-muted py-5">
                        No photos added to the gallery yet.
                    </div>
                    <?php
                }
                ?>
            </div>

            <?php if ($totalPages > 1): ?>
                <nav class="mt-4" id="pagination-nav">
                    <ul class="pagination pagination-sm justify-content-center mb-0">
                        <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link text-dark" href="?page=<?php echo $page - 1; ?>">Previous</a>
                        </li>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                <a class="page-link <?php echo ($page == $i) ? 'bg-dark border-dark text-white' : 'text-dark'; ?>" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                            <a class="page-link text-dark" href="?page=<?php echo $page + 1; ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </main>

    <!-- Image Preview Modal with Slider Arrows -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-2 border-dark rounded-4 overflow-hidden">
                <div class="modal-header border-bottom border-dark bg-light">
                    <h5 class="modal-title fw-bold" id="imagePreviewTitle">Image Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 text-center bg-dark position-relative d-flex align-items-center justify-content-center" style="min-height: 350px;">
                    <button class="btn btn-light border-2 border-dark rounded-circle position-absolute start-0 ms-3 shadow" onclick="prevImage()" style="z-index: 10;">❮</button>
                    <img id="imagePreviewSrc" src="" class="img-fluid w-100" style="max-height: 75vh; object-fit: contain;">
                    <button class="btn btn-light border-2 border-dark rounded-circle position-absolute end-0 me-3 shadow" onclick="nextImage()" style="z-index: 10;">❯</button>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.galleryItems = <?php echo json_encode($galleryItemsList); ?>;
    </script>
    <script src="assets/js/scripts.js"></script>

</body>

</html>
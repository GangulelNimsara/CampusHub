<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "includes/db.php";

$featuredEventsQuery = Database::search("SELECT `events`.*, `categories`.* 
                                          FROM `events` 
                                          LEFT JOIN `categories` ON `events`.`catogaryId` = `categories`.`id` 
                                          ORDER BY `events`.`id` DESC LIMIT 3");

$announcementsQuery = Database::search("SELECT * FROM `announcements` ORDER BY `id` DESC LIMIT 2");

$galleryQuery = Database::search("SELECT * FROM `gallery` ORDER BY `id` DESC LIMIT 4");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Campus Hub</title>

    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>

    <?php include "includes/navbar.php"; ?>

    <section class="py-5 bg-white border-bottom mt-5">
        <div class="container py-4 text-center pt-4">
            <h1 class="display-5 fw-bold mb-3" id="hero-title">Welcome to Campus Hub</h1>
            <p class="lead text-muted mx-auto section-header-wrapper mb-4" id="hero-subtitle">
                Your central hub for campus events, student clubs, announcements, and activity registrations.
            </p>
            <div class="d-flex justify-content-center gap-3">
                <?php if (isset($_SESSION["user"])): ?>
                    <a href="events.php" class="btn btn-primary btn-lg rounded-pill px-4 fw-medium">Explore Events</a>
                    <a href="dashboard.php" class="btn btn-dark btn-lg rounded-pill px-4 fw-medium">Go to Dashboard 📊</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary btn-lg rounded-pill px-4 fw-medium">Log In to Explore</a>
                    <a href="register.php" class="btn btn-outline-dark btn-lg rounded-pill px-4 fw-medium">Join CampusHub</a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <main class="py-5 bg-light min-vh-100">
        <div class="container">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h3 class="fw-bold mb-0">Upcoming Featured Events</h3>
                    <p class="text-muted small mb-0">Discover and register for activities happening across campus.</p>
                </div>
                <?php if (isset($_SESSION["user"])): ?>
                    <a href="events.php" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-medium">View All Events &rarr;</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-medium">Log In for All Events &rarr;</a>
                <?php endif; ?>
            </div>

            <div class="row g-4 mb-5" id="index-events-container">
                <?php
                if ($featuredEventsQuery && $featuredEventsQuery->num_rows > 0) {
                    while ($event = $featuredEventsQuery->fetch_assoc()) {
                        $categoryName = $event['name'] ?? $event['catogary_name'] ?? $event['category'] ?? '';
                        $bannerImage = !empty($event['bannerPath']) ? htmlspecialchars($event['bannerPath']) : 'assets/images/defaultEvent.png';
                ?>
                        <div class="col-md-4">
                            <div class="card h-100 border-2 border-dark rounded-4 shadow-sm bg-white overflow-hidden">
                                <img src="<?php echo $bannerImage; ?>" class="card-img-top border-bottom border-dark" alt="Event Banner" style="height: 180px; object-fit: cover;">
                                <div class="card-body d-flex flex-column">
                                    <?php if (!empty($categoryName)) { ?>
                                        <div class="mb-2">
                                            <span class="badge bg-primary text-white border border-dark rounded-pill px-3">
                                                <?php echo htmlspecialchars($categoryName); ?>
                                            </span>
                                        </div>
                                    <?php } ?>
                                    <h5 class="card-title fw-bold mb-2"><?php echo htmlspecialchars($event['title']); ?></h5>
                                    <p class="card-text text-muted small flex-grow-1">
                                        <?php echo htmlspecialchars(mb_strimwidth($event['description'] ?? '', 0, 90, '...')); ?>
                                    </p>
                                    <div class="small text-muted mb-3">
                                        <?php if (!empty($event['event_date'])) { ?>
                                            <div>📅 <?php echo htmlspecialchars($event['event_date']); ?></div>
                                        <?php } ?>
                                        <?php if (!empty($event['venue'])) { ?>
                                            <div>📍 <?php echo htmlspecialchars($event['venue']); ?></div>
                                        <?php } ?>
                                    </div>
                                    <?php if (isset($_SESSION["user"])): ?>
                                        <button onclick="registerForEvent(<?php echo $event['id']; ?>);" class="btn btn-dark rounded-pill w-100 fw-semibold">Register Now</button>
                                    <?php else: ?>
                                        <a href="login.php" class="btn btn-outline-dark rounded-pill w-100 fw-semibold">Log In to Register</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo '<div class="col-12 text-center text-muted py-5">No upcoming events found.</div>';
                }
                ?>
            </div>

            <div class="card border-2 border-dark rounded-4 p-4 shadow-sm bg-white mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h4 class="fw-bold mb-0">📢 Latest Announcements</h4>
                    <?php if (isset($_SESSION["user"])): ?>
                        <a href="announcements.php" class="btn btn-sm btn-link text-decoration-none fw-semibold">All Notices &rarr;</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-sm btn-link text-decoration-none fw-semibold">Log In for Notices &rarr;</a>
                    <?php endif; ?>
                </div>
                <div class="row g-3" id="index-announcements-container">
                    <?php
                    if ($announcementsQuery && $announcementsQuery->num_rows > 0) {
                        while ($announcement = $announcementsQuery->fetch_assoc()) {
                    ?>
                            <div class="col-md-6">
                                <div class="border border-2 border-dark rounded-3 p-3 bg-light h-100">
                                    <h6 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($announcement['title'] ?? 'Notice'); ?></h6>
                                    <p class="small text-muted mb-0"><?php echo htmlspecialchars($announcement['content'] ?? $announcement['description'] ?? ''); ?></p>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<div class="col-12 text-center text-muted py-3">No announcements available right now.</div>';
                    }
                    ?>
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <div>
                        <h3 class="fw-bold mb-0">📸 Campus Life & Highlights</h3>
                        <p class="text-muted small mb-0">Memorable moments captured from recent campus events.</p>
                    </div>
                    <a href="gallery.php" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-medium">View Full Gallery &rarr;</a>
                </div>

                <div class="row g-3">
                    <?php
                    $galleryItemsList = array();
                    if ($galleryQuery && $galleryQuery->num_rows > 0) {
                        $index = 0;
                        while ($galleryItem = $galleryQuery->fetch_assoc()) {
                            $imagePath = !empty($galleryItem['file_path']) ? $galleryItem['file_path'] : (!empty($galleryItem['path']) ? $galleryItem['path'] : (!empty($galleryItem['imagePath']) ? $galleryItem['imagePath'] : (!empty($galleryItem['image_path']) ? $galleryItem['image_path'] : 'assets/images/defaultEvent.png')));
                            $title = htmlspecialchars($galleryItem['title'] ?? 'Campus Memory');
                            $galleryItemsList[] = array('src' => $imagePath, 'title' => $title);
                    ?>
                            <div class="col-6 col-md-3">
                                <div class="card border-2 border-dark rounded-4 shadow-sm bg-white overflow-hidden h-100 cursor-pointer" 
                                     style="cursor: pointer;"
                                     onclick="openImagePreview(<?php echo $index; ?>)">
                                    <img src="<?php echo htmlspecialchars($imagePath); ?>" class="img-fluid w-100" alt="<?php echo $title; ?>" style="height: 160px; object-fit: cover;">
                                    <?php if (!empty($galleryItem['title'])) { ?>
                                        <div class="card-footer bg-white border-0 p-2 text-center">
                                            <span class="small fw-semibold text-dark text-truncate d-block"><?php echo $title; ?></span>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                    <?php
                            $index++;
                        }
                    } else {
                        echo '<div class="col-12 text-center text-muted py-4">No gallery images found.</div>';
                    }
                    ?>
                </div>
            </div>

        </div>
    </main>

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
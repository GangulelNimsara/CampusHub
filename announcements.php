<?php
include "includes/session.php";
include "includes/db.php";

$isLoggedIn = isset($_SESSION["user"]);

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// 1. THIS IS THE MISSING XML LOADING CODE
$xmlFile = "xml/announcements.xml";
$xmlAnnouncements = [];
if (file_exists($xmlFile)) {
    $xmlData = simplexml_load_file($xmlFile);
    if ($xmlData !== false) {
        $xmlAnnouncements = $xmlData->announcement;
    }
}

$totalQuery = Database::search("SELECT COUNT(*) AS `total` FROM `announcements`");
$totalResult = $totalQuery->fetch_assoc();
$totalAnnouncements = $totalResult['total'];
$totalPages = ceil($totalAnnouncements / $limit);

$announcementsQuery = "SELECT * FROM `announcements` ORDER BY `id` DESC LIMIT {$limit} OFFSET {$offset}";
$announcementsResult = Database::search($announcementsQuery);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - Campus Hub</title>

    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "includes/navbar.php"; ?>

    <main class="py-4 bg-light min-vh-100 dashboard-container">
        <div class="container">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h3 class="fw-bold mb-0">Announcements & Notices</h3>
                    <p class="text-muted small mb-0">Stay updated with official campus updates and notifications.</p>
                </div>
                <?php if ($isLoggedIn): ?>
                    <a href="dashboard.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-medium">Go to Dashboard</a>
                <?php endif; ?>
            </div>

            <div class="row g-3" id="announcements-container">
                
                <?php
                // 2. THIS IS THE MISSING XML DISPLAY LOOP
                if (!empty($xmlAnnouncements)) {
                    foreach ($xmlAnnouncements as $item) {
                        ?>
                        <div class="col-12">
                            <div class="card border-2 border-dark rounded-4 shadow-sm bg-white p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="fw-bold mb-0">📌 <?php echo htmlspecialchars($item->title); ?> (XML Feed)</h5>
                                    <?php if (!empty($item->date)): ?>
                                        <span class="badge bg-warning text-dark border border-dark rounded-pill extra-small">
                                            <?php echo htmlspecialchars($item->category); ?> - <?php echo htmlspecialchars($item->date); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-muted mb-0 small"><?php echo nl2br(htmlspecialchars($item->content)); ?></p>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>

                <?php
                // 3. YOUR ORIGINAL DATABASE DISPLAY LOOP
                if ($announcementsResult && $announcementsResult->num_rows > 0) {
                    while ($announcement = $announcementsResult->fetch_assoc()) {
                        $title = !empty($announcement['title']) ? $announcement['title'] : 'Notice';
                        $content = !empty($announcement['content']) ? $announcement['content'] : ($announcement['message'] ?? '');
                        $date = !empty($announcement['created_at']) ? $announcement['created_at'] : ($announcement['date'] ?? '');
                        ?>
                        <div class="col-12">
                            <div class="card border-2 border-dark rounded-4 shadow-sm bg-white p-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="fw-bold mb-0">🔔 <?php echo htmlspecialchars($title); ?></h5>
                                    <?php if (!empty($date)): ?>
                                        <span class="badge bg-light text-dark border border-dark rounded-pill extra-small">
                                            <?php echo htmlspecialchars($date); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-muted mb-0 small"><?php echo nl2br(htmlspecialchars($content)); ?></p>
                            </div>
                        </div>
                        <?php
                    }
                } elseif (empty($xmlAnnouncements)) {
                    // Only show empty message if BOTH XML and DB are empty
                    ?>
                    <div class="col-12 text-center text-muted py-5">
                        No announcements posted yet.
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
<?php include "includes/footer.php"; ?>
    <script src="assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/scripts.js"></script>

</body>

</html>
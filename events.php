<?php
include "includes/session.php";
include "includes/db.php";

$isLoggedIn = isset($_SESSION["user"]);
$userId = $isLoggedIn ? $_SESSION["user"]["id"] : 0;

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

$totalQuery = Database::search("SELECT COUNT(*) AS `total` FROM `events`");
$totalResult = $totalQuery->fetch_assoc();
$totalEvents = $totalResult['total'];
$totalPages = ceil($totalEvents / $limit);

$eventsQuery = "SELECT `events`.*, `events`.`id` AS `event_real_id`, `categories`.* 
                FROM `events` 
                INNER JOIN `categories` ON `events`.`catogaryId` = `categories`.`id` 
                ORDER BY `events`.`id` DESC 
                LIMIT {$limit} OFFSET {$offset}";

$eventsResult = Database::search($eventsQuery);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Events - Campus Hub</title>

    <link rel="icon" type="image/jpeg" href="assets/images/app-icon.jpg?v=<?php echo time(); ?>">
    <link rel="shortcut icon" type="image/jpeg" href="assets/images/app-icon.jpg?v=<?php echo time(); ?>">

    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "includes/navbar.php"; ?>

    <main class="py-4 bg-light min-vh-100 dashboard-container">
        <div class="container">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h3 class="fw-bold mb-0">Upcoming Events</h3>
                    <p class="text-muted small mb-0">Explore and register for upcoming campus events and activities.</p>
                </div>
                <?php if ($isLoggedIn): ?>
                    <a href="dashboard.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3 fw-medium">Go to Dashboard</a>
                <?php endif; ?>
            </div>

            <div class="row g-3" id="events-list-container">
                <?php
                if ($eventsResult && $eventsResult->num_rows > 0) {
                    while ($event = $eventsResult->fetch_assoc()) {
                        $categoryName = $event['name'] ?? $event['catogary_name'] ?? $event['category'] ?? $event['title'] ?? '';
                        $eventId = $event['event_real_id'];

                        $regStatus = 0;
                        if ($isLoggedIn) {
                            $checkReg = Database::search("SELECT `registartionStatus` FROM `registrations` WHERE `student_id` = '" . $userId . "' AND `event_id` = '" . $eventId . "'");
                            if ($checkReg && $checkReg->num_rows > 0) {
                                $regRow = $checkReg->fetch_assoc();
                                $regStatus = (int)($regRow['registartionStatus'] ?? 1);
                            }
                        }
                        ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-2 border-dark rounded-4 shadow-sm bg-white overflow-hidden">
                                <img src="<?php echo !empty($event['bannerPath']) ? htmlspecialchars($event['bannerPath']) : 'assets/images/defaultEvent.png'; ?>" class="card-img-top border-bottom border-2 border-dark" alt="Event Banner" style="height: 180px; object-fit: cover;">
                                
                                <div class="card-body d-flex flex-column p-3">
                                    <?php if (!empty($categoryName)): ?>
                                        <span class="badge bg-warning text-dark border border-dark rounded-pill mb-2 align-self-start px-3">
                                            <?php echo htmlspecialchars($categoryName); ?>
                                        </span>
                                    <?php endif; ?>

                                    <h5 class="fw-bold mb-2"><?php echo htmlspecialchars($event['title']); ?></h5>
                                    <p class="text-muted small mb-3 flex-grow-1"><?php echo htmlspecialchars($event['description']); ?></p>

                                    <div class="small text-secondary mb-3">
                                        <?php if (!empty($event['event_date'])): ?>
                                            <div>📅 <strong>Date & Time:</strong> <?php echo htmlspecialchars($event['event_date']); ?></div>
                                        <?php endif; ?>
                                        <?php if (!empty($event['venue'])): ?>
                                            <div>📍 <strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="pt-2 border-top">
                                        <?php if ($isLoggedIn): ?>
                                            <?php if ($regStatus === 2): ?>
                                                <button class="btn btn-success w-100 rounded-pill fw-semibold" disabled>
                                                    Registered ✓
                                                </button>
                                            <?php elseif ($regStatus === 1): ?>
                                                <button class="btn btn-secondary w-100 rounded-pill fw-semibold" disabled>
                                                    Pending Approval ⏳
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-primary w-100 rounded-pill fw-semibold" onclick="registerForEvent(<?php echo $eventId; ?>);">
                                                    Register Now 🎟️
                                                </button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <a href="login.php" class="btn btn-outline-dark w-100 rounded-pill fw-semibold">
                                                Login to Register 🔒
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="col-12 text-center text-muted py-5">
                        No upcoming events found.
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
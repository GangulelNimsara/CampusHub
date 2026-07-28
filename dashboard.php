<?php
include "includes/session.php";
include "includes/db.php";

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION["user"]["id"];

$userQuery = Database::search("SELECT * FROM `users` WHERE `id` = '" . $userId . "'");
$data = $userQuery->fetch_assoc();

$_SESSION["user"] = $data;

$registeredCountQuery = Database::search("SELECT COUNT(*) AS `count` FROM `registrations` WHERE `student_id` = '" . $userId . "'");
$registeredCount = 0;
if ($registeredCountQuery && $registeredCountQuery->num_rows > 0) {
    $row = $registeredCountQuery->fetch_assoc();
    $registeredCount = $row['count'];
}

$announcementsCountQuery = Database::search("SELECT COUNT(*) AS `count` FROM `announcements`");
$announcementsCount = 0;
if ($announcementsCountQuery && $announcementsCountQuery->num_rows > 0) {
    $row = $announcementsCountQuery->fetch_assoc();
    $announcementsCount = $row['count'];
}

$enrolledEventsQuery = Database::search("SELECT `registrations`.*, `events`.* 
                                          FROM `registrations` 
                                          INNER JOIN `events` ON `registrations`.`event_id` = `events`.`id` 
                                          WHERE `registrations`.`student_id` = '" . $userId . "' 
                                          ORDER BY `registrations`.`id` DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Campus Hub</title>

    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include "includes/navbar.php"; ?>

    <main class="py-5 mt-4 min-vh-100 dashboard-container">
        <div class="container-fluid px-4 px-md-5">

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    <h3 class="fw-bold mb-0">Welcome, <?php echo htmlspecialchars($data["first_name"]); ?>! 👋</h3>
                    <p class="text-muted small mb-0 d-flex align-items-center gap-2">
                        <span>Manage your enrolled events and campus activities.</span>
                        <?php if (!empty($data['studentId'])): ?>
                            <span class="badge bg-dark text-white rounded-pill px-3 py-2 fs-6 fw-semibold border border-dark shadow-sm">
                                ID: <?php echo htmlspecialchars($data['studentId']); ?>
                            </span>
                        <?php endif; ?>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-medium" data-bs-toggle="modal" data-bs-target="#dashboardFeedbackModal">💬 Send Feedback</button>
                    <a href="announcements.php" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-medium">🔔 Announcements</a>
                    <a href="events.php" class="btn btn-primary btn-sm rounded-pill px-3 fw-medium">+ Browse Events</a>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="card border-2 border-dark rounded-3 p-2 px-3 shadow-sm bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted extra-small fw-bold text-uppercase">Registered Events</span>
                                <h4 class="fw-bold mb-0 mt-1" id="stat-registered"><?php echo $registeredCount; ?></h4>
                            </div>
                            <div class="fs-3 text-primary">🎟️</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-2 border-dark rounded-3 p-2 px-3 shadow-sm bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted extra-small fw-bold text-uppercase">Announcements</span>
                                <h4 class="fw-bold mb-0 mt-1" id="stat-announcements"><?php echo $announcementsCount; ?></h4>
                            </div>
                            <div class="fs-3 text-warning">🔔</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-2 border-dark rounded-3 p-2 px-3 shadow-sm bg-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="text-muted extra-small fw-bold text-uppercase">Account Status</span>
                                <h4 class="fw-bold mb-0 mt-1">
                                    <?php 
                                    if ((isset($data["status_id"]) && $data["status_id"] == 1) || (isset($data["status"]) && $data["status"] == 1)) {
                                        echo "Active";
                                    } else {
                                        echo "Inactive";
                                    }
                                    ?>
                                </h4>
                            </div>
                            <div class="fs-3 text-info">
                                <?php 
                                if ((isset($data["status_id"]) && $data["status_id"] == 1) || (isset($data["status"]) && $data["status"] == 1)) {
                                    echo "🟢";
                                } else {
                                    echo "🔴";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 align-items-start">

                <div class="col-lg-7 col-xl-7">
                    <div class="card border-2 border-dark rounded-4 p-3 shadow-sm bg-white">
                        <h6 class="fw-bold mb-2">Your Enrolled Events</h6>

                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="small">
                                        <th>Event Name</th>
                                        <th>Date</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="small" id="enrolled-events-body">
                                    <?php
                                    if ($enrolledEventsQuery && $enrolledEventsQuery->num_rows > 0) {
                                        while ($row = $enrolledEventsQuery->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td class='fw-bold'>" . htmlspecialchars($row['title']) . "</td>";
                                            echo "<td class='text-muted'>" . htmlspecialchars($row['event_date']) . "</td>";
                                            echo "<td class='text-muted'>" . htmlspecialchars($row['venue']) . "</td>";
                                            echo "<td><span class='badge bg-success text-white rounded-pill px-3'>Registered</span></td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr>";
                                        echo "<td colspan='4' class='text-center text-muted py-3'>No registered events found. <a href='events.php' class='fw-bold text-primary'>Browse Events</a></td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <nav class="mt-3 d-none" id="pagination-nav">
                            <ul class="pagination pagination-sm justify-content-end mb-0" id="pagination-list"></ul>
                        </nav>
                    </div>
                </div>

                <div class="col-lg-5 col-xl-5">
                    <div class="card border-2 border-dark rounded-4 p-3 shadow-sm bg-white">
                        <h6 class="fw-bold text-center mb-2">Event Schedule</h6>
                        <div id="calendar" class="dashboard-calendar-compact"></div>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <div class="modal fade" id="dashboardFeedbackModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-2 border-dark rounded-4 overflow-hidden">
                <div class="modal-header border-bottom border-dark bg-light">
                    <h5 class="modal-title fw-bold">💬 General Feedback</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-white">
                    <p class="text-muted small mb-3">Share suggestions, report issues, or send general notes to the Campus Hub management team.</p>
                    <div class="mb-3">
                        <textarea id="dashboardFeedbackMessage" class="form-control rounded-3 border-dark" rows="4" placeholder="Enter your feedback here..."></textarea>
                    </div>
                    <div class="text-end">
                        <button type="button" onclick="submitDashboardFeedback();" class="btn btn-primary rounded-pill px-4 fw-semibold">Submit Feedback 🚀</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script src="assets/js/scripts.js"></script>

</body>

</html>
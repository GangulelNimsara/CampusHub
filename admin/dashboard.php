<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../includes/db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

$studentsCountQuery = Database::search("SELECT COUNT(*) AS total FROM `users`");
$totalStudents = $studentsCountQuery ? $studentsCountQuery->fetch_assoc()['total'] : 0;

$eventsCountQuery = Database::search("SELECT COUNT(*) AS total FROM `events`");
$totalEvents = $eventsCountQuery ? $eventsCountQuery->fetch_assoc()['total'] : 0;

$registrationsCountQuery = Database::search("SELECT COUNT(*) AS total FROM `registrations`");
$totalRegistrations = $registrationsCountQuery ? $registrationsCountQuery->fetch_assoc()['total'] : 0;

$feedbackCountQuery = Database::search("SELECT COUNT(*) AS total FROM `feedback`");
$totalFeedback = $feedbackCountQuery ? $feedbackCountQuery->fetch_assoc()['total'] : 0;

$userCol = "";
$eventCol = "";

$regColsQuery = Database::search("SHOW COLUMNS FROM `registrations`");
if ($regColsQuery) {
    while ($col = $regColsQuery->fetch_assoc()) {
        $field = $col['Field'];
        if (in_array($field, ['user_id', 'users_id', 'id_user', 'student_id', 'user_email', 'email'])) {
            $userCol = $field;
        }
        if (in_array($field, ['event_id', 'events_id', 'id_event'])) {
            $eventCol = $field;
        }
    }
}

$recentRegistrations = false;

if ($userCol && $eventCol) {
    $userJoinCond = ($userCol === 'user_email' || $userCol === 'email') 
        ? "`registrations`.`{$userCol}` = `users`.`email`" 
        : "`registrations`.`{$userCol}` = `users`.`id`";

    $eventJoinCond = "`registrations`.`{$eventCol}` = `events`.`id`";

    $recentRegistrations = Database::search("SELECT `registrations`.*, `users`.`first_name`, `users`.`last_name`, `events`.`title` 
        FROM `registrations` 
        LEFT JOIN `users` ON {$userJoinCond} 
        LEFT JOIN `events` ON {$eventJoinCond} 
        ORDER BY `registrations`.`id` DESC LIMIT 5");
} else {
    $recentRegistrations = Database::search("SELECT * FROM `registrations` ORDER BY `id` DESC LIMIT 5");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Campus Hub</title>

    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "includes/navbar.php"; ?>

    <main class="container-fluid px-4 py-5 mt-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Admin Dashboard ⚡</h2>
                <p class="text-muted small mb-0">Overview and system metrics for Campus Hub.</p>
            </div>
            <a href="students/add.php" class="btn btn-dark rounded-pill px-4 fw-medium">
                <i class="bi bi-person-plus me-1"></i> Add Student
            </a>
        </div>

        <div class="row g-4 mb-5">
            
            <div class="col-12 col-sm-6 col-xl-3">
                <a href="students/index.php" class="text-decoration-none text-dark">
                    <div class="p-4 bg-white rounded-4 shadow-sm border border-2 border-dark d-flex align-items-center justify-content-between h-100">
                        <div>
                            <span class="text-muted small fw-semibold d-block">TOTAL STUDENTS</span>
                            <h3 class="fw-bold mb-0 mt-1"><?php echo $totalStudents; ?></h3>
                        </div>
                        <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-people fs-4"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <a href="events/index.php" class="text-decoration-none text-dark">
                    <div class="p-4 bg-white rounded-4 shadow-sm border border-2 border-dark d-flex align-items-center justify-content-between h-100">
                        <div>
                            <span class="text-muted small fw-semibold d-block">ACTIVE EVENTS</span>
                            <h3 class="fw-bold mb-0 mt-1"><?php echo $totalEvents; ?></h3>
                        </div>
                        <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-calendar-event fs-4"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <a href="registrations/index.php" class="text-decoration-none text-dark">
                    <div class="p-4 bg-white rounded-4 shadow-sm border border-2 border-dark d-flex align-items-center justify-content-between h-100">
                        <div>
                            <span class="text-muted small fw-semibold d-block">REGISTRATIONS</span>
                            <h3 class="fw-bold mb-0 mt-1"><?php echo $totalRegistrations; ?></h3>
                        </div>
                        <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-ticket-perforated fs-4"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <a href="feedback/index.php" class="text-decoration-none text-dark">
                    <div class="p-4 bg-white rounded-4 shadow-sm border border-2 border-dark d-flex align-items-center justify-content-between h-100">
                        <div>
                            <span class="text-muted small fw-semibold d-block">FEEDBACK</span>
                            <h3 class="fw-bold mb-0 mt-1"><?php echo $totalFeedback; ?></h3>
                        </div>
                        <div class="rounded-circle bg-info bg-opacity-10 text-info p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="bi bi-chat-left-text fs-4"></i>
                        </div>
                    </div>
                </a>
            </div>

        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="p-4 bg-white rounded-4 shadow-sm border border-2 border-dark">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Recent Event Registrations</h5>
                        <a href="registrations/index.php" class="text-dark small fw-semibold text-decoration-none">View All &rarr;</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light border-bottom border-dark">
                                <tr>
                                    <th scope="col">Student</th>
                                    <th scope="col">Event Title</th>
                                    <th scope="col">Registration Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($recentRegistrations && $recentRegistrations->num_rows > 0): ?>
                                    <?php while ($row = $recentRegistrations->fetch_assoc()): ?>
                                        <tr>
                                            <td class="fw-semibold">
                                                <?php 
                                                    if (isset($row['first_name'])) {
                                                        echo htmlspecialchars(trim($row['first_name'] . ' ' . ($row['last_name'] ?? '')));
                                                    } else {
                                                        echo htmlspecialchars($row[$userCol] ?? 'N/A');
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    echo htmlspecialchars($row['title'] ?? $row[$eventCol] ?? 'N/A'); 
                                                ?>
                                            </td>
                                            <td class="text-muted small">
                                                <?php echo htmlspecialchars($row['registered_at'] ?? $row['created_at'] ?? $row['date'] ?? 'N/A'); ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">No recent registrations found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script src="../assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
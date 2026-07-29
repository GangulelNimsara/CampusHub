<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../../includes/db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit();
}

$registrationsQuery = Database::search("SELECT `registrations`.*, 
    `users`.`first_name`, 
    `users`.`last_name`, 
    `events`.`title` AS event_title, 
    `registrationstatus`.`status` AS status_name 
    FROM `registrations` 
    LEFT JOIN `users` ON `registrations`.`student_id` = `users`.`id` 
    LEFT JOIN `events` ON `registrations`.`event_id` = `events`.`id` 
    LEFT JOIN `registrationstatus` ON `registrations`.`registartionStatus` = `registrationstatus`.`id` 
    ORDER BY `registrations`.`id` DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Registrations - Admin Panel</title>

    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "../includes/navbar.php"; ?>

    <main class="container-fluid px-4 py-5 mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Event Registrations 🎟️</h2>
                <p class="text-muted small mb-0">Manage student event registrations and status updates.</p>
            </div>
        </div>

        <div class="card border-2 border-dark rounded-4 shadow-sm p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light border-bottom border-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Student Name</th>
                            <th scope="col">Event Title</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($registrationsQuery && $registrationsQuery->num_rows > 0): ?>
                            <?php while ($row = $registrationsQuery->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold">#<?php echo htmlspecialchars($row['id']); ?></td>
                                    <td>
                                        <?php 
                                            if (!empty($row['first_name'])) {
                                                echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']);
                                            } else {
                                                echo "Student #" . htmlspecialchars($row['student_id']);
                                            }
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($row['event_title'] ?? ('Event #' . $row['event_id'])); ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $statusId = $row['registartionStatus'] ?? 1;
                                            $statusName = $row['status_name'] ?? 'Pending';

                                            if ($statusId == 2) {
                                                echo '<span class="badge bg-success rounded-pill px-3 py-2">' . htmlspecialchars($statusName) . '</span>';
                                            } elseif ($statusId == 3) {
                                                echo '<span class="badge bg-danger rounded-pill px-3 py-2">' . htmlspecialchars($statusName) . '</span>';
                                            } else {
                                                echo '<span class="badge bg-warning text-dark rounded-pill px-3 py-2">' . htmlspecialchars($statusName) . '</span>';
                                            }
                                        ?>
                                    </td>
                                    <td class="text-muted small">
                                        <?php echo htmlspecialchars($row['registration_date'] ?? 'N/A'); ?>
                                    </td>
                                    <td class="text-end">
                                        <?php if (($row['registartionStatus'] ?? 1) != 2): ?>
                                            <button onclick="approveRegistration(<?php echo $row['id']; ?>);" class="btn btn-sm btn-outline-success rounded-pill px-3 me-1">
                                                <i class="bi bi-check-circle"></i> Approve
                                            </button>
                                        <?php endif; ?>
                                        <button onclick="deleteRegistration(<?php echo $row['id']; ?>);" class="btn btn-sm btn-danger rounded-pill px-3">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No event registrations found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/js/scripts.js"></script>

</body>

</html>
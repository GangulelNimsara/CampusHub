<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../../includes/db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit();
}

$eventsQuery = Database::search("SELECT `events`.*, `events`.`id` AS `event_id`, `categories`.* 
    FROM `events` 
    LEFT JOIN `categories` ON `events`.`catogaryId` = `categories`.`id` 
    ORDER BY `events`.`id` DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events - Admin Panel</title>

    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "../includes/navbar.php"; ?>

    <main class="container-fluid px-4 py-5 mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Manage Events 📅</h2>
                <p class="text-muted small mb-0">Create, update, or remove campus events.</p>
            </div>
            <a href="add.php" class="btn btn-dark rounded-pill px-4 fw-medium">
                <i class="bi bi-plus-circle me-1"></i> Add New Event
            </a>
        </div>

        <div class="card border-2 border-dark rounded-4 shadow-sm p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light border-bottom border-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Banner</th>
                            <th scope="col">Title</th>
                            <th scope="col">Category</th>
                            <th scope="col">Date</th>
                            <th scope="col">Venue</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($eventsQuery && $eventsQuery->num_rows > 0): ?>
                            <?php while ($event = $eventsQuery->fetch_assoc()): 
                                $eid = $event['event_id'];
                            ?>
                                <tr>
                                    <td class="fw-bold">#<?php echo htmlspecialchars($eid); ?></td>
                                    <td>
                                        <img src="../../<?php echo htmlspecialchars($event['bannerPath'] ?? 'assets/images/defaultEvent.png'); ?>" alt="Banner" class="rounded border border-dark" style="width: 50px; height: 35px; object-fit: cover;" onerror="this.src='../../assets/images/defaultEvent.png';">
                                    </td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($event['title'] ?? ''); ?></td>
                                    <td>
                                        <span class="badge bg-warning text-dark border border-dark rounded-pill px-2">
                                            <?php echo htmlspecialchars($event['name'] ?? $event['catogary_name'] ?? 'General'); ?>
                                        </span>
                                    </td>
                                    <td class="small text-muted"><?php echo htmlspecialchars($event['event_date'] ?? 'N/A'); ?></td>
                                    <td class="small text-muted"><?php echo htmlspecialchars($event['venue'] ?? 'N/A'); ?></td>
                                    <td class="text-end">
                                        <a href="edit.php?id=<?php echo $eid; ?>" class="btn btn-outline-dark btn-sm rounded-pill px-3 me-1">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <button onclick="deleteEvent(<?php echo $eid; ?>);" class="btn btn-danger btn-sm rounded-pill px-3">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No events found.</td>
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
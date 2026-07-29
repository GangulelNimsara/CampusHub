<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../../includes/db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit();
}

$announcementsQuery = Database::search("SELECT * FROM `announcements` ORDER BY `id` DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Announcements - Admin Panel</title>

    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "../includes/navbar.php"; ?>

    <main class="container-fluid px-4 py-5 mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Manage Announcements 📢</h2>
                <p class="text-muted small mb-0">Publish and manage campus announcements.</p>
            </div>
            <a href="add.php" class="btn btn-dark rounded-pill px-4 fw-medium">
                <i class="bi bi-plus-circle me-1"></i> Add Announcement
            </a>
        </div>

        <div class="card border-2 border-dark rounded-4 shadow-sm p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light border-bottom border-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Title</th>
                            <th scope="col">Content</th>
                            <th scope="col">Created At</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($announcementsQuery && $announcementsQuery->num_rows > 0): ?>
                            <?php while ($row = $announcementsQuery->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold">#<?php echo htmlspecialchars($row['id']); ?></td>
                                    <td class="fw-bold"><?php echo htmlspecialchars($row['title'] ?? ''); ?></td>
                                    <td class="small text-muted" style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <?php echo htmlspecialchars($row['content'] ?? ''); ?>
                                    </td>
                                    <td class="small text-muted"><?php echo htmlspecialchars($row['created_at'] ?? 'N/A'); ?></td>
                                    <td class="text-end">
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-dark btn-sm rounded-pill px-3 me-1">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <button onclick="deleteAnnouncement(<?php echo $row['id']; ?>);" class="btn btn-danger btn-sm rounded-pill px-3">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No announcements found.</td>
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
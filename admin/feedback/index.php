<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../../includes/db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit();
}

$feedbackQuery = Database::search("SELECT `feedback`.*, `users`.`first_name`, `users`.`last_name`, `users`.`email` 
    FROM `feedback` 
    LEFT JOIN `users` ON `feedback`.`user_id` = `users`.`id` 
    ORDER BY `feedback`.`id` DESC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Feedback - Admin Panel</title>

    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "../includes/navbar.php"; ?>

    <main class="container-fluid px-4 py-5 mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Student Feedback 💬</h2>
                <p class="text-muted small mb-0">Review suggestions and messages submitted by students.</p>
            </div>
        </div>

        <div class="card border-2 border-dark rounded-4 shadow-sm p-4 bg-white">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light border-bottom border-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Student Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Feedback / Message</th>
                            <th scope="col">Submitted Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($feedbackQuery && $feedbackQuery->num_rows > 0): ?>
                            <?php while ($row = $feedbackQuery->fetch_assoc()): ?>
                                <tr>
                                    <td class="fw-bold">#<?php echo htmlspecialchars($row['id']); ?></td>
                                    <td class="fw-bold">
                                        <?php 
                                            if (isset($row['first_name'])) {
                                                echo htmlspecialchars(trim($row['first_name'] . ' ' . ($row['last_name'] ?? '')));
                                            } else {
                                                echo 'Student';
                                            }
                                        ?>
                                    </td>
                                    <td class="small text-muted"><?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($row['message'] ?? ''); ?></td>
                                    <td class="small text-muted"><?php echo htmlspecialchars($row['submitted_at'] ?? 'N/A'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No student feedback found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="../../assets/js/scripts.js"></script>

</body>

</html>
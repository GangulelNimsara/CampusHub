<?php
include "includes/session.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Registrations - Campus Hub</title>

    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "includes/navbar.php"; ?>

    <main class="py-5 mt-4 min-vh-100">
        <div class="container-fluid px-4 px-md-5">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h3 class="fw-bold mb-0">My Event Registrations</h3>
                    <p class="text-muted small mb-0">Track your registered events and access your event passes.</p>
                </div>
                <a href="events.php" class="btn btn-primary btn-sm rounded-pill px-3 fw-medium">+ Browse Events</a>
            </div>

            <div class="card border-2 border-dark rounded-4 p-3 p-md-4 shadow-sm bg-white">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="small text-uppercase">
                                <th>Ticket ID</th>
                                <th>Event Name</th>
                                <th>Date & Time</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody id="registrations-table-body">
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Loading your registrations...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

    <script src="assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/scripts.js"></script>

</body>

</html>
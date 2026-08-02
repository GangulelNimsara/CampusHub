<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for Event - Campus Hub</title>

    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "includes/navbar.php"; ?>

    <main class="py-5 mt-4 min-vh-100">
        <div class="container-fluid px-4 px-md-5">

            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h3 class="fw-bold mb-0">Event Registration</h3>
                            <p class="text-muted small mb-0">Complete your registration to secure a slot for this campus event.</p>
                        </div>
                        <a href="events.php" class="btn btn-outline-dark btn-sm rounded-pill px-3">← Back to Events</a>
                    </div>

                    <div class="card border-2 border-dark rounded-4 p-3 mb-4 shadow-sm bg-white">
                        <div class="row align-items-center g-3">
                            <div class="col-12 col-md-4">
                                <img src="assets/images/default-event.jpg" id="event-preview-img" class="img-fluid rounded-3 object-fit-cover w-100" style="max-height: 140px;" alt="Event Banner">
                            </div>
                            <div class="col-12 col-md-8">
                                <span class="badge bg-primary rounded-pill px-3 py-1 mb-2" id="event-preview-category">Technology & IT</span>
                                <h4 class="fw-bold text-dark mb-1" id="event-preview-title">Annual Campus Hackathon 2026</h4>
                                <p class="text-muted small mb-2" id="event-preview-meta">📅 Aug 15, 2026 | ⏰ 09:00 AM | 📍 Main Auditorium</p>
                                <p class="extra-small text-secondary mb-0" id="event-preview-organizer">Organized by: <strong>Campus Admin</strong></p>
                            </div>
                        </div>
                    </div>

                    <div class="card border-2 border-dark rounded-4 p-4 shadow-sm bg-white">
                        <form id="studentEventRegisterForm">
                            <input type="hidden" id="event-id" name="event_id" value="<?php echo $_GET['id'] ?? ''; ?>">

                            <h5 class="fw-bold mb-3">Participant Information</h5>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Full Name</label>
                                    <input type="text" class="form-control rounded-3 bg-light" id="reg-name" name="full_name" value="<?php echo htmlspecialchars(($_SESSION['user']['first_name'] ?? '') . ' ' . ($_SESSION['user']['last_name'] ?? '')); ?>" readonly>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Email Address</label>
                                    <input type="email" class="form-control rounded-3 bg-light" id="reg-email" name="email" value="<?php echo htmlspecialchars($_SESSION['user']['email'] ?? ''); ?>" readonly>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Mobile Number</label>
                                    <input type="text" class="form-control rounded-3" id="reg-mobile" name="mobile" value="<?php echo htmlspecialchars($_SESSION['user']['mobile'] ?? ''); ?>" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small fw-semibold">Student / Index ID</label>
                                    <input type="text" class="form-control rounded-3" id="reg-student-id" name="student_id" placeholder="e.g. IT202688" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-semibold">Special Requirements / Notes <span class="text-muted fw-normal">(Optional)</span></label>
                                <textarea class="form-control rounded-3" id="reg-notes" name="notes" rows="3" placeholder="Dietary preferences, accessibility needs, or team details..."></textarea>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-semibold">Confirm Registration 🎟️</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <script src="assets/js/bootstrap.bundle.js"></script>
    <script src="assets/js/scripts.js"></script>

</body>

</html>
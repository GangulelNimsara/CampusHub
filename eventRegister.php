<?php
include "includes/session.php";
include "includes/db.php";

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION["user"];
$eventId = $_GET["event_id"] ?? $_GET["id"] ?? null;

if (!$eventId || !is_numeric($eventId)) {
    header("Location: events.php");
    exit();
}

$eventQuery = Database::search("SELECT `events`.*, `categories`.* 
                                FROM `events` 
                                LEFT JOIN `categories` ON `events`.`catogaryId` = `categories`.`id` 
                                WHERE `events`.`id` = '" . $eventId . "'");

if (!$eventQuery || $eventQuery->num_rows === 0) {
    header("Location: events.php");
    exit();
}

$event = $eventQuery->fetch_assoc();
$categoryName = $event['name'] ?? $event['catogary_name'] ?? $event['category'] ?? '';
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

    <main class="py-5 mt-5 min-vh-100">
        <div class="container pt-3">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h3 class="fw-bold mb-0">Event Registration</h3>
                    <p class="text-muted small mb-0">Complete your registration to secure a slot for this campus event.</p>
                </div>
                <a href="events.php" class="btn btn-outline-dark btn-sm rounded-pill px-3">← Back to Events</a>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <div class="card border-2 border-dark rounded-4 p-3 shadow-sm bg-white mb-4">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-4">
                                <img src="<?php echo !empty($event['bannerPath']) ? htmlspecialchars($event['bannerPath']) : 'assets/images/defaultEvent.png'; ?>"
                                    class="img-fluid rounded-3 border border-dark w-100"
                                    alt="Event Banner"
                                    style="height: 140px; object-fit: cover;">
                            </div>
                            <div class="col-md-8">
                                <?php if (!empty($categoryName)): ?>
                                    <span class="badge bg-primary text-white border border-dark rounded-pill mb-2 px-3">
                                        <?php echo htmlspecialchars($categoryName); ?>
                                    </span>
                                <?php endif; ?>

                                <h4 class="fw-bold mb-2"><?php echo htmlspecialchars($event['title']); ?></h4>

                                <div class="small text-muted mb-0">
                                    <?php if (!empty($event['event_date'])): ?>
                                        <span class="me-3">📅 <?php echo htmlspecialchars($event['event_date']); ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($event['venue'])): ?>
                                        <span>📍 <?php echo htmlspecialchars($event['venue']); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-2 border-dark rounded-4 p-4 shadow-sm bg-white mb-4">
                        <h5 class="fw-bold mb-3">Participant Information</h5>

                        <form id="registrationMenuForm">
                            <input type="hidden" name="event_id" value="<?php echo htmlspecialchars($event['id']); ?>">

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted mb-1">Full Name</label>
                                    <div class="fw-bold text-dark fs-6 bg-light p-2 px-3 rounded-3 border border-dark">
                                        <?php echo htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted mb-1">Email Address</label>
                                    <div class="fw-bold text-dark fs-6 bg-light p-2 px-3 rounded-3 border border-dark">
                                        <?php echo htmlspecialchars($user['email'] ?? ''); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted mb-1">Mobile Number</label>
                                    <div class="fw-bold text-dark fs-6 bg-light p-2 px-3 rounded-3 border border-dark">
                                        <?php echo htmlspecialchars($user['mobile'] ?? ''); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-muted mb-1">Student / Index ID</label>
                                    <div class="fw-bold text-dark fs-6 bg-light p-2 px-3 rounded-3 border border-dark">
                                        <?php echo htmlspecialchars($user['studentId'] ?? ''); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-semibold">Special Requirements / Notes <span class="text-muted fw-normal">(Optional)</span></label>
                                <textarea name="notes" class="form-control rounded-3 border-dark" rows="3" placeholder="Dietary preferences, accessibility needs, or team details..."></textarea>
                            </div>

                            <div class="text-end">
                                <button type="button" onclick="registerEvent();" class="btn btn-primary rounded-pill px-5 fw-semibold">Confirm Registration 🎟️</button>
                            </div>
                        </form>
                    </div>

                    <div class="card border-2 border-dark rounded-4 p-4 shadow-sm bg-white">
                        <h5 class="fw-bold mb-2">💬 Event Feedback & Suggestions</h5>
                        <p class="text-muted small mb-3">Have a question or feedback about this event? Send a message directly to the administrators.</p>

                        <form id="eventFeedbackForm">
                            <div class="mb-3">
                                <textarea id="eventFeedbackMessage" class="form-control rounded-3 border-dark" rows="3" placeholder="Write your thoughts or queries..."></textarea>
                            </div>
                            <div class="text-end">
                                <button type="button" onclick="submitEventFeedback();" class="btn btn-outline-dark rounded-pill px-4 fw-semibold">Send Feedback 🚀</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </main>
    <?php include "includes/footer.php"; ?>
    <script src="assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/scripts.js"></script>

</body>

</html>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../../includes/db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;
    $title = isset($_POST["title"]) ? trim($_POST["title"]) : "";
    $category_id = isset($_POST["category_id"]) ? (int)$_POST["category_id"] : 0;
    $description = isset($_POST["description"]) ? trim($_POST["description"]) : "";
    $event_date = isset($_POST["event_date"]) ? trim($_POST["event_date"]) : "";
    $venue = isset($_POST["venue"]) ? trim($_POST["venue"]) : "";

    if ($id <= 0 || empty($title) || $category_id <= 0 || empty($event_date) || empty($venue)) {
        echo "Please fill in all required fields.";
        exit();
    }

    $escaped_title = addslashes($title);
    $escaped_desc = addslashes($description);
    $escaped_date = addslashes($event_date);
    $escaped_venue = addslashes($venue);

    if (isset($_FILES["banner"]) && $_FILES["banner"]["error"] === UPLOAD_ERR_OK) {
        $file = $_FILES["banner"];
        $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
        $filename = "event_" . time() . "_" . uniqid() . "." . $ext;
        $target_dir = "../../uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        if (move_uploaded_file($file["tmp_name"], $target_dir . $filename)) {
            $db_banner_path = addslashes("uploads/" . $filename);
            Database::iud("UPDATE `events` SET `bannerPath` = '" . $db_banner_path . "' WHERE `id` = '" . $id . "'");
        }
    }

    Database::iud("UPDATE `events` SET `catogaryId` = '" . $category_id . "', `title` = '" . $escaped_title . "', `description` = '" . $escaped_desc . "', `event_date` = '" . $escaped_date . "', `venue` = '" . $escaped_venue . "' WHERE `id` = '" . $id . "'");

    echo "success";
    exit();
}

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$eventQuery = Database::search("SELECT * FROM `events` WHERE `id` = '" . $id . "'");

if (!$eventQuery || $eventQuery->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$event = $eventQuery->fetch_assoc();
$categoriesQuery = Database::search("SELECT * FROM `categories` ORDER BY `id` ASC");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event - Admin Panel</title>

    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "../includes/navbar.php"; ?>

    <main class="container px-4 py-5 mt-5" style="max-width: 600px;">
        <div class="mb-4">
            <a href="index.php" class="text-dark small fw-semibold text-decoration-none">&larr; Back to Events List</a>
            <h2 class="fw-bold mb-0 mt-2">Edit Event Details ✏️</h2>
        </div>

        <div class="card border-2 border-dark rounded-4 shadow-sm p-4 bg-white">
            <form id="editEventForm" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $event['id']; ?>">

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Event Title</label>
                    <input type="text" class="form-control rounded-3 border-dark" name="title" value="<?php echo htmlspecialchars($event['title']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Category</label>
                    <select class="form-select rounded-3 border-dark" name="category_id" required>
                        <?php if ($categoriesQuery && $categoriesQuery->num_rows > 0): ?>
                            <?php while ($cat = $categoriesQuery->fetch_assoc()): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo ($event['catogaryId'] == $cat['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['name'] ?? $cat['catogary_name'] ?? $cat['category'] ?? ('Category #' . $cat['id'])); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Date & Time</label>
                        <input type="datetime-local" class="form-control rounded-3 border-dark" name="event_date" value="<?php echo date('Y-m-d\TH:i', strtotime($event['event_date'])); ?>" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-semibold">Venue</label>
                        <input type="text" class="form-control rounded-3 border-dark" name="venue" value="<?php echo htmlspecialchars($event['venue']); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Description</label>
                    <textarea class="form-control rounded-3 border-dark" name="description" rows="3"><?php echo htmlspecialchars($event['description']); ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Change Banner Image (Optional)</label>
                    <input type="file" class="form-control rounded-3 border-dark" name="banner" accept="image/*">
                </div>

                <button type="button" onclick="editEventProcess();" class="btn btn-dark w-100 rounded-pill fw-semibold py-2">
                    Update Event
                </button>
            </form>
        </div>
    </main>

    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/js/scripts.js"></script>

</body>

</html>
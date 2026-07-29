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
    $content = isset($_POST["content"]) ? trim($_POST["content"]) : "";

    if ($id <= 0 || empty($title) || empty($content)) {
        echo "Please fill in all required fields.";
        exit();
    }

    $escaped_title = addslashes($title);
    $escaped_content = addslashes($content);

    Database::iud("UPDATE `announcements` SET `title` = '" . $escaped_title . "', `content` = '" . $escaped_content . "' WHERE `id` = '" . $id . "'");

    echo "success";
    exit();
}

$id = isset($_GET["id"]) ? (int)$_GET["id"] : 0;
$query = Database::search("SELECT * FROM `announcements` WHERE `id` = '" . $id . "'");

if (!$query || $query->num_rows === 0) {
    header("Location: index.php");
    exit();
}

$announcement = $query->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Announcement - Admin Panel</title>

    <link rel="stylesheet" href="../../assets/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/styles.css">
</head>

<body class="bg-light">

    <?php include "../includes/navbar.php"; ?>

    <main class="container px-4 py-5 mt-5" style="max-width: 600px;">
        <div class="mb-4">
            <a href="index.php" class="text-dark small fw-semibold text-decoration-none">&larr; Back to Announcements List</a>
            <h2 class="fw-bold mb-0 mt-2">Edit Announcement ✏️</h2>
        </div>

        <div class="card border-2 border-dark rounded-4 shadow-sm p-4 bg-white">
            <form id="editAnnouncementForm">
                <input type="hidden" name="id" value="<?php echo $announcement['id']; ?>">

                <div class="mb-3">
                    <label class="form-label small fw-semibold">Title</label>
                    <input type="text" class="form-control rounded-3 border-dark" name="title" value="<?php echo htmlspecialchars($announcement['title'] ?? ''); ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-semibold">Content</label>
                    <textarea class="form-control rounded-3 border-dark" name="content" rows="5" required><?php echo htmlspecialchars($announcement['content'] ?? ''); ?></textarea>
                </div>

                <button type="button" onclick="editAnnouncementProcess();" class="btn btn-dark w-100 rounded-pill fw-semibold py-2">
                    Update Announcement
                </button>
            </form>
        </div>
    </main>

    <script src="../../assets/js/bootstrap.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../../assets/js/scripts.js"></script>

</body>

</html>
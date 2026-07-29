<?php
include "includes/session.php";
include "includes/db.php";

if (!isset($_SESSION["user"])) {
    echo "login_required";
    exit();
}

$userId = $_SESSION["user"]["id"];
$firstName = isset($_POST["first_name"]) ? trim($_POST["first_name"]) : "";
$lastName = isset($_POST["last_name"]) ? trim($_POST["last_name"]) : "";
$mobile = isset($_POST["mobile"]) ? trim($_POST["mobile"]) : "";
$newPassword = isset($_POST["new_password"]) ? trim($_POST["new_password"]) : "";

$userQuery = Database::search("SELECT * FROM `users` WHERE `id` = '" . $userId . "'");
if (!$userQuery || $userQuery->num_rows === 0) {
    echo "User not found.";
    exit();
}

$updateFields = array();

if (!empty($firstName)) {
    $updateFields[] = "`first_name` = '" . addslashes($firstName) . "'";
}
if (!empty($lastName)) {
    $updateFields[] = "`last_name` = '" . addslashes($lastName) . "'";
}
if (!empty($mobile)) {
    $updateFields[] = "`mobile` = '" . addslashes($mobile) . "'";
}
if (!empty($newPassword)) {
    $updateFields[] = "`password` = '" . addslashes($newPassword) . "'";
}

if (isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] === UPLOAD_ERR_OK) {
    $file = $_FILES["profile_image"];
    $allowedTypes = array("image/jpeg", "image/png", "image/svg+xml", "image/webp");

    if (in_array($file["type"], $allowedTypes)) {
        $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
        $directory = "assets/images/porfilePics/";

        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }

        $targetPath = $directory . "profile_" . $userId . "_" . time() . "." . $extension;

        if (move_uploaded_file($file["tmp_name"], $targetPath)) {
            $updateFields[] = "`profilepicpath` = '" . addslashes($targetPath) . "'";
        }
    }
}

if (empty($updateFields)) {
    echo "no_changes";
    exit();
}

$query = "UPDATE `users` SET " . implode(", ", $updateFields) . " WHERE `id` = '" . $userId . "'";
Database::iud($query);

$updatedUserQuery = Database::search("SELECT * FROM `users` WHERE `id` = '" . $userId . "'");
$_SESSION["user"] = $updatedUserQuery->fetch_assoc();

echo "success";
?>
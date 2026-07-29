<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../../includes/db.php";

if (!isset($_SESSION["admin"])) {
    echo "unauthorized";
    exit();
}

$id = isset($_POST["id"]) ? (int)$_POST["id"] : 0;

if ($id > 0) {
    $query = Database::search("SELECT * FROM `gallery` WHERE `id` = '" . $id . "'");
    if ($query && $query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $relativePath = $row['imagePath'] ?? $row['path'] ?? '';
        
        if (!empty($relativePath)) {
            $file_path = "../../" . ltrim($relativePath, "/");
            if (is_file($file_path) && file_exists($file_path)) {
                @unlink($file_path);
            }
        }
    }

    Database::iud("DELETE FROM `gallery` WHERE `id` = '" . $id . "'");
    echo "success";
} else {
    echo "Invalid media ID.";
}
?>
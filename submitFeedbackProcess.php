<?php
include "includes/session.php";
include "includes/db.php";

if (!isset($_SESSION["user"])) {
    echo "login_required";
    exit();
}

$userId = $_SESSION["user"]["id"];
$message = $_POST["message"] ?? '';

if (empty(trim($message))) {
    echo "Please write a message before submitting.";
    exit();
}

Database::iud("INSERT INTO `feedback` (`user_id`, `message`) VALUES ('" . $userId . "', '" . mysqli_real_escape_string(Database::$connection, $message) . "')");

echo "success";
?>
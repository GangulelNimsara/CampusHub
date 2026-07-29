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

$escapedMessage = addslashes($message);

Database::iud("INSERT INTO `feedback` (`user_id`, `message`) VALUES ('" . $userId . "', '" . $escapedMessage . "')");

echo "success";
?>
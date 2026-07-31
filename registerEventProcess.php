<?php
ob_start();
error_reporting(0);
ini_set('display_errors', 0);

include "includes/session.php";
include "includes/db.php";

ob_clean();
header('Content-Type: text/plain; charset=utf-8');

if (!isset($_SESSION["user"])) {
    echo "login_required";
    exit();
}

$eventId = $_POST["eventId"] ?? $_POST["event_id"] ?? null;

if (!$eventId) {
    echo "Invalid Event Selected";
    exit();
}

$userId = $_SESSION["user"]["id"];

$checkQuery = Database::search("SELECT * FROM `registrations` WHERE `student_id` = '" . $userId . "' AND `event_id` = '" . $eventId . "'");

if ($checkQuery && $checkQuery->num_rows > 0) {
    echo "You are already registered for this event.";
} else {
    Database::iud("INSERT INTO `registrations` (`student_id`, `event_id`, `registartionStatus`) VALUES ('" . $userId . "', '" . $eventId . "', '1')");
    echo "success";
}
exit();
?>
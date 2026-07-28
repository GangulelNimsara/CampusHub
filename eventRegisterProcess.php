<?php
include "includes/session.php";
include "includes/db.php";

if (!isset($_SESSION["user"])) {
    echo "login_required";
    exit();
}

$userId = $_SESSION["user"]["id"];
$eventId = $_POST["event_id"] ?? null;

if (!$eventId || !is_numeric($eventId)) {
    echo "Invalid Event ID.";
    exit();
}

$checkRegistration = Database::search("SELECT * FROM `registrations` WHERE `student_id` = '" . $userId . "' AND `event_id` = '" . $eventId . "'");

if ($checkRegistration && $checkRegistration->num_rows > 0) {
    echo "already_registered";
} else {
    Database::iud("INSERT INTO `registrations` (`student_id`, `event_id`) VALUES ('" . $userId . "', '" . $eventId . "')");
    echo "success";
}
?>
<?php
include "includes/session.php";
require "includes/db.php";

$userId = $_SESSION["user"]["id"];

$regCountResult = Database::search("SELECT COUNT(*) AS `total` FROM `registrations` WHERE `user_id` = '" . $userId . "'");
$registeredCount = 0;
if ($regCountResult && $row = $regCountResult->fetch_assoc()) {
    $registeredCount = $row['total'];
}

$announcementResult = Database::search("SELECT COUNT(*) AS `total` FROM `announcements`");
$announcementCount = 0;
if ($announcementResult && $row = $announcementResult->fetch_assoc()) {
    $announcementCount = $row['total'];
}
?>
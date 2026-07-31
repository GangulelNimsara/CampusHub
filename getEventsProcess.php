<?php
include "includes/session.php";
include "includes/db.php";

header('Content-Type: application/json');

if (!isset($_SESSION["user"])) {
    echo json_encode([]);
    exit();
}

$userId = $_SESSION["user"]["id"];

$query = Database::search("SELECT `events`.`id`, `events`.`title`, `events`.`event_date` AS `start` 
                           FROM `registrations` 
                           INNER JOIN `events` ON `registrations`.`event_id` = `events`.`id` 
                           WHERE `registrations`.`student_id` = '" . $userId . "'");

$events = [];
if ($query && $query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $events[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'start' => $row['start']
        ];
    }
}

echo json_encode($events);
?>
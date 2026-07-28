<?php
include "includes/session.php";
include "includes/db.php";

header('Content-Type: application/json');

if (!isset($_SESSION["user"])) {
    echo json_encode([]);
    exit();
}

$eventsQuery = Database::search("SELECT `title`, `event_date` AS `start`, `venue` AS `description` FROM `events` WHERE `event_date` IS NOT NULL");

$eventsList = array();

if ($eventsQuery && $eventsQuery->num_rows > 0) {
    while ($row = $eventsQuery->fetch_assoc()) {
        $eventsList[] = array(
            'title' => $row['title'],
            'start' => $row['start'],
            'description' => $row['description'] ?? '',
            'className' => 'highlight-event-date'
        );
    }
}

echo json_encode($eventsList);
?>
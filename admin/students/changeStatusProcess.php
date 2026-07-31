<?php
include "../../includes/session.php";
include "../../includes/db.php";

if (!isset($_POST["id"]) || !isset($_POST["status"])) {
    echo "Invalid Request";
    exit();
}

$studentId = $_POST["id"];
$newStatus = (int)$_POST["status"];

$check = Database::search("SELECT * FROM `users` WHERE `id` = '" . $studentId . "'");

if ($check && $check->num_rows > 0) {
    Database::iud("UPDATE `users` SET `status_id` = '" . $newStatus . "' WHERE `id` = '" . $studentId . "'");
    echo "success";
} else {
    echo "User not found";
}
?>
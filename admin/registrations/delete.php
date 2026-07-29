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
    Database::iud("DELETE FROM `registrations` WHERE `id` = '" . $id . "'");
    echo "success";
} else {
    echo "Invalid registration ID.";
}
?>
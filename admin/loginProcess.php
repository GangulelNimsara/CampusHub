<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "../includes/db.php";

$username = isset($_POST["username"]) ? trim($_POST["username"]) : "";
$password = isset($_POST["password"]) ? trim($_POST["password"]) : "";

if (empty($username) || empty($password)) {
    echo "Please enter both username and password.";
    exit();
}

$escapedUsername = addslashes($username);
$escapedPassword = addslashes($password);

$adminQuery = Database::search("SELECT * FROM `admins` WHERE (`username` = '" . $escapedUsername . "' OR `email` = '" . $escapedUsername . "') AND `password` = '" . $escapedPassword . "'");

if ($adminQuery && $adminQuery->num_rows > 0) {
    $adminData = $adminQuery->fetch_assoc();
    $_SESSION["admin"] = $adminData;
    echo "success";
} else {
    echo "Invalid admin credentials.";
}
?>
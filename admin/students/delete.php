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
    
    $regCol = "";
    $regColsQuery = Database::search("SHOW COLUMNS FROM `registrations`");
    if ($regColsQuery) {
        while ($col = $regColsQuery->fetch_assoc()) {
            $field = $col['Field'];
            if (in_array($field, ['user_id', 'users_id', 'id_user', 'user_email'])) {
                $regCol = $field;
                break;
            }
        }
    }

    if (!empty($regCol)) {
        Database::iud("DELETE FROM `registrations` WHERE `{$regCol}` = '" . $id . "'");
    }

   
    Database::iud("DELETE FROM `users` WHERE `id` = '" . $id . "'");

    echo "success";
} else {
    echo "Invalid student ID.";
}
?>
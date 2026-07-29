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
   
    Database::iud("DELETE FROM `registrations` WHERE `event_id` = '" . $id . "'");
    
  
    $query = Database::search("SELECT `bannerPath` FROM `events` WHERE `id` = '" . $id . "'");
    if ($query && $query->num_rows > 0) {
        $row = $query->fetch_assoc();
        if (!empty($row['bannerPath'])) {
            $path = "../../" . ltrim($row['bannerPath'], '/');
            if (is_file($path) && file_exists($path)) {
                @unlink($path);
            }
        }
    }


    Database::iud("DELETE FROM `events` WHERE `id` = '" . $id . "'");
    echo "success";
} else {
    echo "Invalid Event ID.";
}
exit();
?>
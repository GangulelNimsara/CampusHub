<?php

session_start();

include "includes/db.php";

$usr = $_POST['username'] ?? '';
$pwd = $_POST['password'] ?? '';

if (empty($usr) && empty($pwd)) {
    echo "Please fill all the fields";
} else if (empty($usr)) {
    echo "Please enter username";
} else if (strlen($usr) > 12) {
    echo "Username should be less than 12 characters";
} else if (empty($pwd)) {
    echo "Please enter password";
} else if (strlen($pwd) < 8) {
    echo "Password should be at least 8 characters";
} else {

    $result = Database::search("SELECT * FROM `users` WHERE `username` = '".$usr."'");

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();

        if ($pwd !== $data['password']) {
            echo "Invalid Password";
        } else {
            $_SESSION["user"] = $data;
            echo "success";
        }
    } else {
        echo "user_not_found";
    }
}

?>
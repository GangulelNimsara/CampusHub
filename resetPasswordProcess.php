<?php

include "includes/db.php";

$pwd = $_POST['password'];
$otp = $_POST['verificationCode'];

if (empty($otp) || strlen($otp) !== 8) {
    echo "Invalid verification code length.";
    exit();
}else if (empty($pwd) || strlen($pwd) < 8) {
    echo "Password must be at least 8 characters.";
    exit();
}

$result = Database::search("SELECT * FROM `users` WHERE `vcode` = '".$otp."'");
$data = $result->fetch_assoc();
if(empty($result->num_rows)){
    echo "Please Enter Correct Verification Code";
}else if($pwd == $data['password']){
    echo "Use Different Password";
}else{
    Database::iud("UPDATE `users`  SET `password` = '".$pwd."' WHERE `vcode` = '".$otp."'");
    echo "success";
}


?>
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();

include "includes/db.php";
include "includes/phpmailer/Exception.php";
include "includes/phpmailer/PHPMailer.php";
include "includes/phpmailer/SMTP.php";

$email = $_POST["email"] ?? '';

if (empty($email)) {
    echo "Please enter your email address";
    exit();
}

$result = Database::search("SELECT * FROM `users` WHERE `email`='" . $email . "'");

if ($result && $result->num_rows > 0) {
    $vcode = sprintf("%08d", mt_rand(0, 99999999));
    
    Database::iud("UPDATE `users` SET `vcode` = '" . $vcode . "' WHERE `email` = '" . $email . "'");
    
    $_SESSION["reset_email"] = $email;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'zeroexp440@gmail.com'; 
        $mail->Password   = 'wxdz seqk fish dmmr';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->SMTPKeepAlive = false;

        $mail->setFrom('zeroexp440@gmail.com', 'CampusHub');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'CampusHub - Password Reset Code';
        $mail->Body    = 'Your password reset verification code is: <b>' . $vcode . '</b>';

        $mail->send();
        $mail->smtpClose();
        echo "success";

    } catch (Exception $e) {
        echo "Mail sending failed: " . $mail->ErrorInfo;
    }

} else {
    echo "Invalid email address";
}

?>
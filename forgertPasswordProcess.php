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
        $mail->Body = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Your Password - Campus Hub</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8f9fa; font-family: \'Segoe UI\', Tahoma, Geneva, Verdana, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 500px; background-color: #ffffff; border: 2px solid #212529; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                    <tr>
                        <td align="center" style="padding: 25px 20px; background-color: #f8f9fa; border-bottom: 2px solid #212529;">
                            <h2 style="margin: 0; color: #0d6efd; font-size: 24px; font-weight: bold;">Campus<span style="color: #ffc107;">Hub</span></h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px 25px; text-align: center;">
                            <h3 style="margin-top: 0; color: #212529; font-size: 20px;">Password Reset Request</h3>
                            <p style="color: #6c757d; font-size: 14px; line-height: 1.5; margin-bottom: 25px;">
                                We received a request to reset your password. Use the verification code below to complete the process:
                            </p>

                            <div style="background-color: #e9ecef; border: 2px dashed #212529; border-radius: 10px; padding: 15px; font-size: 28px; font-weight: bold; letter-spacing: 6px; color: #0d6efd; display: inline-block; margin-bottom: 25px;">
                                ' . $vcode . '
                            </div>

                            <p style="color: #6c757d; font-size: 13px; margin: 0;">
                                This code is valid for 10 minutes. If you did not request this reset, please ignore this email.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding: 15px; background-color: #f8f9fa; border-top: 1px solid #dee2e6; color: #6c757d; font-size: 12px;">
                            © 2026 Campus Hub. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>';

        $mail->send();
        $mail->smtpClose();
        echo "success";
    } catch (Exception $e) {
        echo "Mail sending failed: " . $mail->ErrorInfo;
    }
} else {
    echo "Invalid email address";
}

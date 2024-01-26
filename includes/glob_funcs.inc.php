<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once "phpmailer/src/Exception.php";
require_once "phpmailer/src/PHPMailer.php";
require_once "phpmailer/src/SMTP.php";
require_once "env.inc.php";


function sendVerificationEmail($to, $verificationEmail, $name)
{
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = MAIL_USERNAME;
        $mail->Password   = MAIL_PASSWORD;
        $mail->SMTPSecure = MAIL_ENCRYPTION;
        $mail->Port       = MAIL_PORT;

        $mail->setFrom(MAIL_USERNAME, APP_NAME);
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body    = "<h1>Hello $name, Click on this link to verify your email :</h1><h2><a href='$verificationEmail' target='_blank'>Verify Email</a></h2>";

        $mail->send();
        return 'Email sent successfully';
    } catch (Exception $e) {
        return "Error sending email: {$mail->ErrorInfo}";
    }
}

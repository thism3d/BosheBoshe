<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Make sure PHPMailer is installed via Composer

function sendmail($to, $subject, $htmlBody, $plainTextBody = '') {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 0;                                   // Disable verbose debug output
        $mail->isSMTP();                                        // Use SMTP
        $mail->Host       = 'mail.bosheboshe.com';                // SMTP server
        $mail->SMTPAuth   = true;                               // Enable SMTP auth
        $mail->Username   = 'orders@bosheboshe.com';                 // SMTP username
        $mail->Password   = '#Muzahid221';                     // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;        // Encryption
        $mail->Port       = 465;                                // Port

        // Recipients
        $mail->setFrom('orders@bosheboshe.com', 'BosheBoshe Orders');
        $mail->addAddress($to);                                 // Add recipient

        // Content
        $mail->isHTML(true);                                    // HTML format
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;
        $mail->AltBody = $plainTextBody ?: strip_tags($htmlBody);

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("PHPMailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

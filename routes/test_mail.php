<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Pastikan path ke file PHPMailer yang benar
require 'path/to/PHPMailer/src/Exception.php';
require 'path/to/PHPMailer/src/PHPMailer.php';
require 'path/to/PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'mail.bml-log.com'; // Host SMTP yang benar
    $mail->SMTPAuth = true;
    $mail->Username = 'quotation@bml-log.com'; // Username SMTP Anda
    $mail->Password = 'nZ6c}LI;Qg(z'; // Password SMTP Anda
    $mail->SMTPSecure = 'ssl'; // Gunakan 'tls' jika port 587
    $mail->Port = 465; // Port yang sesuai jika menggunakan SSL

    // Recipients
    $mail->setFrom('quotation@bml-log.com', 'Quotation Management System');
    $mail->addAddress('recipient@example.com', 'Recipient Name'); // Ganti dengan email penerima yang valid

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body    = 'This is a test email sent using PHPMailer.';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>

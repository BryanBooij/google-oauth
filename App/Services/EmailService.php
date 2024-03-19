<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    public function send_email($email)
    {

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);

        try {
            // mail information
            $mail_FROM = 'bryan@touchtree.tech';
            $user_RCPT = $email;

            // SMTP server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.elasticemail.com';  // SMTP hostname
            $mail->SMTPAuth = true;
            $mail->Username = 'bryan@touchtree.tech';  // SMTP username
            $mail->Password = '429F1CB709FB826771E8D774A9A99689883A'; //SMTP password
            $mail->SMTPSecure = 'tls'; // Enable TLS encryption
            $mail->Port = 2525; // TCP port to connect to

            // Sender and recipient settings
            $mail->setFrom($mail_FROM);
            $mail->addAddress($user_RCPT);

            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'Successful login';
            $mail->Body = '<p>Congratulations you have successfully logged in! </p>';

            // Send email + error message if needed
            $mail->send();
            echo 'Email sent successfully.';
        } catch (Exception $e) {
            echo 'Failed to send email. Error: ' . $mail->ErrorInfo;
        }
        return 0;
    }
}
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'singkollectivep@gmail.com';
    $mail->Password = 'irlvqizffcferlcq';
    $mail->SMTPSecure = 'tls'; // tls or ssl
    $mail->Port = 587; // Check your SMTP provider for the correct port

    //Recipients
    $mail->setFrom('autodoxccc@gmail.com', 'Autodox Notification');
    $mail->addAddress($email, $name);

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Autodox Verification';
    $mail->Body    = '  <p>Hi '.$name.',</p>
                    
                        <p>We’re excited to welcome you to Autodox Car Care Center portal! Before you begin your journey, we need to verify your account. Follow these steps to complete the verification process:</p>
                    
                        <p>Click the link below to verify your account:</p>
                        <p><a href="https://ecom.autodox.shop//landing-page/login/login.php?id='.$lastInsertedId.'">Verify My Account</a></p>
                    
                        <p>If the link isn’t clickable, you can copy and paste this URL into your browser:</p>
                        <p>https://ecom.autodox.shop//landing-page/login/login.php?id='.$lastInsertedId.'</p>
                    
                        <p>After verification, you’ll have access to all the amazing features on Autodox Car Care Center portal.</p>
                    
                        <p>If you encounter any issues or have questions, our support team is here to help. Simply reply to this email or reach out to us at <a href="mailto:[Support Email]">[Support Email]</a>.</p>
                    
                        <p>Welcome aboard!</p>
                    
                        <p>Sincerely,<br>Autodox CCC.</p>';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
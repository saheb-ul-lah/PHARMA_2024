<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$response = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $otp = rand(100000, 999999);

    session_start();
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'csjmc.noreply@gmail.com';
        $mail->Password = 'abuzqueovjzsygrc';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('csjmc.noreply@gmail.com', 'CSJMC Alumni');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP for Password Reset';
        $mail->Body    = "Your OTP is <b>$otp</b>. Please use this to reset your password.";
        $mail->AltBody = "Your OTP is $otp. Please use this to reset your password.";

        $mail->send();
        $response['status'] = 'success';
        $response['message'] = 'OTP has been sent to your email';
    } catch (Exception $e) {
        $response['status'] = 'error';
        $response['message'] = "OTP could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Invalid request';
}

echo json_encode($response);
?>

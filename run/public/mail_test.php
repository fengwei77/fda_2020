<?php
require_once('./class.phpmailer.php');
$mail = new PHPMailer(); // create a new object
$mail->IsSMTP(); // enable SMTP
$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages         only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "smtp.mail.yahoo.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "xxxxxx@ymail.com";
$mail->Password = "xxxxxx";
$mail->SetFrom("xxxxx@ymail.com");
$mail->Subject = "Test";
$mail->Body = "hello";
$mail->AddAddress("xxxxx@ymail.com");

if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message has been sent";
}?>

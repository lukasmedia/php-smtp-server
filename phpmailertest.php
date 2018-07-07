<?php
require_once __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('Etc/UTC');

$mail = new \Lukasmedia\PHPMailer\PHPMailer();
$mail->isSMTP();

//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;

$mail->Host = 'localhost';
$mail->Port = 25;
$mail->SMTPAuth = false;

$mail->setFrom('from@example.com', 'First Last');
$mail->addReplyTo('replyto@example.com', 'First Last');
$mail->addAddress('whoto@example.com', 'John Doe');
$mail->Subject = 'PHPMailer SMTP test';

$mail->msgHTML(file_get_contents('vendor/lukasmedia/phpmailer.min/contents.html'), __DIR__);
$mail->AltBody = 'This is a plain-text message body';
$mail->addAttachment('vendor/lukasmedia/phpmailer.min/images/phpmailer_mini.png');

if (!$mail->send())
    echo 'Mailer Error: ' . $mail->ErrorInfo;
else
    echo 'Message sent!';

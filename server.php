#!/usr/bin/php
<?php
require('src/smtp.class.inc.php');

$hp = new PHPSMTPServer;
$hp->logFile      = '/var/www/php-smtp-server/mail.log';
$hp->serverHello  = 'Mailroute.nl ESMTP Postfix';
$hp->mailFile     = '/var/www/php-smtp-server/emails/'.uniqid('').'.mime';
$hp->log(date('Y-m-d H:i:s') . ' RECEIVING NEW MESSAGE');
$hp->receive();
$hp->log(dirname(__FILE__));
exit;

#!/usr/bin/php
<?php
require('smtp.class.inc.php');

$hp = new PHPSMTPServer;
$hp->logFile      = '/var/www/php-smtp-server/mail.log';
$hp->serverHello  = 'Mailroute.nl ESMTP Postfix';
$hp->mailFile     = '/var/www/php-smtp-server/emails/'.uniqid('').'.mime';
$hp->receive();
exit;

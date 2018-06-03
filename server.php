#!/usr/bin/php
<?php
// https://github.com/zbateson/MailMimeParser
require('smtp.class.inc.php');

$hp = new fakeSMTP;
$hp->logFile      = '/var/www/php-smtp-server/mail.log';
$hp->serverHello  = 'Mailroute.nl ESMTP Postfix';
$hp->mailFile     = '/var/www/php-smtp-server/emails/'.uniqid('').'.mime';
$hp->receive();
exit;

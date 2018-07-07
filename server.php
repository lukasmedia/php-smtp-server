#!/usr/bin/php
<?php
$fullpath = trim(dirname(__FILE__));

require_once __DIR__ . '/vendor/autoload.php';
require('src/smtp.class.inc.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('php-smtp-server');
$log->pushHandler(new StreamHandler($fullpath.'/logs/mail.log', Logger::WARNING));

$mailParser = new \ZBateson\MailMimeParser\MailMimeParser();

$hp = new PHPSMTPServer($fullpath, $log, $mailParser);
exit;

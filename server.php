#!/usr/bin/php
<?php
require('src/smtp.class.inc.php');

$fullpath = dirname(__FILE__);

$hp = new PHPSMTPServer($fullpath);
exit;

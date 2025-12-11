<?php
require_once("service/send_email.php");
require_once("lib/util.php");
session_start();

// $inquiry_number = (new EmailSending($_SESSION['from'], Mode::DEVELOPMENT))->doWork();
$inquiry_number = (new EmailSending($_SESSION['from']))->doWork();
$_SESSION['inquiry_number'] = $inquiry_number;

// リダイレクト
header("Location: thanks.php");

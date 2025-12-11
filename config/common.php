<?php
define('HOST_NAME', 'みん社保');
define('HOST_CC_MAIL', 'murakami.sakiko@tssf.jp'); // 送信先 送信元
define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
define('CHATWORK_ROOM_ID', '342993215');
define("INQUIRYNUMBER", $_SERVER['DOCUMENT_ROOT'] . "/inquiry_number.txt");

// テスト用
define('CC_MAIL_TEST', 'zihan.li@bizlink.io');
define('CHATWORK_ROOM_ID_TEST', '312547356');

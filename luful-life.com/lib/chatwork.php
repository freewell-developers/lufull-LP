<?php

require_once("util.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/config/common.php");

// チャットワーク送信機能
function send_cw_notification($arr, $mode)
{

  // メッセージ本文生成
  $message = "[info][title]みん社保LPから流入がありました[/title]";
  $message .= "[ 流入経路 ] " . (!empty($arr["inflow_source"]) ? $arr["inflow_source"] : "オーガニック") . "\n";
  $message .= "[ 受付No ] " . $arr["inquiry_number"] . "\n";
  $message .= "[ 流入LP ] " . $arr["lp"] . "\n";
  $message .= "[ お名前 ] " . $arr["last_name"] . " " . $arr["first_name"] . "\n";
  $message .= "[ メールアドレス ] " . $arr["email"] . "\n";
  $message .= "[ 電話番号 ] " . $arr["tel"] . "\n";
  $message .= "[ 希望連絡手段 ] " . (!empty($arr["contact_method"]) ? $arr["contact_method"] : "指定なし") . "\n";
  $message .= "[ お問い合わせ内容 ]\n" . $arr["inquiry_contents"] . "\n";
  $message .= "[ 候補者マスタURL ]\n" .  "https://it-freelance.cybozu.com/k/18/show#record=" . $arr["candidate_record_id"] . "\n";
  $message .= "[ 商況マスタURL ]\n" .  "https://it-freelance.cybozu.com/k/48/show#record=" . $arr["trend_record_id"] . "\n";
  $message .= "[/info]";


  // パラメータ
  $params = ["body" => $message];

  // cURLでPOST
  $ch = curl_init();
  // cURLのオプション設定　{roomId}の箇所には取得したルームIDを入れる
  // curl_setopt($ch, CURLOPT_URL, "https://api.chatwork.com/v2/rooms/" . "342993215" . "/messages");
  $roomId = $mode == Mode::PRODUCTION ? CHATWORK_ROOM_ID : CHATWORK_ROOM_ID_TEST;
  curl_setopt($ch, CURLOPT_URL, "https://api.chatwork.com/v2/rooms/" . $roomId . "/messages");
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-ChatWorkToken: ' . "b5611435f94bf73b1b74e8995d53a632"));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    // 結果を文字列で返す
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // サーバー証明書の検証を行わない
  curl_setopt($ch, CURLOPT_POST, true);        // HTTP POSTを実行
  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params, '', '&'));

  $rsp = curl_exec($ch);
  curl_close($ch);
}

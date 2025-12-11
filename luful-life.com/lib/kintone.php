<?php
$logFile = __DIR__ . '/debug.log';
function log_debug($message) {
    global $logFile;
    error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, $logFile);
}
// ---------------------------
// レコード取得（utm_source 検索 / GET）
// ---------------------------
function get_kintone_record($aid)
{
  $app_id = 89;
  $api_token = "uuxW9uMA7ftQUOipbCU7sdCxMS7CBNWbUu4gsHBa";
  $subDomain = "it-freelance";

  $escaped_aid = addslashes($aid);
  $query = 'aid = "' . $escaped_aid . '"';
  $encoded_query = urlencode($query);

  $url = "https://{$subDomain}.cybozu.com/k/v1/records.json?app={$app_id}&query={$encoded_query}";
  
  $headers = [
    "X-Cybozu-API-Token: {$api_token}"
  ];

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); 
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');

  $response = curl_exec($curl);
  $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
  curl_close($curl);

  if ($http_status === 200) {
    $data = json_decode($response, true);
    return $data['records'][0] ?? null;
  }
  return null;
}

// ---------------------------
// レコード登録（候補者マスタ）
// ---------------------------
function create_kintone_candidate_record($arr)
{
  $candidate_app_id = 18;
  $candidate_api_token = "qfhKpR4bbzFbVTEz62n64chHANorqKrn8yMbBRcD";

  $inflow_source = trim(mb_convert_kana($arr["inflow_source"], "asKV", "UTF-8"));

  $candidate_body = [
    'app' => $candidate_app_id,
    'record' => [
      '反響日' => ['value' => date("Y-m-d")],
      '流入経路' => ['value' => $inflow_source],
      '受付No' => ['value' => $arr["inquiry_number"]],
      '申込LP' => ['value' => $arr["lp"]],
      '姓' => ['value' => $arr["last_name"]],
      '名' => ['value' => $arr["first_name"]],
      'メールアドレス' => ['value' => $arr["email"]],
      '電話番号' => ['value' => $arr["tel"]],
      '希望連絡手段' => ['value' => !empty($arr["contact_method"]) ? $arr["contact_method"] : "未設定"],
      'メモ' => ['value' => "【LP問合せ内容】\n" . $arr["inquiry_contents"]],
      'UTM_source' => ['value' => $arr["utm_source"]],
      'UTM_medium' => ['value' => $arr["utm_medium"]],
      'UTM_campaign' => ['value' => $arr["utm_campaign"]],
      'UTM_content' => ['value' => $arr["utm_content"]],
      'aid' => ['value' => $arr["aid"]],
      '紹介コード' => ['value' => $arr["introductioncode"]]
    ]
  ];
  //print_r($candidate_body);
  log_debug("候補者レコード送信データ: " . json_encode($candidate_body, JSON_UNESCAPED_UNICODE));

  $rsp = post_kintone($candidate_api_token, $candidate_body);
  //print_r($rsp);
  log_debug("rsp: " . print_r($rsp, true));

  return json_decode($rsp)->id;
}

// ---------------------------
// レコード登録（商況マスタ）
// ---------------------------
function create_kintone_trend_record($arr)
{
  $trend_app_id = 48;
  $trend_api_token = "qfhKpR4bbzFbVTEz62n64chHANorqKrn8yMbBRcD,0FXNNRruYT3mTDLjFUluAKhckcZQBIJZsGX1FDGc";
  $trend_body = [
    'app' => $trend_app_id,
    'record' => [
      '候補者番号' => ['value' => $arr["candidate_record_id"]]
    ]
  ];
  print_r($trend_body);

  $rsp = post_kintone($trend_api_token, $trend_body);
  print_r($rsp);
  return json_decode($rsp)->id;
}

// ---------------------------
// 共通POST関数（登録用）
// ---------------------------
function post_kintone($api_token, $post_body)
{
  // cURLでPOST
  $ch = curl_init();

  $subDomain = "it-freelance"; //サブドメイン

  // パラメータ
  $params = json_encode($post_body);

  $headers = [
    'X-Cybozu-API-Token: ' . $api_token,
    'Content-Type: application/json'
  ];

  // cURLのオプション設定　{roomId}の箇所には取得したルームIDを入れる
  curl_setopt($ch, CURLOPT_URL, "https://" . $subDomain . ".cybozu.com/k/v1/record.json");
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    // 結果を文字列で返す
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // サーバー証明書の検証を行わない
  curl_setopt($ch, CURLOPT_POST, true);        // HTTP POSTを実行
  curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

  $rsp = curl_exec($ch);
  curl_close($ch);
  return $rsp;
}

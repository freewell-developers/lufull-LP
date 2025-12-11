<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/common.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/chatwork.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/lib/kintone.php';
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/util.php";

class EmailSending
{
    private Option $option;
    private Mode $mode;

    function __construct(Option $option, Mode $mode = Mode::PRODUCTION)
    {
        $this->option = $option;
        $this->mode = $mode;
    }

    private function getInquiryNumber(): String
    {
        # 問い合わせ番号の取得
        $fp = fopen(INQUIRYNUMBER, 'r+');
        $inquiry_number = 0;

        # ファイルを開いて問い合わせ番号を取得
        if ($fp) {
            if (flock($fp, LOCK_EX)) {
                $inquiry_number = fgets($fp);
                $inquiry_number++;
                rewind($fp);
                if (fwrite($fp, (string)$inquiry_number) === FALSE) {
                    print('ファイル書き込みに失敗しました');
                }
                flock($fp, LOCK_UN);
            }
        }
        fclose($fp);
        $inquiry_number = sprintf('%08d', $inquiry_number);
        return $inquiry_number;
    }
    private function checkInput($params)
    {
        $last_name = myTrim($params['last_name']);
        if (empty($last_name)) {
            header("Location: index.php");
            die;
        }
        $first_name = myTrim($params['first_name']);
        if (empty($first_name)) {
            header("Location: index.php");
            die;
        }
        $email = myTrim($params['email']);
        if (empty($email) || !is_email($email)) {
            header("Location: index.php");
            die;
        }
        $tel = myTrim($params['tel']);
        if (empty($tel) || !is_tel_jp($params['tel'])) {
            header("Location: index.php");
            die;
        }
    }

    private function checkKintone($params)
    {
        $cookie_itf_aid = $_COOKIE['_itf_aid'] ?? '';
    
        // aidがなかったらオーガニック
        if (empty($cookie_itf_aid)) {
            return "オーガニック";
        }
    
        $record = get_kintone_record($cookie_itf_aid);
        $inflow_source_field = '流入経路_キャンペーン_名称';
    
        if ($record && isset($record[$inflow_source_field]['value'])) {
            return $record[$inflow_source_field]['value'];
        }
        // aidはあるけど、kintoneにレコードがなかったらオーガニック
        return "オーガニック";
    }
    

    private function notifyQueryReceivedToMinsyaho($inflow_source, $inquiry_number, $params): Bool
    {
        $receive_title = "お問い合わせを受け付けました。 (" . $inquiry_number . ")";
        $receive_msg  = "－○お問い合わせ情報－－－－－－－－－－－－－－－\n\n";
        $receive_msg .= "[ 流入経路 ]：" . $inflow_source . "\n";
        $receive_msg .= "[ 受付No ]：" . $inquiry_number . "\n";
        $receive_msg .= "[ 流入元LP ]：" . $this->option->toString() . "\n";
        $receive_msg .= "[ お名前 ]：" . $params["last_name"] . " " . $params["first_name"] . "\n";
        $receive_msg .= "[ メールアドレス ]：" . $params['email'] . "\n";
        $receive_msg .= "[ 電話番号 ]：" . $params['tel'] . "\n";

        $receive_msg .= "[ キャンペーンコード ]：" . $params["campaign_code"] . "\n";

        $receive_msg .= "[ お問い合わせ内容 ]：\n" . $params['inquiry_contents'] . "\n\n";
        $receive_msg .= "－－－－－－－－－－－－－－－－－－－－－－－\n";

        $charset = "UTF-8";
        $headerss['MIME-Version'] = "1.0";
        $headerss['Content-Type'] = "text/plain; charset=" . $charset;
        $headerss['Content-Transfer-Encoding'] = "BASE64";
        $headerss['From'] = mb_encode_mimeheader('みん社保') . '<' . $params['email'] . '>';

        //headerを作成
        foreach ($headerss as $key => $val) {
            $arrheader[] = $key . ': ' . $val;
        }
        $header = implode("\n", $arrheader);
        mb_language('ja');
        mb_internal_encoding("UTF-8");
        return mb_send_mail($this->mode == Mode::PRODUCTION ? HOST_CC_MAIL : CC_MAIL_TEST, $receive_title, $receive_msg, $header);
    }

    private function notifyQueryHandledToClient($inquiry_number, $params): Bool
    {
        $reply_title = "お問い合わせ受付完了 (受付No: " . $inquiry_number . ")";

        $reply_msg = $params["last_name"] . " " . $params["first_name"] . " 様\n\n";

        $reply_msg .= "お問い合わせありがとうございます。\n";
        $reply_msg .= "みん社保 運営事務局です。\n\n";

        $reply_msg .= "弊社サービス「みん社保」につきまして、\n";
        $reply_msg .= "ご年齢や扶養の有無によって保険料の削減金額などが変わってまいりますので、\n";
        $reply_msg .= "一度オンライン上にてシミュレーションを交え簡単にご説明させていただきたく\n";
        $reply_msg .= "下記URLからご都合のよろしいお時間を選択いただけますと幸いです。\n\n";

        $reply_msg .= "https://app.aitemasu.me/u/itfreeelance/minsyaho\n\n";

        $reply_msg .= "所要時間は15~20分程度を予定しております。\n\n";

        $reply_msg .= "↓LINEでのやり取りご希望のお客様はコチラ↓\n";
        $reply_msg .= "https://lin.ee/utV5oTd\n\n";


        $reply_msg .= "その他、ご不明点やご希望等ございましたら、\n";
        $reply_msg .= "メール、またはお電話にてご遠慮なくお問い合わせください。\n\n";

        $reply_msg .= "ご多用のところ恐れ入りますが、よろしくお願いいたします。\n\n";

        $reply_msg .= "－－－－－－－－－－－－－－－－－－－－－－－\n";
        $reply_msg .= "みん社保 運営事務局\n\n";
        $reply_msg .= "〒156-0043 東京都世田谷区松原3-40-7 パインフィールドビル302\n";
        $reply_msg .= "【電話】03-6692-2017\n";
        $reply_msg .= "【FAX】03-5300-4030\n";
        $reply_msg .= "－－－－－－－－－－－－－－－－－－－－－－－\n\n";

        $charset = "UTF-8";
        $headersc['MIME-Version'] = "1.0";
        $headersc['Content-Type'] = "text/plain; charset=" . $charset;
        $headersc['Content-Transfer-Encoding'] = "BASE64";
        $headersc['From'] = mb_encode_mimeheader('みん社保') . '<' . HOST_CC_MAIL . '>';
        //headerを作成
        foreach ($headersc as $key => $val) {
            $arrheaderc[] = $key . ': ' . $val;
        }
        $headerc = implode("\n", $arrheaderc);
        mb_language('ja');
        mb_internal_encoding("UTF-8");

        // メール送信
        return mb_send_mail($params['email'], $reply_title, $reply_msg, $headerc);
    }

    private function registerToKintone($inflow_source, $inquiry_number, $params): Bool
    {
       $inflow_source_value = $inflow_source ?: "オーガニック"; 
        // Kintone候補者マスタにレコード登録
        $candidate_param = [
            "inflow_source" => $inflow_source_value,
            "inquiry_number" => $inquiry_number,
            "lp" => $this->option->toString(),
            "last_name" => $params["last_name"],
            "first_name" => $params["first_name"],
            "email" => $params["email"],
            "tel" => $params["tel"],
            "inquiry_contents" => $params["inquiry_contents"],
            "campaign_code" => $params["campaign_code"], // ← 追加
            "utm_source"  => $_COOKIE['_itf_utm_source'] ?? '',
            "utm_medium"  => $_COOKIE['_itf_utm_medium'] ?? '',
            "utm_campaign"  => $_COOKIE['_itf_utm_campaign'] ?? '',
            "utm_content"  => $_COOKIE['_itf_utm_content'] ?? '',
            "aid"  => $_COOKIE['_itf_aid'] ?? '',
            "introductioncode" => $params["introductioncode"],
            "contact_method" => $params["contact_method"],
        ];

        $candidate_record_id = create_kintone_candidate_record($candidate_param);

        // Kintone商況マスタにレコード登録
        $trend_param = [
            "candidate_record_id" => $candidate_record_id
        ];
        $trend_record_id = create_kintone_trend_record($trend_param);
        // チャットワーク通知
        if (preg_match('/^\d+$/', $candidate_record_id) && preg_match('/^\d+$/', $candidate_record_id)) {
            $cw_param = [
                "inflow_source" => $inflow_source_value,
                "inquiry_number" => $inquiry_number,
                "lp" => $this->option->toString(),
                "last_name" => $params["last_name"],
                "first_name" => $params["first_name"],
                "email" => $params["email"],
                "tel" => $params["tel"],
                "contact_method" => $params["contact_method"], 
                "inquiry_contents" => $params["inquiry_contents"],
                "campaign_code" => $params["campaign_code"],// ← 追加
                "candidate_record_id" => $candidate_record_id,
                "trend_record_id" => $trend_record_id
            ];
            send_cw_notification($cw_param, $this->mode);
            return true;
        } else {
            return false;
        }
    }

  public function doWork(): String
    {
        $params = $_POST;
        $this->checkInput($params);
        $inflow_source = $this->checkKintone($params);
        $inquiry_number = $this->getInquiryNumber();
        if (
            !($this->notifyQueryReceivedToMinsyaho($inflow_source, $inquiry_number, $params)
                && $this->notifyQueryHandledToClient($inquiry_number, $params)
                && $this->registerToKintone($inflow_source, $inquiry_number, $params))
        ) {
            // ユーザリクエストの処理失敗しました
            // issue2 問い合わせの処理が失敗する場合の例外処理の追加
            header("Location: " . ($_SESSION["from"] == Option::LP1 ? "/" : "/lp2"));
            exit();
        }
        # 8桁ゼロ埋めして、セッションに保存
        return $inquiry_number;
    }
}

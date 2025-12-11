<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/util.php";
session_start();

# 問い合わせ番号
# ページのrefreshに対する修正
$inquiry_number = null;
if (array_key_exists('inquiry_number', $_SESSION)) {
    $inquiry_number = $_SESSION['inquiry_number'];
    unset($_SESSION['inquiry_number']);
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>

    <?php readfile("components/head_content.html") ?>

</head>

<body class="page-thanks">

    <div class="thanks">
        <div>
            <h3>お問い合わせいただき、<span class="nowrap">ありがとうございます。</span></h3>
            <p>自動送信メールにて、お申し込みの方へメールが送信されました。<br />
                ご確認頂けますようお願い申し上げます。<br />
            </p>
            <p class="lg-txt">
                ご希望のお打ち合わせ希望日時をご選択ください。
                <a href="https://app.aitemasu.me/u/itfreeelance/minsyaho" target="_self" class="schedule">日程調整はこちら</a><br>
                また、お打ち合わせのご希望日時が18時以降・土日の場合は<br />
                後ほどお送りするメールへのご返信、またはお電話にて遠慮なくお申しつけください。
            </p>
            <p class="y-tel"><a href="tel" onclick="yahoo_report_conversion('tel:03-6692-2017')">03-6692-2017に電話する</a></p>
            <div class="line-area">
                <p class="lineicon"><img src="/images/line-icon.svg" alt="line">LINEでのやり取りご希望のお客様はコチラ↓</p>
                <a href="https://lin.ee/utV5oTd" class="lineqrcode"><img src="/images/line-qrcode.png" alt="line-qrcode"></a>
            </div>
            <p>
                なお、一両日経過してもメールが届かない場合には<br />
                ご入力時のメールアドレスが間違っている場合がありますので<br />
                誠に恐縮ですが、再度のご連絡をよろしくお願いいたします
            </p>

            <a href="/index.php" target="_self" class="back-link">TOPページに戻る</a>

        </div>
    </div>
    <footer>Copyright © みん社保. All rights reserved.</footer>

</body>

</html>
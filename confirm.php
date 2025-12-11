<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/lib/util.php";

$params = $_POST;
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
if (!is_email($params['email'])) {
  header("Location: index.php");
  die;
}

if (empty($email)) {
  header("Location: index.php");
  die;
}

$tel = myTrim($params['tel']);
if (!is_tel_jp($params['tel'])) {
  header("Location: index.php");
  die;
}
if (empty($tel)) {
  header("Location: index.php");
  die;
}


$introductioncode = myTrim($params['introductioncode']);


$contact_method = isset($params['contact_method']) ? $params['contact_method'] : "";


$comment = htmlspecialchars(stripslashes($params['inquiry_contents']));


session_start();

?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <?php readfile("components/head_content.html") ?>
  <div class="contact">
    <div class="inner">
      <h2 class="section-title">お問い合わせ</h2>
      <div class="content">
        <form id="c-form" action="/send_mail.php" method="POST">
          <dl>
            <dt>お名前<span>＊</span></dt>
            <dd><?php echo $params['last_name'] . " " . $params['first_name'] ?></dd>
            <input type="hidden" name="last_name" value="<?php echo $params['last_name'] ?>" id="last_name">
            <input type="hidden" name="first_name" value="<?php echo $params['first_name'] ?>" id="first_name">
          </dl>
          <dl>
            <dt>電話番号<span>＊</span></dt>
            <dd><?php echo $params['tel'] ?></dd>
            <input type="hidden" name="tel" value="<?php echo $params['tel'] ?>" id="tel">
          </dl>
          <dl>
            <dt>メールアドレス<span>＊</span></dt>
            <dd><?php echo $params['email'] ?></dd>
            <input type="hidden" name="email" value="<?php echo $params['email'] ?>" id="email">
          </dl>

          <dl>
            <dt>希望連絡手段<span>＊</span></dt>
            <dd><?php echo $contact_method; ?></dd>
            <input type="hidden" name="contact_method" value="<?php echo $contact_method; ?>">
          </dl>


          <dl>
            <dt>紹介コード</dt>
            <dd><?php echo $params['introductioncode'] ?></dd>
            <input type="hidden" name="introductioncode" value="<?php echo $params['introductioncode'] ?>" id="introductioncode">
          </dl>
          
          <dl>
            <dt>お問い合わせ内容</dt>
            <dd><?php if (!empty($comment)) {
                  echo preg_replace("/\r\n|\n|\r/", "<br>", $comment);
                } else {
                  echo "";
                } ?>
            </dd>
            <input type="hidden" name="inquiry_contents" value="<?php echo $comment ?>" id="inquiry_contents">
            <input type="hidden" name="code" value="code">
          </dl>
        </form>
      </div>
      <div class="submit-box">
        <div class="submit"><input type="submit" id="c_back" value="戻る"></div>

        <div class="loading-container">
          <div class="submit">
            <input type="submit" id="c_btt" value="送信">
            <div class="loading-overlay">
              <div class="loading-spinner" id="loading"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php readfile("components/footer.html"); ?>
</body>
<script>
  $("#c_back").click(function() {
    history.go(-1);
  })
  $(document).ready(function() {
    $("#c_btt").click(function(event) {
      $(this).closest('.loading-container').addClass('loading');
      $(this).prop('disabled', true);
      $("#c-form").submit();
    });

    $("#c-form").on('submit', function() {
      event.preventDefault();
      $("#c_btt").prop('disabled', true);
      $("#loading").show();
    });
  });
</script>

</html>

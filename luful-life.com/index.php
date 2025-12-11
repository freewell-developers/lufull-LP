<?php
require_once 'lib/util.php';
include 'lib/set_cookies.php';

$_SESSION['from'] = Option::LP2;
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  
  <!-- <link rel="stylesheet" href="css/style_new01.css"> -->

  <?php
  readfile("components/twitter_card_content.html");
  readfile("components/head_content.html")
  ?>
  <!-- <meta name="google-site-verification" content="20Rbuvvvnd4VhBmq1zxTeknoJYp5enFWBgsbEX_fUUg" /> -->
  <script src="/js/main_page_script.js"></script>
  <!-- <link rel="stylesheet" href="css/style_new01.css"> -->

</head>

<body class="home">

  <?php
  readfile("components/header.html");
  readfile("components/trouble.html");
  readfile("components/leave.html");
  readfile("components/bizlink.html");
  readfile("components/subscriber.html");
  readfile("components/actual.html");
  readfile("components/four.html");
  readfile("components/check-esay.html");
  readfile("components/qa.html");
  readfile("components/carrier.html");
  readfile("components/contact.html");
  readfile("components/footer.html");
  ?>

</body>

<script>
  (function(d) {
    var config = {
      kitId: 'wxj6xrd',
      scriptTimeout: 3000,
      async: true
    },
    h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
  })(document);
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- <script src="js/main_page_script.js"></script> -->



</html>

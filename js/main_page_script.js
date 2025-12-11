(function ($) {
  function ieFix() {
    if (!!window.ActiveXObject || "ActiveXObject" in window) {
      $("html").attr("data-browser", "IE");
      if ($(".hasbg").length) {
        $(".hasbg").each(function () {
          $(this).css({
            "background-image": function () {
              var b = $(this).children("img").attr("src");
              $(this).children("img").remove();
              return "url(" + b + ")";
            },
          });
        });
      }
    }
  }

  function ResizeAll() {
    var $box = $(".leave-ul .content");
    if ($box.length) {
      $box.css({
        height: "auto",
      });
      $box.height(
        Math.max.apply(
          null,
          $box.map(function () {
            return $(this).height();
          })
        )
      );
    }
  }

  function scrollToObject() {
    $(".home .more").on("click", function (event) {
      event.preventDefault();
      var $target = $(this).attr("href");
      var $position = $($target).offset().top;
      $("html,body").stop().animate({ scrollTop: $position }, 600);
    });
    $(".home .sptop-btn more2").on("click", function (event) {
      event.preventDefault();
      var $target = $(this).attr("href");
      var $position = $($target).offset().top;
      $("html,body").stop().animate({ scrollTop: $position }, 600);
    });
    $(".home .anchor").on("click", function (event) {
      event.preventDefault();
      var $anchor = $("." + $(this).data("anchor"));
      var $position = $($anchor).offset().top - 20;
      $("html,body").stop().animate({ scrollTop: $position }, 600);
    });
  }

  function animateContent() {
    var $object = $(
      ".home .section-title, .hukidasi-title-div, .insurance .content,.insurance .bottom,.common,.simulation p,.simulation a,.trouble figure,.leave,.leave-ul .inner > ul > li,.bizlink .content,.bizlink .more,.leave-to span,.leave-to .table1,.leave-to .leave-tit1,.leave-to .content,.leave-to .leave-tit2,.actual .actual-ul,.actual .actual-common h3,.actual .actual-common ul,.actual .more,.check-esay .check-ul2 li,.qa dl,.carrier table,.insurance .inner > figure,.usually .usually-1,.usually .usually-2,.usually-tit,.usually .usually-3,.usually .usually-4,.four h2,.four ul li,.check-esay .inner > h3,.check-esay .check-ul1 li,.introduction h2,.introduction ul,.introduction .introduction1,.introduction .more1,.subscriber .subscriber-1,.subscriber .subscriber-2,.introduction .section-title,.introduction ul,.meeting .inner > p,.meeting ul,.meeting .more,.home .contact .content,.introduce .flex"
    );
    $object.each(function () {
      $this = $(this);
      $this.addClass("normalmove");
      if ($this.length) {
        var $a = $(this).offset().top;
        var $b = $(window).scrollTop();
        var $c = $(window).height() * 0.8;
        if ($b > $a - $c) {
          if (!$this.hasClass("normalanimate")) {
            $this.addClass("normalanimate");
          }
        }
      }
    });
  }

  $(function () {
    ieFix();
    ResizeAll();
    animateContent();
    scrollToObject();
  });
  $(window).on("scroll", function () {
    animateContent();
  });

  window.addEventListener("scroll", () => {
    const more = window.document.getElementById("sptop-btn");
    const triggerPosition = 20000; // アニメーションを開始する位置を指定
    if (window.scrollY > triggerPosition) {
      more.classList.add("hidden");
      more.classList.remove("show");
    } else {
      more.classList.add("show");
      more.classList.remove("hidden");
    }
  });
  $(document).ready(function () {
    function showErrorMessage(elementId, message) {
      $("#" + elementId).text(message);
    }

    function clearErrorMessage(elementId) {
      $("#" + elementId).text("");
    }

    function validateInput() {
      var isValid = true;

      var last_name = $("#last_name").val().trim();
      if (last_name == "") {
        showErrorMessage("last_name_error", "姓が入力されていません");
        isValid = false;
      } else {
        clearErrorMessage("last_name_error");
      }

      var first_name = $("#first_name").val().trim();
      if (first_name == "") {
        showErrorMessage("first_name_error", "名が入力されていません");
        isValid = false;
      } else {
        clearErrorMessage("first_name_error");
      }

      var tel = $("#mob").val().trim();
      if (tel == "") {
        showErrorMessage("mob_error", "電話番号が入力されていません");
        isValid = false;
      } else if (/[０-９]/.test(tel)) {
        showErrorMessage(
          "mob_error",
          "電話番号には全角文字を使用しないでください"
        );
        isValid = false;
      } else if (tel.includes("-")) {
        showErrorMessage("mob_error", "ハイフンなしで入力してください");
        isValid = false;
      } else if (!/^(0[5-9]0[0-9]{8}|0[1-9][1-9][0-9]{7})$/.test(tel)) {
        showErrorMessage(
          "mob_error",
          "電話番号のフォーマットが正しくありません"
        );
        isValid = false;
      } else {
        clearErrorMessage("mob_error");
      }

      var email = $("#email").val().trim();
      if (email == "") {
        showErrorMessage("email_error", "メールアドレスが入力されていません");
        isValid = false;
      } else if (!/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/.test(email)) {
        showErrorMessage(
          "email_error",
          "メールアドレスのフォーマットが正しくありません"
        );
        isValid = false;
      } else {
        clearErrorMessage("email_error");
      }





      var acceptance = $("#acceptance").is(":checked");
      if (!acceptance) {
        showErrorMessage(
          "acceptance_error",
          " 個人情報の取り扱いに同意の上、確認ボタンを押してください"
        );
        isValid = false;
      } else {
        clearErrorMessage("acceptance_error");
      }


      return isValid;
    }

    $("#last_name, #first_name, #mob, #email, #acceptance, #campaign_code").on(
      "input",
      function () {
        validateInput();
      }
    );

    $("#btt").click(function () {
      if (validateInput()) {
        $("#m-form").submit();
      } else {
        return false;
      }
    });

    
  });
})(jQuery);



// スムーススクロール処理
document.addEventListener("DOMContentLoaded", () => {

  const links = document.querySelectorAll("a.jump");

  links.forEach(link => {
    link.addEventListener("click", (e) => {
      const href = link.getAttribute("href");

      if (href.startsWith("#")) {
        e.preventDefault();

        const target = document.querySelector(href);
        if (!target) return;

        const top = target.getBoundingClientRect().top + window.scrollY - 80;

        window.scrollTo({
          top,
          behavior: "smooth"
        });
      }
    });
  });

});




// ==========================
// Q&A アコーディオン処理
// ==========================
document.addEventListener("DOMContentLoaded", function () {

  const dts = document.querySelectorAll(".qa-item > dt");

  dts.forEach(dt => {

    if (!dt) return; // nullチェック（安全対策）

    dt.addEventListener("click", function () {

      const dd = dt.nextElementSibling;
      if (!dd) return;

      dt.classList.toggle("active");

      if (dd.style.display === "block") {
        dd.style.display = "none";
      } else {
        dd.style.display = "block";
      }
    });
  });

});

$(document).ready(function onClick() {
    //.js-modal-videoの画像がクリックされた際に、modalvideoを実行
    $(".js-modal-video").modalVideo({
        channel: "youtube",
        youtube: {
            rel: 0, //関連動画の指定
            autoplay: 0, //自動再生の指定
            controls: 0, //コントロールさせるかどうかの指定
        },
    });
});

$(function () {
    $(window).scroll(function () {
      $(".effect-fade").each(function () {
        var elemPos = $(this).offset().top; /* 要素の位置を取得 */
        var scroll = $(window).scrollTop(); /* スクロール位置を取得 */
        var windowHeight = $(window).height(); /* 画面幅を取得（画面の下側に入ったときに動作させるため） */
        if (scroll > elemPos - windowHeight) {
          /* 要素位置までスクロール出来たときに動作する */
          $(this).addClass("effect-scroll");
        }
      });
    });
    jQuery(window).scroll();
  });

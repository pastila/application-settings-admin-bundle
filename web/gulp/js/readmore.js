$(document).ready(function() {
  (function() {
    var showChar = 800;
    var ellipsestext = "...";

    $(".readmore__parent").each(function() {
      var content = $(this).html();
      if (content.length > showChar) {
        var c = content.substr(0, showChar);
        var h = content;
        var html =
            '<div class="readmore-text" style="display:block">' +
            c +
            '<span class="moreellipses">' +
            ellipsestext +
            '&nbsp;&nbsp;<a rel="nofollow" href="#" class="moreless more">Читать весь комментарий...</a></span></span></div><div class="readmore-text" style="display:none">' +
            h +
            '<a rel="nofollow" href="#" class="moreless less">Скрыть комментарий</a></span></div>';

        $(this).html(html);
      }
    });

    $(".moreless").click(function() {
      var thisEl = $(this);
      var cT = thisEl.closest(".readmore-text");
      var tX = ".readmore-text";

      if (thisEl.hasClass("less")) {
        cT.prev(tX).toggle();
        cT.slideToggle();
      } else {
        cT.toggle();
        cT.next(tX).fadeToggle();
      }
      return false;
    });
    /* end iffe */
  })();

  /* end ready */
});
$(document).ready(function() {
  $('.less').click(function() {
    $('html, body').animate({
      scrollTop: $(this).closest('.readmore__parent').offset().top - 80 + "px"
    }, 400);
  });
})

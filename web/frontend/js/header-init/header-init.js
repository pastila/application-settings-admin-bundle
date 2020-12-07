export default function headerInit() {

  if ($('.app-header')) {
    var scrollPos;

    $('.app-header__menu-button').on('click', function (event) {
      event.preventDefault();

      if ($(this).hasClass('is-opened')) {
        scrollPos = $(window).scrollTop();
        $(this).removeClass('is-opened');
        $('body').removeClass('nav-active');
      } else {
        $(this).addClass('is-opened');
        $('body').addClass('nav-active');
      }
    });

    $(window).on('scroll', function (event) {
      if ($(window).scrollTop() > 0) {
        $('.app-header').addClass("is-fixed");
      } else {
        $('.app-header').removeClass("is-fixed");
      }

      $('.app-header').addClass('is-visible');

    }).trigger('scroll');
  }
}
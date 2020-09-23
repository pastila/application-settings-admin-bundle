$(document).ready(function () {

  $('.steps_items_item_button:not(:last)').on( 'click', function(){
    if(!$(this).hasClass("disabled")) {
      $('html').animate({
            scrollTop: $(".steps-wrap").offset().top - 10
          }, 1300
      );
    }
    return false;
  });
});
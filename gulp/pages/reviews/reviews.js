$(document).ready(function() {

  function showComment() {
    const items = document.querySelectorAll('.white_block');

    items.forEach(function(item) {
      const btn = item.querySelector('.feedback__bottom_link');
      const showBlock = item.querySelector('.hidenComments');
      const count = item.querySelector('.comment-count');

      if (count && count.innerText > 0) {
        if (btn) {
          btn.addEventListener('click', function(e) {

            this.classList.toggle('active');

            if (btn.classList.contains('active')) {
              showBlock.classList.add('active');
            } else {
              showBlock.classList.remove('active');
            }
          });
        }
      } else {
        return
      }
    });
  }

  showComment();
  $('.toggle_comment_dropdown').on('click', function (e) {
    $(this).parent().toggleClass('openedBlock');
  });

  var data = {
    "id_rewievs":"0",
    "star":"0",
  };

  $(".feedback_change_star").click(function() {
    var  data_id_rewiev = $(this).attr("data-id-rewiev");
    if($(this).hasClass("click_on")){
      $('[data-form-id='+data_id_rewiev+']').toggleClass("hidden");
      $(this).html("Редактировать оценку");
      $(this).removeClass("click_on");

    }else{
      $('[data-form-id='+data_id_rewiev+']').toggleClass("hidden");
      $(this).html("Отмена");
      $(this).addClass("click_on");
      $('[data-star-id='+data_id_rewiev+']').each(function() {
        $(this).removeClass("selected");
      })
    }



    data.star = 0;
    $(".change_count_star").click(function(e) {
      e.preventDefault();
      $('[data-star-id='+data_id_rewiev+']').each(function() {
        if($(this).hasClass("selected") == true){
          data.star = $(this).attr("data-value");
        }
      });

      data.id_rewievs = $(this).attr("data-id-rewiev");
          $.ajax({
                url: '/ajax/change_amount_star.php',
                type: 'POST',
                data: data,
                beforeSend: function() {

                },
                success: function(msg){
                    if(msg == 0){
                      $('[data-result-id='+data_id_rewiev+']').html("Без изменений");
                      $('[data-result-id='+data_id_rewiev+']').removeClass("hidden");


                    }
                    if(msg == 1){
                      $('[data-result-id='+data_id_rewiev+']').html("Сохранено");
                      $('[data-result-id='+data_id_rewiev+']').removeClass("hidden");
                     var star =  $(".star-active").first();

                     if($('[data-star='+data_id_rewiev+']').length < data.star){
                       var count_add = data.star - $('[data-star='+data_id_rewiev+']').length;

                       for(var i = 1; i <= count_add;++i) {
                         $('[data-block-star='+data_id_rewiev+']').append(star.clone());
                       }
                     }else if($('[data-star='+data_id_rewiev+']').length > data.star){

                       var count_remove =  $('[data-star='+data_id_rewiev+']').length  - data.star;

                       for(var i = 1; i <= count_remove;++i) {
                         $('[data-star='+data_id_rewiev+']:first').remove();
                       }
                     }

                    }
                },
              }).done(function(msg) {

              });
    });
  })
});
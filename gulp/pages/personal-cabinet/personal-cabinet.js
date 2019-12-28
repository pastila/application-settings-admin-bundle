var fd = new FormData();
var data = {
  "id_city":"0",
};



$(document).ready(function() {

  $(document).on('click', '#search_result li', function() {
    $('#referal').val($(this).text());
    $('#search_result').fadeOut();
  });
  $(document).on('click', '#referal', function() {
    $('#search_result').css({'display': 'block'});
  });
  $(document).mouseup(function(e) {
    let container = $('#referal');
    if (container.has(e.target).length === 0) {
      $('#search_result').fadeOut();
    }
  });
  $(document).on('click', '#search_result_hospital li', function() {
    $('#referal_two').val($(this).text());
    $('#search_result_hospital').fadeOut();
  });
  $(document).on('click', '#referal_two', function() {
    $('#search_result_hospital').css({'display': 'block'});
  });

  $(document).mouseup(function(e) {
    let container = $('#referal_two');
    if (container.has(e.target).length === 0) {
      $('#search_result_hospital').fadeOut();
    }
  });


  $('#referal').on('keyup', function() {
    var $this = $(this);
    var delay = 500;
    if ($this.val() == '') {
      $this.attr("data-id_region","");
      $(".hospital").each(function(){
        $(this).remove();
      });
      $("#hospital").remove();
      $("#referal_two").val("");
      $("#referal_two").attr("data-id_region","");
    }
    $('#search_result').css({'display': 'block'});
    clearTimeout($this.data('timer'));
    $this.data('timer', setTimeout(function() {
      $this.removeData('timer');
      $.post('/ajax/personal-cabinet/search_region.php', {name_city: $this.val()}, function(msg) {
        if ($('.error_region').length != 0) {
          $('.error_region').remove();
        }
        $('.region').each(function() {
          $(this).remove();
        });
        if (msg == 'error_region') {

          if ($('.error_region').length != 0) {
            $('.error_region').remove();
            $('#search_result').append('<li class="error_region" >Регион не найден</li>');
          } else {
            $("#search_result").append('<li class="error_region" >Регион не найден</li>');
          }
        } else {
          setTimeout(function() {
            $('#search_result').append(msg);
          }, 100);
        }
      });
    }, delay));
  });




  $(document).on('click', '.region', function() {
    $(".search-second").css({"pointer-events":"inherit"});
    let id_region = $(this).attr('value');
    $('#referal').attr('data-id_region', id_region);
    $('#referal').attr('data-region_check', 'check');
    $('#referal_two').val('');


    $.post('/ajax/personal-cabinet/search_company.php', {region_id:$("#referal").attr("data-id_region") }, function(msg) {
      if ($('.error_region').length != 0) {
        $('.error_region').remove();
      }
      $('.hospital').each(function() {
        $(this).remove();
      });
      $('.hospital-empty').remove();
      setTimeout(function() {
        $('#search_result_hospital').append(msg);
      }, 100);

    });
  });




  $(document).on('click', '.hospital', function() {
    let id_region = $(this).attr('value');
    let select_region = $(this).text();

    $('#referal_two').val(select_region);
    $('#referal_two').attr('data-id_region', id_region);
    $('#referal_two').attr('data-region_check', 'check');
  });

  $('#referal_two').on('keyup', function() {
    var $this = $(this);
    var delay = 500;
    if ($this.val() == '') {
      $this.attr("data-id_region","");
    }
    $('#search_result_hospital').css({'display': 'block'});
    clearTimeout($this.data('timer'));
    $this.data('timer', setTimeout(function() {
      $this.removeData('timer');
      $.post('/ajax/personal-cabinet/search_company.php', {name_hospital: $this.val(), region_id:$("#referal").attr("data-id_region") }, function(msg) {
        if ($('.error_region').length != 0) {
          $('.error_region').remove();
        }
        $('.hospital').each(function() {
          $(this).remove();
        });
        $('.hospital-empty').remove();
        if (msg == 'error_company') {

          if ($('.error_region').length != 0) {
            $('.error_region').remove();
            $('#search_result_hospital').append('<li class="error_region" >Компания не найдена</li>');
            $('.hospital-empty').remove();

          } else {
            $('#search_result_hospital').append('<li class="error_region" >Компания не найдена</li>');
            $('.hospital-empty').remove();
          }
        } else {
          console.log("2323");
          setTimeout(function() {
            $('#search_result_hospital').append(msg);
          }, 100);

        }
      });
    }, delay));
  });







  $("#change-data").click(function() {

    if($(this).hasClass("click") == false) {
      $(this).addClass("click");
      $("#true_data_person").css({"display": "none"});
      $("#for_change_person").css({"display": "block"});
      $("#change-data").text("Отменить редактирование");
    }else {
      $(this).removeClass("click");
      $("#true_data_person").css({"display": "block"});
      $("#for_change_person").css({"display": "none"});
      $("#change-data").text("Редактировать данные");
    }
  })
  $('[name=personal_phone]').mask('+7(000)000-00-00');
})
$("#save_data").click(function(e) {
  e.preventDefault();
  var error = [];
  var data_FORM =  $("#form_change_data").serializeArray();
  var input_file = $('.input_file');

  if (input_file.prop('files')[0] != undefined) {
    if (input_file.prop('files')[0]['size'] > 10485760) {
      error.push('error');
      $('.block-error-label_size').css({"display":"block"});
      error.push("error");
    }
    var format_file = input_file.prop('files')[0]['type'];
    format_file = format_file.split('/')[1];
    if ((format_file.search('jpeg') == -1) &&
        (format_file.search('pjpeg') == -1) && (format_file.search('png') == -1)) {
      $('.block-error-label_format').css({"display":"block"});
      error.push("error");
    }
  }



  let region = $('#referal').attr('data-id_region');
  console.log(region);
  if(region != "" || region != undefined || region != "0"){
    fd.append("town",region );
  }
  let hospital = $('#referal_two').attr('data-id_region');

  if(hospital != "" || hospital != undefined || region != "0"){
    fd.append("id_company",hospital);
  }



    if(data_FORM[5]['value'].length >16 ){
      $('[name=uf_insurance_policy]').after(
          ' <span class="label danger  error-policy-max "  >Введен длинный номер полиса</span>');
      error.push("error");

    }else if( data_FORM[5]['value'].length  <16){
      $('[name=uf_insurance_policy]').after(
          '<span class="label danger  error-policy-min "  >Введен короткий номер полиса</span>');
      error.push("error");
    }


    if(data_FORM[3]['value'].length <16){
      $('[name=personal_phone]').after(
          '<span class="label danger  error-personal_phone "  >Введен короткий номер телефона</span>');
      error.push("error");
    }
    if(data_FORM[0]['value'].length <2){
      $('[name=name]').after(
          '<span class="label danger  error-name "  >Введите имя</span>');
      error.push("error");
    }
    if(data_FORM[1]['value'].length <2){
      $('[name=last_name]').after(
          '<span class="label danger  error-last_name "  >Введите фамилию</span>');
      error.push("error");
    }
    if(data_FORM[2]['value'].length <2){
      $('[name=second_name]').after(
          '<span class="label danger  error-second_name "  >Введите отчество</span>');
      error.push("error");
    }
  if(data_FORM[4]['value'].length <2){
    $('[name=email]').after(
        '<span class="label danger  error-email "  >Введите эмеил</span>');
    error.push("error");
  }

  fd.append('import_file', input_file.prop('files')[0]);
  fd.append('name', data_FORM[0]['value']);
  fd.append('last_name', data_FORM[1]['value']);
  fd.append('second_name', data_FORM[2]['value']);
  fd.append('personal_phone', data_FORM[3]['value']);
  fd.append('email', data_FORM[4]['value']);
  fd.append('uf_insurance_policy', data_FORM[5]['value']);

  if(error.length == 0) {
    $.ajax({
      url: '/ajax/change_data_user.php',
      type: 'POST',
      data: fd,
      processData: false,
      contentType: false,
      beforeSend: function() {

      },
      success: function(msg) {
        if (msg == "1") {
          window.location.reload();
        }
        if (msg == "Пользователь с таким эмейлом уже есть") {

          $(".input_email").
              after(
                  '<p class="danger">Пользователь с таким эмейлом уже есть</p>')
        }
      },
    }).done(function(msg) {

    });
  }
});
/*добавление файла*/
$(document).ready(function() {
  var inputs = document.querySelectorAll('.file-input');

  for (var i = 0, len = inputs.length; i < len; i++) {
    customInput(inputs[i])
  }

  function customInput (el) {
    const fileInput = el.querySelector('[type="file"]');
    const label = el.querySelector('[data-js-label]');

    fileInput.onchange =
        fileInput.onmouseout = function () {
          if (!fileInput.value) return;

          var value = fileInput.value.replace(/^.*[\\\/]/, '');
          el.className += ' -chosen';
          label.innerText = value
        }
  }
});
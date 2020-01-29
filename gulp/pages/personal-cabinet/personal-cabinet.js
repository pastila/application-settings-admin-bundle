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
  $("#save_data").click(function(e) {
    e.preventDefault();
    var error = [];
    var data_FORM =  $("#form_change_data").serializeArray();
    var input_file = $('.input_file');

    // if (input_file.prop('files')[0] != undefined) {
    //   if (input_file.prop('files')[0]['size'] > 10485760) {
    //     error.push('error');
    //     $('.block-error-label_size').css({"display":"block"});
    //     error.push("error");
    //   }
    //   var format_file = input_file.prop('files')[0]['type'];
    //   format_file = format_file.split('/')[1];
    //   if ((format_file.search('jpeg') == -1) &&
    //       (format_file.search('pjpeg') == -1) && (format_file.search('png') == -1)) {
    //     $('.block-error-label_format').css({"display":"block"});
    //     error.push("error");
    //   }
    // }







    if(data_FORM[4]['value'].length >16 ){
      $('[name=uf_insurance_policy]').after(
          ' <span class="label danger  error-policy-max "  >Введен длинный номер полиса</span>');
      error.push("error");

    }else if( data_FORM[4]['value'].length  <16){
      $('[name=uf_insurance_policy]').after(
          '<span class="label danger  error-policy-min "  >Введен короткий номер полиса</span>');
      error.push("error");
    }


    let region = $('#referal').attr('data-id_region');
    console.log(region);
    if(region != "" || region != undefined || region != "0"){
      fd.append("town",region );
      let hospital = $('#referal_two').attr('data-id_region');

      if(hospital != "" || hospital != undefined || region != "0"){
        fd.append("id_company",hospital);
      }
    }

    if(data_FORM[1]['value'].length <2){
      $('[name=name]').after(
          '<span class="label danger  error-name "  >Введите имя</span>');
      error.push("error");
    }
    if(data_FORM[0]['value'].length <2){
      $('[name=last_name]').after(
          '<span class="label danger  error-last_name "  >Введите фамилию</span>');
      error.push("error");
    }
    if(data_FORM[2]['value'].length <2){
      $('[name=second_name]').after(
          '<span class="label danger  error-second_name "  >Введите отчество</span>');
      error.push("error");
    }
  if(data_FORM[3]['value'].length <2){
    $('[name=email]').after(
        '<span class="label danger  error-email "  >Введите эмеил</span>');
    error.push("error");
  }
  // fd.append('import_file', input_file.prop('files')[0]);
  fd.append('name', data_FORM[1]['value']);
  fd.append('last_name', data_FORM[0]['value']);
  fd.append('second_name', data_FORM[2]['value']);
  fd.append('email', data_FORM[3]['value']);
  fd.append('uf_insurance_policy', data_FORM[4]['value']);

    if(error.length == 0) {
      $.ajax({
        url: '/ajax/personal-cabinet/change_data_user.php',
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

  //Добавление информации о ребенке
  $(document).on('click', '#add_children_btn', function() {
    search_region();
    keyup_region();
    $('#add_children').css('display','block');
    $('#add_children_btn').css('display','none');
  });
  $(document).on('click', '#cancel', function() {
    $('#add_children').css('display','none');
    $('#add_children_btn').css('display','flex');
    $('#add_children_form')[0].reset();
  });
  $(document).on('click', '#save_children', function(e) {
    let id = $('#hospital_input').attr('data-id_hospital');
    $form = document.getElementById('add_children_form');
    if ($form.checkValidity()) {
      $.post('/ajax/add_children.php', {
            name: $('#children_name_add').val(),
            surname: $('#children_last_name_add').val(),
            patronymic: $('#children_second_name_add').val(),
            town:$(".id_region").val(),
            policy: $('#child_policy_add').val(),
            hospital: id,
            birthday: $('#children_birthday_add').val(),
          },
          function(result) {
            if (result === 'error') {
              $('.error_search-js').css('display', 'block');
            } else if (result === 'success'){
              $('#add_children').css('display','none');
              $('#add_children_btn').css('display','flex');
              $('#add_children_form')[0].reset();

              $.post('/ajax/cur_children.php', function(e) {
                $('#cur_children').html(e);
              });
            }
          }, 'html');
    }
  });
  $(document).on('click', '.del_js', function() {
    let id = $(this).attr('id').split('_');
    let cur_id = $('#element_' + id[1]);
    $.ajax({
      url: '/ajax/del_child.php',
      data: {ID: id[1]},
      type: 'post',
      success: function() {
        cur_id.remove();
      }
    });
  });

  $(document).on('click', '.edit_js', function() {
    let id = $(this).attr('id').split('_');
    let cur_id = $('#element_' + id[1]);
    $('#edit_children_' + id[1]).css('display','block');
    $('#element_' + id[1]).css('display','none');
    search_region_edit(id[1]);
    keyup_region_edit(id[1]);
    search_hospital_edit(id[1]);
    cancel(id[1]);
    save(id[1]);
    let id_region = $('#region_input_'+id[1]).attr('data-id_region');
    keyup_hospital_edit(id_region, id[1]);
    // $.ajax({
    //   url: '/ajax/edit_child.php',
    //   data: {ID: id[1]},
    //   type: 'post',
    //   success: function() {
    //
    //   }
    // });
  });
});
function cancel(id) {
  $(document).on('click', '#cancel_edit_'+id, function() {
    location.reload();
  });
}
function save(id) {
  $(document).on('click', '#save_edit_'+id, function(e) {
    let id_el = $('#hospital_input_'+id).attr('data-id_hospital');
    $form = document.getElementById('edit_children_form_'+id);
    if ($form.checkValidity()) {
      $.post('/ajax/add_children.php', {
            name: $('#children_name_add_'+id).val(),
            surname: $('#children_last_name_add_'+id).val(),
            patronymic: $('#children_second_name_add_'+id).val(),
            policy: $('#child_policy_add_'+id).val(),
            hospital: id_el,
            birthday: $('#children_birthday_add_'+id).val(),
            id: id
          },
          function(result) {
            if (result === 'error') {
              $('.error_search-js_'+id).css('display', 'block');
            } else if (result === 'success'){
              $.post('/ajax/cur_children.php', function(e) {
                location.reload();
              });
            }
          }, 'html');
    }
  });

}

function search_region() {
  // ------ choose_region ------
  $(document).on('click', '#search_result_region li', function() {
    $('#region_input').val($(this).text());
    $('#search_result_region').fadeOut()
  });
  $(document).on('click', '#region_input', function() {
    $('#search_result_region').css('display', 'block');
  });
  $(document).mouseup(function(e) {
    let container = $('#region_input');
    if (container.has(e.target).length === 0) {
      $('#search_result_region').fadeOut();
    }
  });

  $(document).on('click', '.region-js', function() {
    let id = $(this).attr('value');
    let component = $('#hospitals');
    $.post('/ajax/choice_hospital.php', {id: id}, function(result) {
      $(component).html(result);
      search_hospital();
    }, 'html');
  });
}
function search_region_edit(id) {
  // ------ choose_region ------
  $(document).on('click', '#search_result_region' + id +' li', function() {
    $('#region_input_' + id).val($(this).text());
    $('#search_result_region_' + id).fadeOut()
  });
  $(document).on('click', '#region_input_' + id, function() {
    $('#search_result_region_' + id).css('display', 'block');
  });
  $(document).mouseup(function(e) {
    let container = $('#region_input_' + id);
    if (container.has(e.target).length === 0) {
      $('#search_result_region_' + id).fadeOut();
    }
  });
  $(document).on('click', '.region-js_' + id, function() {
    let id_el = $(this).attr('value');
    let component = $('#hospitals_' + id);
    $.post('/ajax/choice_hospital_edit.php', {id: id_el, el: id}, function(result) {
      $(component).html(result);
      search_hospital_edit(id);
    }, 'html');
  });
}
function search_hospital_edit(id) {
  // ------ choose_hospital ------
  $(document).on('click', '#search_result_hospital_' + id +' li', function() {
    $('#hospital_input_'+id).val($(this).text());
    $('#hospital_input_'+id).attr('data-id_hospital', $(this).attr('value'));
    $('#search_result_hospital_'+id).fadeOut()
  });
  $(document).on('click', '#hospital_input_'+id, function() {
    $('#search_result_hospital_'+id).css('display', 'block');
  });
  $(document).mouseup(function(e) {
    let container = $('#hospital_input_'+id);
    if (container.has(e.target).length === 0) {
      $('#search_result_hospital_'+id).fadeOut();
    }
  });
  keyup_region_edit(id);
  let id_region = $('#region_input_'+id).attr('data-id_region');
  keyup_hospital_edit(id_region, id);
}

function search_hospital() {
  // ------ choose_hospital ------
  $(document).on('click', '#search_result_hospital_down li', function() {
    $('#hospital_input').val($(this).text());
    $('#hospital_input').attr('data-id_hospital', $(this).attr('value'));
    $('#search_result_hospital_down').fadeOut()
  });
  $(document).on('click', '#hospital_input', function() {
    $('#search_result_hospital_down').css('display', 'block');
  });
  $(document).mouseup(function(e) {
    let container = $('#hospital_input');
    if (container.has(e.target).length === 0) {
      $('#search_result_hospital_down').fadeOut();
    }
  });
  keyup_region();
  let id_region = $('#region_input').attr('data-id_region');
  keyup_hospital(id_region);
}

function keyup_region_edit(id) {
  $('#region_input_'+id).on('keyup', function() {
    var $this = $(this);
    var delay = 500;
    clearTimeout($this.data('timer'));
    $this.data('timer', setTimeout(function() {
      $this.removeData('timer');
      $('#search_result_region_'+id).css('display', 'block');
      $.post('/ajax/smart_search_region_edit.php', {name_city: $this.val(), el: id}, function(msg) {
        if ($('.error_region_'+id).length != 0) {
          $('.error_region_'+id).remove();
        }
        $('.region-js_'+id).each(function() {
          $(this).remove();
        });
        if (msg == 'error_region') {
          if ($('.error_region_'+id).length != 0) {
            $('.error_region_'+id).remove();
            $('#search_result_region_'+id).
                append('<li class="error_region_'+id+'">Регион не найден</li>');
          } else {
            $('#search_result_region_'+id).
                append('<li class="error_region_'+id+'">Регион не найден</li>');
          }
        } else {
          setTimeout(function() {
            $('#search_result_region_'+id).append(msg);
          }, 100);

          $(document).on('click', '.region-js_'+id, function() {
            let id_el = $(this).attr('value');
            let select_region = $(this).text();
            $('#region_input_'+id).val(select_region);
            $('#region_input_'+id).attr('data-id_region', id);
            let component = $('#hospitals_'+id);
            $.ajax({
              dataType: 'html',
              url: '/ajax/choice_hospital_edit.php',
              type: 'POST',
              data: {id: id_el, el: id},
              beforeSend: function() {
              },
              success: function(result) {
                $(component).html(result);
                search_hospital_edit(id);
                let id_class = $('#region_input_'+id).attr('data-id_region');
                keyup_hospital_edit(id_class, id);
              },
            }).done(function(msg) {

            });
          });
        }
      });
    }, delay));
  });
}



function keyup_region() {
  $('#region_input').on('keyup', function() {
    var $this = $(this);
    var delay = 500;
    clearTimeout($this.data('timer'));
    $this.data('timer', setTimeout(function() {
      $this.removeData('timer');
      $('#search_result_region').css('display', 'block');
      $.post('/ajax/smart_search_region.php', {name_city: $this.val()}, function(msg) {
        if ($('.error_region').length != 0) {
          $('.error_region').remove();
        }
        $('.region-js').each(function() {
          $(this).remove();
        });
        if (msg == 'error_region') {
          if ($('.error_region').length != 0) {
            $('.error_region').remove();
            $('#search_result_region').
                append('<li class="error_region">Регион не найден</li>');
          } else {
            $('#search_result_region').
                append('<li class="error_region">Регион не найден</li>');
          }
        } else {
          setTimeout(function() {
            $('#search_result_region').append(msg);
          }, 100);
          $(document).on('click', '.region-js', function() {
            let id = $(this).attr('value');
            let select_region = $(this).text();
            $('#region_input').val(select_region);
            $('#region_input').attr('data-id_region', id);
            let component = $('#hospitals');
            $.ajax({
              dataType: 'html',
              url: '/ajax/choice_hospital.php',
              type: 'POST',
              data: {id: id},
              beforeSend: function() {
              },
              success: function(result) {
                $(component).html(result);
                search_hospital();
                let id_class = $('#region_input').attr('data-id_region');
                keyup_hospital(id_class);
              },
            }).done(function(msg) {

            });
          });
        }
      });
    }, delay));
  });
}
function keyup_hospital(id_region) {
  $('#hospital_input').on('keyup', function() {
    var $this = $(this);
    var delay = 500;
    clearTimeout($this.data('timer'));
    $this.data('timer', setTimeout(function() {
      $this.removeData('timer');
      $('#search_result_hospital_down').css('display', 'block');
      $.post('/ajax/smart_search_hosp.php', {name_hospital: $this.val(), region_id: id_region
      }, function(msg) {
        if ($('.error_hospital').length != 0) {
          $('.error_hospital').remove();
        }
        $('.hospital-js').each(function() {
          $(this).remove();
        });
        if (msg == 'error_hospital') {
          if ($('.error_hospital').length != 0) {
            $('.error_hospital').remove();
            $('#search_result_hospital_down').
                append('<li class="error_region error_hospital">Компания не найдена</li>');
          } else {
            $('#search_result_hospital_down').
                append('<li class="error_region error_hospital">Компания не найдена</li>');
          }
        } else {
          setTimeout(function() {
            $('#search_result_hospital_down').append(msg);
          }, 100);
          $(document).on('click', '.hospital-js', function() {
            let id = $(this).attr('value');
            let select_hospital = $(this).text();
            $('#hospital_input').val(select_hospital);
            $('#hospital_input').attr('data-id_hospital', id);
          });
        }
      });
    }, delay));
  });
}
function keyup_hospital_edit(id_region, id) {
  $('#hospital_input_'+id).on('keyup', function() {
    var $this = $(this);
    var delay = 500;
    clearTimeout($this.data('timer'));
    $this.data('timer', setTimeout(function() {
      $this.removeData('timer');
      $('#search_result_hospital_'+id).css('display', 'block');
      $.post('/ajax/smart_search_hosp_edit.php', {name_hospital: $this.val(), region_id: id_region, el:id
      }, function(msg) {
        if ($('.error_hospital_'+id).length != 0) {
          $('.error_hospital_'+id).remove();
        }
        $('.hospital-js_'+id).each(function() {
          $(this).remove();
        });
        if (msg == 'error_hospital') {
          if ($('.error_hospital_'+id).length != 0) {
            $('.error_hospital_'+id).remove();
            $('#search_result_hospital_'+id).
                append('<li class="error_region error_hospital_'+id+'">Компания не найдена</li>');
          } else {
            $('#search_result_hospital_'+id).
                append('<li class="error_region error_hospital_'+id+'">Компания не найдена</li>');
          }
        } else {
          setTimeout(function() {
            $('#search_result_hospital_'+id).append(msg);
          }, 100);
          $(document).on('click', '.hospital-js_'+id, function() {
            let id = $(this).attr('value');
            let select_hospital = $(this).text();
            $('#hospital_input_'+id).val(select_hospital);
            $('#hospital_input_'+id).attr('data-id_hospital', id);
          });
        }
      });
    }, delay));
  });
}

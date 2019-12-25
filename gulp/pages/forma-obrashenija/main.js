

$(document).ready(function() {
  search_region();
  search_class();
  keyup_class();
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
  $(document).on('click', '.region', function() {

    let id_region = $(this).attr('value');
    let select_region = $(this).text();
    $('#referal').val(select_region);
    $('#referal').attr('data-id_region', id_region);
    $('#region_name').text(select_region);
    $('#referal').attr('data-region_check', 'check');
    let component = $('#hospitals');
    $.ajax({
      dataType: 'html',
      url: '/ajax/form_hospitals.php',
      type: 'POST',
      data: {id: id_region},
      beforeSend: function() {
      },
      success: function(result) {
        $(component).html(result);

        $('#region_name').text(select_region);
        $('#hosptital_name').html('Не выбрано');
        search_region();
        search_hospital();
      },
    }).done(function(msg) {
    });
  });
  $(document).mouseup(function(e) {
    var div = $('#search_result');
    var input = $('#referal');
    if (div.text() == '' || $('#referal').attr('data-region_check') != 'check') {
      if (!div.is(e.target) && div.has(e.target).length === 0 &&
          !input.is(e.target)) {
        $('#referal').text('');
        $('#referal').val('');
      }
    }
  });
 function search_region() {
   console.log("23");
   $('#referal').on('keyup', function() {
console.log("3232");
     var $this = $(this);
     var delay = 500;
     if ($this.val() == '') {
       $('#region_name').text('НЕ ВЫБРАНО');
        $this.attr("data-id_region","");
     }
     clearTimeout($this.data('timer'));
     $this.data('timer', setTimeout(function() {
       $this.removeData('timer');
       $.post('/ajax/smart_search.php', {name_city: $this.val()}, function(msg) {
         if ($('.error_region').length != 0) {
           $('.error_region').remove();
         }
         $('.region').each(function() {
           $(this).remove();
         });
         if (msg == 'error_region') {

           if ($('.error_region').length != 0) {
             $('.error_region').remove();
             $('#search_result').append('<li class="error_region" >Город не найден</li>');
           } else {
          console.log(   $("#search_result"));
             $("#search_result").append('<li class="error_region" >Город не найден</li>');
           }
         } else {
           setTimeout(function() {
             $('#search_result').append(msg);
           }, 100);
           $(document).on('click', '.region', function() {

             let id_region = $(this).attr('value');
             let select_region = $(this).text();
             $('#referal').val(select_region);
             $('#referal').attr('data-id_region', id_region);
             $('#referal').attr('data-region_check', 'check');
             $('#region_name').text(select_region);
             let component = $('#hospitals');
             $.ajax({
               dataType: 'html',
               url: '/ajax/form_hospitals.php',
               type: 'POST',
               data: {id: id_region},
               beforeSend: function() {
               },
               success: function(result) {
                 $(component).html(result);
                 $('#region_name').text(select_region);
                 $('#hosptital_name').html('Не выбрано');
                 search_hospital();
                 search_region();
               },
             }).done(function(msg) {
             });
           });
         }
       });
     }, delay));
   });
 }

function search_hospital() {
  $(document).on('click', '.hospital', function() {
    let id_region = $(this).attr('value');
    let select_region = $(this).text();
    let name_boss = $(this).attr("data-name-boss");
    let name_street = $(this).attr("data-street");
    $('#referal_two').val(select_region);
    $('#referal_two').attr('data-id_region', id_region);
    $('#referal_two').attr('data-region_check', 'check');
    $('#hosptital_name').text(select_region);
    $('#street_name').text(name_street);
    $('#boss_name').text(name_boss);



  });
  $('#referal_two').on('keyup', function() {
    var $this = $(this);
    var delay = 500;
    if ($this.val() == '') {
      $('#hosptital_name').text('НЕ ВЫБРАНО');
      $this.attr("data-id_region","");
      $('#street_name').text('НЕ ВЫБРАНО');
      $('#boss_name').text('НЕ ВЫБРАНО');

    }

    clearTimeout($this.data('timer'));
    $this.data('timer', setTimeout(function() {
      $this.removeData('timer');
      $.post('/ajax/smart_search_hospital.php', {name_hospital: $this.val(), region_id:$("#referal").attr("data-id_region") }, function(msg) {
        if ($('.error_region').length != 0) {
          $('.error_region').remove();
        }
        $('.hospital').each(function() {
          $(this).remove();
        });
        $('.hospital-empty').remove();

        if (msg == 'error_hospital') {
          keyup_class();
          if ($('.error_region').length != 0) {
            $('.error_region').remove();
            $('#search_result_hospital').append('<li class="error_region" >Больница не найдена</li>');
            $('.hospital-empty').remove();

          } else {
            $('#search_result_hospital').append('<li class="error_region" >Больница не найдена</li>');
            $('.hospital-empty').remove();
          }
        } else {
          setTimeout(function() {
            $('#search_result_hospital').append(msg);
          }, 100);

          $(document).on('click', '.hospital', function() {
            let id_region = $(this).attr('value');
            let select_region = $(this).text();
            let name_boss = $(this).attr("data-name-boss");
            let name_street = $(this).attr("data-street");
            $('#referal_two').val(select_region);
            $('#referal_two').attr('data-id_region', id_region);
            $('#referal_two').attr('data-region_check', 'check');
            $('#hosptital_name').text(select_region);
            $('#street_name').text(name_street);
            $('#boss_name').text(name_boss);


          });
        }
      });
    }, delay));
  });
}





  let urgent = $('#urgent');
  let planned = $('#planned');
  let appeal;

  planned.click(function() {
    if (planned.is(':checked')) {
      urgent.prop('checked', false);
      appeal = 'Плановое';
    }
  });
  urgent.click(function() {
    if (urgent.is(':checked')) {
      planned.prop('checked', false);
      appeal = 'Неотложное';
    }
  });

  $(document).on('click', '#diagnoz_elem', function() {
    let cur_select = $('#choose_diagnoz_elem');
    cur_select.attr('value', this.getAttribute('value'));
  });

  $(document).on('click', '#strax-sluchay', function() {
    let error = [];
    let $form = $('#appeal-form');
    let $title = $('#page-title');

    let region = $('#referal').attr('data-id_region');
    if(region == "" || region == undefined){
      $('#referal').after(
          '<span class="label danger"  >Выберете регион</span>');
      error.push("error");
    }
    let hospital = $('#referal_two').attr('data-id_region');
    if(hospital == "" || hospital == undefined){
      $('#referal_two').after(
          '<span class="label danger"  >Выберете больницу</span>');
      error.push("error");
    }
    let choose_class = $('#class_input').attr('data-id_class');
    if(choose_class == "" || choose_class == undefined){
      $('#class_input').after(
          '<span class="label danger"  >Выберете класс</span>');
      error.push("error");
    }
    let choose_group = $('#group_input').attr('data-id_group');
    if(choose_group == "" || choose_group == undefined){
      $('#group_input').after(
          '<span class="label danger"  >Выберете группу</span>');
      error.push("error");
    }
    let choose_subgroup = $('#subgroup_input').attr('data-id_subgroup');
    if(choose_subgroup == "" || choose_subgroup == undefined){
      $('#subgroup_input').after(
          '<span class="label danger"  >Выберете подгруппу</span>');
      error.push("error");
    }
    let choose_diagnoz = $('#diagnoz_input').attr('data-id_diagnoz');
    if(choose_diagnoz == "" || choose_diagnoz == undefined){
      $('#diagnoz_input').after(
          '<span class="label danger"  >Выберете диагноз</span>');
      error.push("error");
    }
    let years = [];

    let div = $('#years');
    $(div).find('input:checked').each(function() {
      years.push(this.value);
    });
    if(years.length == 0 ){
      $(".wrap-chrckbox:first").after(
          '<span class="label danger"  >Выберете год</span>');
      error.push("error");
    }
    let plan=[];
    let div_last = $('.wrap-chrckbox').last();
    $(div_last).find('input:checked').each(function() {
      plan.push(this.value);
    });
    if(plan.length == 0 ){
      div_last.after(
          '<span class="label danger"  >Не выбранно</span>');
      error.push("error");
    }
    if(error.length > 0){

    }else {

      if ($('#choose_diagnoz_elem').val() != 'Здесь нет моего диагноза') {

        if ($('.header__r_auth_reg').length == 1  && ($(".header__r_auth_reg").attr("data-rigstration") == 0)) {

            $('.header__r_auth_reg').trigger('click');
            setTimeout(function() {
              $('.register_before_review').removeClass('hidden');
            }, 700);

        } else {


          $.post('/ajax/diagnoz.php',
              {
                APPEAL_VALUE: appeal,
                YEARS: years,
                REGION: region,
                HOSPITAL: hospital,
                CLASS: choose_class,
                GROUP: choose_group,
                SUBGROUP: choose_subgroup,
                DIAGNOZ: choose_diagnoz,
              },
              function(result) {
                let diagnoz = jQuery.parseJSON(result);
                if (diagnoz !== 'error') {

                  $form.replaceWith(diagnoz['DIAGNOZ']);
                  $title.html(diagnoz['FULL_NAME']);

                  document.body.scrollTop = document.documentElement.scrollTop = 0;

                  $('#generate_form').magnificPopup({
                    type: 'ajax',
                    modal: true,

                    callbacks: {
                      beforeOpen: function() {
                        if ($(window).width() < 700) {
                          this.st.focus = false;
                        } else {
                          this.st.focus = '#name';
                        }
                      },
                      afterClose: function() {
                        $.post('/ajax/number_calls.php', function(result) {
                          $('#number_calls').html(result);
                        }, 'html');
                      },
                    },
                  });

                } else {
                  $.magnificPopup.open({
                    items: {
                      src: '<div class="white-popup custom_styles_popup">Вы заполнили не все данные</div>',
                      type: 'inline',
                    },
                  });

                }
              }, 'html');
        }
      } else {
        $.magnificPopup.open({
          items: {
            src: '<div class="white-popup custom_styles_popup">Вы заполнили не все данные</div>',
            type: 'inline',
          },
        });
      }
    }

  });

  $(document).on('click', '#option', function() {
    let classID = $(this).attr('value');
    let component = $('.grid');
    let id = $(this).attr('id');
    if (!!classID && id === 'option') {
      $.post('/ajax/main_form_oms.php', {id: classID}, function(result) {
        $(component).html(result);
        console.log('stage 1');
        update_select();
      }, 'html');
    }
  });

  $(document).on('click', '#location', function() {
    let classID = $(this).attr('value');
    let region_name_value = $(this).text();
    let component = $('#hospitals');
    let region_name = $('#region_name');
    let id = $(this).attr('id');
    if (!!classID && id === 'location') {
      $.post('/ajax/form_hospitals.php', {id: classID}, function(result) {
        $(component).html(result);
        $(region_name).html(region_name_value);
        $('#hosptital_name').html('Не выбрано');
        update_hospital_select();
      }, 'html');
    }
  });

  $(document).on('click', '#hospital', function() {
    let cur_select = $('#choose_hospital_elem');
    cur_select.attr('value', this.getAttribute('value'));
    let hospital_name_value = $(this).text();
    let hospital_name = $('#hosptital_name');
    $(hospital_name).html(hospital_name_value);
    // cur_select.attr('value', this.getAttribute('value'));
    // cur_select.attr('id', 'selected_hospital');
    if (hospital_name_value == 'Здесь нет моей больницы') {
      $.magnificPopup.open({
        items: {
          src: '<div class="white-popup custom_styles_popup">Если больницы, в которую вы обратились, в списке нет, значит, она не является участником системы ОМС и не несет обязательств по оказанию помщи по полису ОМС. Случай не страховой.</div>',
          type: 'inline',
        },
      });
      $('#choose_class').css({'pointer-events': 'none'});
    } else {
      $('#choose_class').removeAttr('style');
    }
  });

  $(document).on('click', '#empty_class', function() {
    $.magnificPopup.open({
      items: {
        src: '<div class="white-popup custom_styles_popup">Если среди диагнозов вы не нашли свой, значит заболевание не относится к числу тех, что оплачитваются из средства ОМС. Случай не является страховым.</div>',
        type: 'inline',
      },
    });
  });
  $(document).on('click', '#empty_group', function() {
    $.magnificPopup.open({
      items: {
        src: '<div class="white-popup custom_styles_popup">Если среди диагнозов вы не нашли свой, значит заболевание не относится к числу тех, что оплачитваются из средства ОМС. Случай не является страховым.</div>',
        type: 'inline',
      },
    });
  });
  $(document).on('click', '#empty_subgroup', function() {
    $.magnificPopup.open({
      items: {
        src: '<div class="white-popup custom_styles_popup">Если среди диагнозов вы не нашли свой, значит заболевание не относится к числу тех, что оплачитваются из средства ОМС. Случай не является страховым.</div>',
        type: 'inline',
      },
    });
  });
  $(document).on('click', '#empty_diagnoz', function() {
    $.magnificPopup.open({
      items: {
        src: '<div class="white-popup custom_styles_popup">Если среди диагнозов вы не нашли свой, значит заболевание не относится к числу тех, что оплачитваются из средства ОМС. Случай не является страховым.</div>',
        type: 'inline',
      },
    });
  });

});

function search_class() {
  // ------ choose_class ------
  $(document).on('click', '#search_result_class li', function() {
    $('#class_input').val($(this).text());
    $('#search_result_class').fadeOut()
  });
  $(document).on('click', '#class_input', function() {
    $('#search_result_class').css('display', 'block');
  });
  $(document).mouseup(function(e) {
    let container = $('#class_input');
    if (container.has(e.target).length === 0) {
      $('#search_result_class').fadeOut();
    }
  });

  $(document).on('click', '.class-js', function() {
    let id_class = $(this).attr('value');
    let component = $('#grid');
    $.post('/ajax/main_form_oms.php', {id: id_class}, function(result) {
      $(component).html(result);
      search_group();
    }, 'html');
  });
}
function search_group() {
  // ------ choose_group ------
  $(document).on('click', '#search_result_group li', function() {
    $('#group_input').val($(this).text());
    $('#search_result_group').fadeOut()
  });
  $(document).on('click', '#group_input', function() {
    $('#search_result_group').css('display', 'block');
  });
  $(document).mouseup(function(e) {
    let container = $('#group_input');
    if (container.has(e.target).length === 0) {
      $('#search_result_group').fadeOut();
    }
  });
  $(document).on('click', '.group-js', function() {
    let id_group = $(this).attr('value');
    let component = $('#grid');
    $.post('/ajax/main_form_oms.php', {id: id_group}, function(result) {
      $(component).html(result);
      console.log('stage 2');
      search_subgroup()
    }, 'html');
  });

  keyup_class();
  let id_class = $('#class_input').attr('data-id_class');
  keyup_group(id_class);

}
function search_subgroup() {
  // ------ choose_subgroup ------
  $(document).on('click', '#search_result_subgroup li', function() {
    $('#subgroup_input').val($(this).text());
    $('#search_result_subgroup').fadeOut()
  });
  $(document).on('click', '#subgroup_input', function() {
    $('#search_result_subgroup').css('display', 'block');
  });
  $(document).mouseup(function(e) {
    let container = $('#subgroup_input');
    if (container.has(e.target).length === 0) {
      $('#search_result_subgroup').fadeOut();
    }
  });

  $(document).on('click', '.subgroup-js', function() {
    let id_subgroup = $(this).attr('value');
    let component = $('#grid');
    $.post('/ajax/main_form_oms.php', {id: id_subgroup}, function(result) {
      $(component).html(result);
      console.log('stage 3');
      search_diagnoz()
    }, 'html');
  });
  keyup_class();
  let id_class = $('#class_input').attr('data-id_class');
  keyup_group(id_class);
  let id_group = $('#group_input').attr('data-id_group');
  keyup_subgroup(id_group);

}
function search_diagnoz() {
  // ------ choose_diagnoz ------
  $(document).on('click', '#search_result_diagnoz li', function() {
    $('#diagnoz_input').val($(this).text());
    $('#diagnoz_input').attr('data-id_diagnoz', $(this).attr('value'));
    $('#search_result_diagnoz').fadeOut();

  });
  $(document).on('click', '#diagnoz_input', function() {
    $('#search_result_diagnoz').css('display', 'block');
  });
  $(document).mouseup(function(e) {
    let container = $('#diagnoz_input');
    if (container.has(e.target).length === 0) {
      $('#search_result_diagnoz').fadeOut();
    }
  });

  keyup_class();
  let id_class = $('#class_input').attr('data-id_class');
  keyup_group(id_class);
  let id_group = $('#group_input').attr('data-id_group');
  keyup_subgroup(id_group);
  let id_subgroup = $('#subgroup_input').attr('data-id_subgroup');
  keyup_diagnoz(id_subgroup);

}

function keyup_class() {
  console.log('keyup_class');
  $('#class_input').on('keyup', function() {
    var $this = $(this);
    var delay = 500;
    clearTimeout($this.data('timer'));
    $this.data('timer', setTimeout(function() {
      $this.removeData('timer');
      $.post('/ajax/smart_search_class.php', {name: $this.val()}, function(msg) {
        console.log('search');
        if ($('.error_class').length != 0) {
          $('.error_class').remove();
        }
        $('.class-js').each(function() {
          $(this).remove();
        });
        if (msg == 'error_class') {
          if ($('.error_class').length != 0) {
            $('.error_class').remove();
            $('#search_result_class').
                append('<li class="error_class">Класс не найден</li>');
          } else {
            $('#search_result_class').
                append('<li class="error_class">Класс не найден</li>');
          }
        } else {
          setTimeout(function() {
            $('#search_result_class').append(msg);
          }, 100);
          $(document).on('click', '.class-js', function() {
            let id = $(this).attr('value');
            let select_region = $(this).text();
            $('#class_input').val(select_region);
            $('#class_input').attr('data-id_class', id);
            let component = $('#grid');
            $.ajax({
              dataType: 'html',
              url: '/ajax/main_form_oms.php',
              type: 'POST',
              data: {id: id},
              beforeSend: function() {
              },
              success: function(result) {
                $(component).html(result);
                keyup_class();
                let id_class = $('#class_input').attr('data-id_class');
                keyup_group(id_class);
              },
            }).done(function(msg) {

            });
          });
        }
      });
    }, delay));
  });
}
function keyup_group(id_class) {
  console.log('keyup_group');
  $('#group_input').on('keyup', function() {
    var $this = $(this);
    var delay = 500;
    clearTimeout($this.data('timer'));
    $this.data('timer', setTimeout(function() {
      $this.removeData('timer');
      $.post('/ajax/smart_search_group.php', {name: $this.val(), id: id_class
      }, function(msg) {
        if ($('.error_group').length != 0) {
          $('.error_group').remove();
        }
        $('.group-js').each(function() {
          $(this).remove();
        });
        if (msg == 'error_group') {
          if ($('.error_group').length != 0) {
            $('.error_group').remove();
            $('#search_result_group').
                append('<li class="error_group">Группа не найдена</li>');
          } else {
            $('#search_result_group').
                append('<li class="error_group">Группа не найдена</li>');
          }
        } else {
          setTimeout(function() {
            $('#search_result_group').append(msg);
          }, 100);
          $(document).on('click', '.group-js', function() {
            let id = $(this).attr('value');
            let select_region = $(this).text();
            $('#group_input').val(select_region);
            $('#group_input').attr('data-id_group', id);
            let component = $('#grid');
            $.ajax({
              dataType: 'html',
              url: '/ajax/main_form_oms.php',
              type: 'POST',
              data: {id: id},
              beforeSend: function() {
              },
              success: function(result) {
                $(component).html(result);
                keyup_class();
                let id_class = $('#class_input').attr('data-id_class');
                keyup_group(id_class);
                let id_group = $('#group_input').attr('data-id_group');
                keyup_subgroup(id_group);
              },
            }).done(function(msg) {
            });
          });
        }
      });
    }, delay));
  });
}
function keyup_subgroup(id_group) {
  console.log('keyup_subgroup');
  $('#subgroup_input').on('keyup', function() {
    var $this = $(this);
    var delay = 500;
    clearTimeout($this.data('timer'));
    $this.data('timer', setTimeout(function() {
      $this.removeData('timer');
      $.post('/ajax/smart_search_subgroup.php', {name: $this.val(), id: id_group
      }, function(msg) {
        if ($('.error_subgroup').length != 0) {
          $('.error_subgroup').remove();
        }
        $('.subgroup-js').each(function() {
          $(this).remove();
        });
        if (msg == 'error_subgroup') {
          if ($('.error_subgroup').length != 0) {
            $('.error_subgroup').remove();
            $('#search_result_subgroup').
                append('<li class="error_subgroup">Подгруппа не найдена</li>');
          } else {
            $('#search_result_subgroup').
                append('<li class="error_subgroup">Подгруппа не найдена</li>');
          }
        } else {
          setTimeout(function() {
            $('#search_result_subgroup').append(msg);
          }, 100);
          $(document).on('click', '.subgroup-js', function() {
            let id = $(this).attr('value');
            let select_region = $(this).text();
            $('#subgroup_input').val(select_region);
            $('#subgroup_input').attr('data-id_subgroup', id);
            let component = $('#grid');
            $.ajax({
              dataType: 'html',
              url: '/ajax/main_form_oms.php',
              type: 'POST',
              data: {id: id},
              beforeSend: function() {
              },
              success: function(result) {
                $(component).html(result);
                keyup_class();
                let id_class = $('#class_input').attr('data-id_class');
                keyup_group(id_class);
                let id_group = $('#group_input').attr('data-id_group');
                keyup_subgroup(id_group);
                let id_subgroup = $('#subgroup_input').attr('data-id_subgroup');
                keyup_diagnoz(id_subgroup);
              },
            }).done(function(msg) {
            });
          });
        }
      });
    }, delay));
  });
}
function keyup_diagnoz(id_subgroup) {
  console.log('keyup_diagnoz');
  $('#diagnoz_input').on('keyup', function() {
    var $this = $(this);
    var delay = 500;
    clearTimeout($this.data('timer'));
    $this.data('timer', setTimeout(function() {
      $this.removeData('timer');
      $.post('/ajax/smart_search_diagnoz.php', {name: $this.val(), id: id_subgroup
      }, function(msg) {
        if ($('.error_diagnoz').length != 0) {
          $('.error_diagnoz').remove();
        }
        $('.diagnoz-js').each(function() {
          $(this).remove();
        });
        if (msg == 'error_diagnoz') {
          if ($('.error_diagnoz').length != 0) {
            $('.error_diagnoz').remove();
            $('#search_result_diagnoz').
                append('<li class="error_diagnoz">Диагноз не найден</li>');
          } else {
            $('#search_result_diagnoz').
                append('<li class="error_diagnoz">Диагноз не найден</li>');
          }
        } else {
          setTimeout(function() {
            $('#search_result_diagnoz').append(msg);
          }, 100);
          $(document).on('click', '.diagnoz-js', function() {
            let id = $(this).attr('value');
            let select_region = $(this).text();
            $('#diagnoz_input').val(select_region);
            $('#diagnoz_input').attr('data-id_diagnoz', id);
          });
        }
      });
    }, delay));
  });
}





$(document).ready(function() {

  $(document).on("click",".diagnoz_search_js",function() {

    $("#grid").css({"pointer-events":"none"})
  });
  
  $(".years").click(function(){

    if( $("#selected_year").val() != "" ) {
      var year = $(this).attr("data-year");
      $("#selected_year").val(year);
      let component = $('#hospitals');
      $.ajax({
        dataType: 'html',
        url: '/ajax/form_hospitals.php',
        type: 'POST',
        data: {id: "",},
        beforeSend: function() {
        },
        success: function(result) {
          $(component).html(result);
          $('#region_name').html('Не выбрано');
          $('#hosptital_name').html('Не выбрано');
          $('#street_name').html('Не выбрано');
          $('#boss_name').html('Не выбрано');

          search_hospital();
          search_region();
        },
      }).done(function(msg) {
      });
    }else{
      var year = $(this).attr("data-year");
      $("#selected_year").val(year);
    }

  });


  search_region();
  search_class();
  keyup_class();
  search_diagnoz_global();
  keyup_diagnoz_global();
  $(document).on('click', '#search_result li', function() {
    $('#referal_forma').val($(this).text());
    $('#search_result').fadeOut();
  });
  $(document).on('click', '#referal_forma', function() {

    $('#search_result').css({'display': 'block'});
  });
  $(document).mouseup(function(e) {
    let container = $('#referal_forma');
    if (container.has(e.target).length === 0) {
      $('#search_result').fadeOut();
    }
  });
  $(document).on('click', '#search_result_hospital li', function() {
    $('#referal_two_forma').val($(this).text());
    $('#search_result_hospital').fadeOut();
  });

  $(document).on('click', '#referal_two_forma', function() {
    $('#search_result_hospital').css({'display': 'block'});
  });

  $(document).mouseup(function(e) {
    let container = $('#referal_two_forma');
    if (container.has(e.target).length === 0) {
      $('#search_result_hospital').fadeOut();
    }
  });
  $(document).on('click', '.region', function() {

    let id_region = $(this).attr('value');
    let select_region = $(this).text();
    $('#referal_forma').val(select_region);
    $('#referal_forma').attr('data-id_region', id_region);
    $('#region_name').text(select_region);
    $('#referal_forma').attr('data-region_check', 'check');
    let component = $('#hospitals');

    $.ajax({
      dataType: 'html',
      url: '/ajax/form_hospitals.php',
      type: 'POST',
      data: {id: id_region,year:$("#selected_year").val()},
      beforeSend: function() {
      },
      success: function(result) {
        $(component).html(result);

        $('#region_name').text(select_region);
        $('#hosptital_name').html('Не выбрано');
        $('#street_name').html('Не выбрано');
        $('#boss_name').html('Не выбрано');
        search_region();
        search_hospital();
      },
    }).done(function(msg) {
    });
  });

 function search_region() {

   $('#referal_forma').on('keyup', function() {

     var $this = $(this);
     $('#search_result').css({'display': 'block'});
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
             $('#search_result').append('<li class="error_region" >Регион не найден</li>');
           } else {

             $("#search_result").append('<li class="error_region" >Регион не найден</li>');
           }
         } else {
           setTimeout(function() {
             $('#search_result').append(msg);
           }, 100);
           $(document).on('click', '.region', function() {

             let id_region = $(this).attr('value');
             let select_region = $(this).text();
             $('#referal_forma').val(select_region);
             $('#referal_forma').attr('data-id_region', id_region);
             $('#referal_forma').attr('data-region_check', 'check');
             $('#region_name').text(select_region);
             let component = $('#hospitals');
             $.ajax({
               dataType: 'html',
               url: '/ajax/form_hospitals.php',
               type: 'POST',
               data: {id: id_region,year:$("#selected_year").val()},
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
    $('#referal_two_forma').val(select_region);
    $('#referal_two_forma').attr('data-id_region', id_region);
    $('#referal_two_forma').attr('data-region_check', 'check');
    $('#hosptital_name').text(select_region);
    $('#street_name').text(name_street);
    $('#boss_name').text(name_boss);



  });
  $('#referal_two_forma').on('keyup', function() {
    var $this = $(this);
    var delay = 500;
    if ($this.val() == '') {
      $('#hosptital_name').text('НЕ ВЫБРАНО');
      $this.attr("data-id_region","");
      $('#street_name').text('НЕ ВЫБРАНО');
      $('#boss_name').text('НЕ ВЫБРАНО');

    }
    $('#search_result_hospital').css({'display': 'block'});
    clearTimeout($this.data('timer'));
    $this.data('timer', setTimeout(function() {
      $this.removeData('timer');

      $.post('/ajax/smart_search_hospital.php', {name_hospital: $this.val(), region_id:$("#referal_forma").attr("data-id_region"),year:$("#selected_year").val() }, function(msg) {
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
            $('#referal_two_forma').val(select_region);
            $('#referal_two_forma').attr('data-id_region', id_region);
            $('#referal_two_forma').attr('data-region_check', 'check');
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

    let region = $('#referal_forma').attr('data-id_region');
    if(region == "" || region == undefined){
      $('#referal_forma').after(
          '<p class="label danger"  >Выберите регион</p>');
      error.push("error1");
      $('.error_step-card-3').addClass('error_block');
    }
    let hospital = $('#referal_two_forma').attr('data-id_region');
    if(hospital == "" || hospital == undefined){
      $('#referal_two_forma').after(
          '<p class="label danger"  >Выберите больницу</p>');
      error.push("erro2");
      $('.error_step-card-3').addClass('error_block');
    }
    if ($('#search_diagnoz_input').attr('data-value') === undefined) {
      var choose_class = $('#class_input').attr('data-id_class');
      if(choose_class == "" || choose_class == undefined){
        $('#class_input').after(
            '<p class="label danger absolute_label">Выберите класс</p>');
        error.push("error3");
        $('.error_step-card-4').addClass('error_block');
      }
      var choose_group = $('#group_input').attr('data-id_group');
      if(choose_group == "" || choose_group == undefined){
        $('#group_input').after(
            '<p class="label danger absolute_label">Выберите группу</p>');
        error.push("error4");
        $('.error_step-card-4').addClass('error_block');
      }
      var choose_subgroup = $('#subgroup_input').attr('data-id_subgroup');
      if(choose_subgroup == "" || choose_subgroup == undefined){
        $('#subgroup_input').after(
            '<p class="label danger absolute_label">Выберите подгруппу</p>');
        error.push("error5");
        $('.error_step-card-4').addClass('error_block');
      }
      var choose_diagnoz = $('#diagnoz_input').attr('data-id_diagnoz');
      if(choose_diagnoz == "" || choose_diagnoz == undefined){
        $('#diagnoz_input').after(
            '<p class="label danger absolute_label">Выберите диагноз</p>');
        error.push("error6");
        $('.error_step-card-4').addClass('error_block');
      }
    } else {
      choose_diagnoz = $('#search_diagnoz_input').attr('data-value');
    }
    let years = [];

    let div = $('#years');
    $(div).find('input:checked').each(function() {
      years.push(this.value);
    });
    if(years.length == 0 ){
      $(".wrap-chrckbox:first").after(
          '<p class="label danger"  >Выберите год</p>');
      error.push("error7");
    }
    let plan=[];
    let div_last = $('.plannet');
    $(div_last).find('input:checked').each(function() {
      plan.push(this.value);
    });


    if(plan.length == 0 ){
      div_last.after(
          '<p class="label danger"  >Не выбранно</p>');
      error.push("error8");
      $('.error_step-card-1').addClass('error_block');
    }
    console.log(error);
    if(error.length > 0){

    } else {
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
                $("#strax-sluchay").remove();
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
  $(document).on('click', '.error_region', function() {
    $.magnificPopup.open({
      items: {
        src: '<div class="white-popup custom_styles_popup">Если среди диагнозов вы не нашли свой, значит заболевание не относится к числу тех, что оплачитваются из средства ОМС. Случай не является страховым.</div>',
        type: 'inline',
      },
    });
  });



  $(document).on('click', '.error_class', function() {
    $.magnificPopup.open({
      items: {
        src: '<div class="white-popup custom_styles_popup">Если среди диагнозов вы не нашли свой, значит заболевание не относится к числу тех, что оплачитваются из средства ОМС. Случай не является страховым.</div>',
        type: 'inline',
      },
    });
  });
  $(document).on('click', '.error_group', function() {
    $.magnificPopup.open({
      items: {
        src: '<div class="white-popup custom_styles_popup">Если среди диагнозов вы не нашли свой, значит заболевание не относится к числу тех, что оплачитваются из средства ОМС. Случай не является страховым.</div>',
        type: 'inline',
      },
    });
  });
  $(document).on('click', '.error_subgroup', function() {
    $.magnificPopup.open({
      items: {
        src: '<div class="white-popup custom_styles_popup">Если среди диагнозов вы не нашли свой, значит заболевание не относится к числу тех, что оплачитваются из средства ОМС. Случай не является страховым.</div>',
        type: 'inline',
      },
    });
  });
  $(document).on('click', '.error_diagnoz', function() {
    $.magnificPopup.open({
      items: {
        src: '<div class="white-popup custom_styles_popup">Если среди диагнозов вы не нашли свой, значит заболевание не относится к числу тех, что оплачитваются из средства ОМС. Случай не является страховым.</div>',
        type: 'inline',
      },
    });
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

    if($(this).val() !== ""){
      $("#search_diagnoz_input").css({"pointer-events":"none"})
    }
    let component = $('#grid');
    $.post('/ajax/main_form_oms.php', {id: id_class}, function(result) {
      $(component).html(result);
      search_group();
      $('#search_diagnoz_input').val('');
      $('#search_diagnoz_input').attr('data-value', '');
      $('#search_diagnoz_global').empty();
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
      search_subgroup();
      $('#search_diagnoz_input').val('');
      $('#search_diagnoz_input').attr('data-value', '');
      $('#search_diagnoz_global').empty();
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
      search_diagnoz()
      $('#search_diagnoz_input').val('');
      $('#search_diagnoz_input').attr('data-value', '');
      $('#search_diagnoz_global').empty();
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
    $('#search_diagnoz_input').val($(this).text());
    $('#search_diagnoz_input').attr('data-value', $(this).attr('value'));
    $('#search_diagnoz_global').empty();
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
    if($this.val() !== ""){
      $("#search_diagnoz_input").css({"pointer-events":"none"})
    }else{
      $("#search_diagnoz_input").css({"pointer-events":"auto"})
    }

    var delay = 500;
    $('#search_result_class').css({'display': 'block'});
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

            if($(this).val() !== ""){
              $("#search_diagnoz_input").css({"pointer-events":"none"})
            }
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
    $('#search_result_group').css({'display': 'block'});
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
    $('#search_result_subgroup').css({'display': 'block'});
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
    $('#search_result_diagnoz').css({'display': 'block'});
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
function search_diagnoz_global() {
  // ------ choose_class ------
  $(document).on('click', '#search_diagnoz_global li', function() {
    $('#search_diagnoz_input').val($(this).text());
    $('#search_diagnoz_input').attr('data-value', $(this).attr('value'));
    $('#search_diagnoz_global').fadeOut();
    after_global_search(
        $(this).attr('data-section'),
        $(this).text(),
        $(this).attr('value'));
    $('#diagnoz_input').val($(this).text());
    $('#diagnoz_input').attr('data-id_diagnoz', $(this).attr('value'));
  });
  $(document).on('click', '#search_diagnoz_input', function() {
    $('#search_diagnoz_global').css('display', 'block');
  });
  $(document).mouseup(function(e) {
    let container = $('#search_diagnoz_input');
    if (container.has(e.target).length === 0) {
      $('#search_diagnoz_global').fadeOut();
    }
  });
}
function keyup_diagnoz_global() {
  $('#search_diagnoz_input').on('keyup', function() {
    var $this = $(this);
    if($this.val() === ""){
      $("#grid").css({"pointer-events":"auto"})
    }
    var delay = 500;
    clearTimeout($this.data('timer'));
    $this.data('timer', setTimeout(function() {
      $this.removeData('timer');
      $('#search_diagnoz_global').css('display', 'block');
      $.post('/ajax/forma-obrashenija/keyup_diagnoz_global.php', {name: $this.val(),
      }, function(msg) {
        if ($('.error_diagnoz').length != 0) {
          $('.error_diagnoz').remove();
        }
        $('.diagnoz_search_js').each(function() {
          $(this).remove();
        });
        if (msg == 'error') {
          if ($('.error_diagnoz').length != 0) {
            $('.error_diagnoz').remove();
            $('#search_diagnoz_global').
                append('<li class="error_diagnoz">Диагноз не найден</li>');
          } else {
            $('#search_diagnoz_global').
                append('<li class="error_diagnoz">Диагноз не найден</li>');
          }
        } else {
          setTimeout(function() {
            $('#search_diagnoz_global').append(msg);
          }, 100);
          $(document).on('click', '.diagnoz_search_js', function() {
            $('#search_diagnoz_input').val($(this).text());
            $('#search_diagnoz_input').attr('data-value', $(this).attr('value'));
            after_global_search(
                $(this).attr('data-section'),
                $(this).text(),
                $(this).attr('value'));
          });
        }
      });
    }, delay));
  });
}

function after_global_search (SECTION_ID, TEXT, VALUE) {
  let component = $('#grid');
  $.post('/ajax/main_form_oms.php', {id: SECTION_ID},
      function(result) {
          $(component).html(result);
          $('#diagnoz_input').val(TEXT);
          $('#diagnoz_input').attr('data-id_diagnoz', VALUE);
          search_group();
          search_subgroup();
          search_diagnoz();
  }, 'html');

}
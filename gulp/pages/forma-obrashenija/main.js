//= ../../node_modules/jquery/dist/jquery.min.js
//= ../../node_modules/magnific-popup/dist/jquery.magnific-popup.min.js

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
  $('#referal').on('keyup', function() {
    var $this = $(this);
    var delay = 500;
    if ($this.val() == '') {
      $('#region_name').text('НЕ ВЫБРАНО');
    }

    clearTimeout($this.data('timer'));

    $this.data('timer', setTimeout(function() {

      $this.removeData('timer');

      $.post('/ajax/smart_search.php', {name_city: $this.val()}, function(msg) {
        if ($('.error_region').length != 0) {
          $('.error_region').remove();
        }
        $('.custom-serach__items_item').each(function() {
          $(this).remove();
        });
        if (msg == 'error_region') {
          if ($('.error_region').length != 0) {
            $('.error_region').remove();
            $('#search_result').
                append('<li class="error_region" >Город не найден</li>');
          } else {
            $('#search_result').
                append('<li class="error_region" >Город не найден</li>');
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
              },
            }).done(function(msg) {

            });
          });
        }

      });

    }, delay));

  });


function search_hospital() {



  $(document).on('click', '.hospital', function() {
    console.log("3232");
    console.log($(this));
    let id_region = $(this).attr('value');
    let select_region = $(this).text();
    $('#referal_two').val(select_region);
    $('#referal_two').attr('data-id_region', id_region);
    $('#referal_two').attr('data-region_check', 'check');
    $('#hosptital_name').text(select_region);


  });
  $('#referal_two').on('keyup', function() {

    var $this = $(this);
    var delay = 500;
    if ($this.val() == '') {
      $('#hosptital_name').text('НЕ ВЫБРАНО');
      $this.attr("data-id_region","");
    }

    clearTimeout($this.data('timer'));

    $this.data('timer', setTimeout(function() {

      $this.removeData('timer');

      $.post('/ajax/smart_search_hospital.php', {name_hospital: $this.val(), region_id:$("#referal").attr("data-id_region") }, function(msg) {
        if ($('.error_region').length != 0) {
          $('.error_region').remove();
        }
        $('.custom-serach__items_item').each(function() {
          $(this).remove();
        });
        if (msg == 'error_hospital') {
          if ($('.error_region').length != 0) {
            $('.error_region').remove();
            $('#search_result_hospital').
                append('<li class="error_region" >Больница не найдена</li>');
          } else {
            $('#search_result_hospital').
                append('<li class="error_region" >Больница не найдена</li>');
          }
        } else {
          setTimeout(function() {

            $('#search_result_hospital').append(msg);
          }, 100);


          $(document).on('click', '.hospital', function() {
            let id_region = $(this).attr('value');
            let select_region = $(this).text();
            $('#referal_two').val(select_region);
            $('#referal_two').attr('data-id_region', id_region);
            $('#referal_two').attr('data-region_check', 'check');
            $('#hosptital_name').text(select_region);


          });

        }

      });

    }, delay));

  });
}

  //create_select();

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
    let $form = $('#appeal-form');
    let $title = $('#page-title');
    let region = $('#choose_location_elem').attr('value');
    let hospital = $('#choose_hospital_elem').attr('value');
    let choose_class = $('#choose_class_elem').attr('value');
    let choose_group = $('#choose_group_elem').attr('value');
    let choose_subgroup = $('#choose_subgroup_elem').attr('value');
    let choose_diagnoz = $('#choose_diagnoz_elem').attr('value');

    let years = [];

    let div = $('#years');
    $(div).find('input:checked').each(function() {
      years.push(this.value);
    });
    if ($('#choose_diagnoz_elem').val() != 'Здесь нет моего диагноза') {
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
            console.log(diagnoz);
            if (diagnoz !== 'error') {

              $form.replaceWith(diagnoz['DIAGNOZ']);
              $title.html(diagnoz['FULL_NAME']);

              document.body.scrollTop = document.documentElement.scrollTop = 0;

              if ($('.header__r_auth_reg').length == 1) {
                $('#generate_form').removeAttr('href');
                $('#generate_form').click(function() {
                  $('.header__r_auth_reg').trigger('click');
                  setTimeout(function() {
                    $('.register_before_review').removeClass('hidden');
                  }, 700);
                });
              } else {
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
              }
            } else {
              $.magnificPopup.open({
                items: {
                  src: '<div class="white-popup custom_styles_popup">Вы заполнили не все данные</div>',
                  type: 'inline',
                },
              });

            }
          }, 'html');
    } else {
      $.magnificPopup.open({
        items: {
          src: '<div class="white-popup custom_styles_popup">Вы заполнили не все данные</div>',
          type: 'inline',
        },
      });
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

// function update_hospital_select() {
//   var x, i, j, selElmnt, a, b, c;
//   /* Look for any elements with the class "custom-select": */
//   x = document.getElementsByClassName('custom-select');
//   for (i = 0; i < 2; i++) {
//     selElmnt = x[i].getElementsByTagName('select')[0];
//     /* For each element, create a new DIV that will act as the selected item: */
//     a = document.createElement('DIV');
//     a.setAttribute('class', 'select-selected');
//     // a.setAttribute("date-value",);
//     a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
//     a.setAttribute('value', selElmnt.options[selElmnt.selectedIndex].value);
//     a.setAttribute('id', x[i].getAttribute('id') + '_elem');

//     x[i].appendChild(a);
//     /* For each element, create a new DIV that will contain the option list: */
//     b = document.createElement('DIV');
//     b.setAttribute('class', 'select-items select-hide');
//     for (j = 1; j < selElmnt.length; j++) {

//       /* For each option in the original select element,
//       create a new DIV that will act as an option item: */
//       c = document.createElement('DIV');
//       c.innerHTML = selElmnt.options[j].innerHTML;
//       c.setAttribute('value', selElmnt.options[j].value);
//       if (!!selElmnt.options[j].id) {
//         c.setAttribute('id', selElmnt.options[j].id);
//       } else {
//         c.setAttribute('id', 'option');
//       }
//       c.addEventListener('click', function(e) {
//         /* When an item is clicked, update the original select box,
//         and the selected item: */
//         var y, i, k, s, h;
//         s = this.parentNode.parentNode.getElementsByTagName('select')[0];
//         h = this.parentNode.previousSibling;

//         for (i = 0; i < s.length; i++) {

//           if (s.options[i].innerHTML == this.innerHTML) {
//             s.selectedIndex = i;
//             h.innerHTML = this.innerHTML;

//             if (h.clientWidth < 310) {
//               const str = h.textContent.slice(0, 15) + '...';
//               h.innerHTML = str;
//             } else {
//               return;
//             }

//             y = this.parentNode.getElementsByClassName('same-as-selected');

//             for (k = 0; k < y.length; k++) {
//               y[k].removeAttribute('class');
//             }
//             this.setAttribute('class', 'same-as-selected');
//             break;
//           }
//         }
//         h.click();
//       });
//       b.appendChild(c);
//     }
//     x[i].appendChild(b);
//     a.addEventListener('click', function(e) {
//       /* When the select box is clicked, close any other select boxes,
//       and open/close the current select box: */
//       e.stopPropagation();
//       closeAllSelect(this);
//       this.nextSibling.classList.toggle('select-hide');
//       this.classList.toggle('select-arrow-active');
//     });
//   }

//   /* If the user clicks anywhere outside the select box,
//   then close all select boxes: */
//   document.addEventListener('click', closeAllSelect);
// }

// function update_select() {
//   var x, i, j, selElmnt, a, b, c;
//   /* Look for any elements with the class "custom-select": */
//   x = document.getElementsByClassName('custom-select');
//   for (i = 2; i < x.length; i++) {
//     selElmnt = x[i].getElementsByTagName('select')[0];
//     /* For each element, create a new DIV that will act as the selected item: */
//     a = document.createElement('DIV');
//     a.setAttribute('class', 'select-selected');
//     // a.setAttribute("date-value",);
//     a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
//     a.setAttribute('value', selElmnt.options[selElmnt.selectedIndex].value);

//     a.setAttribute('id', x[i].getAttribute('id') + '_elem');
//     x[i].appendChild(a);
//     /* For each element, create a new DIV that will contain the option list: */
//     b = document.createElement('DIV');
//     b.setAttribute('class', 'select-items select-hide');
//     for (j = 1; j < selElmnt.length; j++) {

//       /* For each option in the original select element,
//       create a new DIV that will act as an option item: */
//       c = document.createElement('DIV');
//       c.innerHTML = selElmnt.options[j].innerHTML;
//       c.setAttribute('value', selElmnt.options[j].value);
//       if (!!selElmnt.options[j].id) {
//         c.setAttribute('id', selElmnt.options[j].id);
//       } else {
//         c.setAttribute('id', 'option');
//       }
//       c.addEventListener('click', function(e) {
//         /* When an item is clicked, update the original select box,
//         and the selected item: */
//         var y, i, k, s, h;
//         s = this.parentNode.parentNode.getElementsByTagName('select')[0];
//         h = this.parentNode.previousSibling;

//         for (i = 0; i < s.length; i++) {

//           if (s.options[i].innerHTML == this.innerHTML) {
//             s.selectedIndex = i;
//             h.innerHTML = this.innerHTML;

//             if (h.clientWidth < 310) {
//               const str = h.textContent.slice(0, 15) + '...';
//               h.innerHTML = str;
//             } else {
//               return;
//             }

//             y = this.parentNode.getElementsByClassName('same-as-selected');

//             for (k = 0; k < y.length; k++) {
//               y[k].removeAttribute('class');
//             }
//             this.setAttribute('class', 'same-as-selected');
//             break;
//           }
//         }
//         h.click();
//       });
//       b.appendChild(c);
//     }
//     x[i].appendChild(b);
//     a.addEventListener('click', function(e) {
//       /* When the select box is clicked, close any other select boxes,
//       and open/close the current select box: */
//       e.stopPropagation();
//       closeAllSelect(this);
//       this.nextSibling.classList.toggle('select-hide');
//       this.classList.toggle('select-arrow-active');
//     });
//   }

//   /* If the user clicks anywhere outside the select box,
//   then close all select boxes: */
//   document.addEventListener('click', closeAllSelect);
// }

// function create_select() {
//   var x, i, j, selElmnt, a, b, c;
//   /* Look for any elements with the class "custom-select": */
//   x = document.getElementsByClassName('custom-select');
//   for (i = 0; i < x.length; i++) {
//     selElmnt = x[i].getElementsByTagName('select')[0];
//     /* For each element, create a new DIV that will act as the selected item: */
//     a = document.createElement('DIV');
//     a.setAttribute('class', 'select-selected');
//     // a.setAttribute("date-value",);
//     a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
//     x[i].appendChild(a);
//     /* For each element, create a new DIV that will contain the option list: */
//     b = document.createElement('DIV');
//     b.setAttribute('class', 'select-items select-hide');
//     for (j = 1; j < selElmnt.length; j++) {

//       /* For each option in the original select element,
//       create a new DIV that will act as an option item: */
//       c = document.createElement('DIV');
//       c.innerHTML = selElmnt.options[j].innerHTML;
//       c.setAttribute('value', selElmnt.options[j].value);
//       if (!!selElmnt.options[j].id) {
//         c.setAttribute('id', selElmnt.options[j].id);
//       } else {
//         c.setAttribute('id', 'option');
//       }

//       c.addEventListener('click', function(e) {
//         /* When an item is clicked, update the original select box,
//         and the selected item: */
//         var y, i, k, s, h;
//         s = this.parentNode.parentNode.getElementsByTagName('select')[0];
//         h = this.parentNode.previousSibling;

//         for (i = 0; i < s.length; i++) {

//           if (s.options[i].innerHTML == this.innerHTML) {
//             s.selectedIndex = i;
//             h.innerHTML = this.innerHTML;

//             if (h.clientWidth < 310) {
//               const str = h.textContent.slice(0, 15) + '...';
//               h.innerHTML = str;
//             } else {
//               return;
//             }

//             y = this.parentNode.getElementsByClassName('same-as-selected');

//             for (k = 0; k < y.length; k++) {
//               y[k].removeAttribute('class');
//             }
//             this.setAttribute('class', 'same-as-selected');
//             break;
//           }
//         }
//         h.click();
//       });
//       b.appendChild(c);
//     }
//     x[i].appendChild(b);
//     a.addEventListener('click', function(e) {
//       /* When the select box is clicked, close any other select boxes,
//       and open/close the current select box: */
//       e.stopPropagation();
//       closeAllSelect(this);
//       this.nextSibling.classList.toggle('select-hide');
//       this.classList.toggle('select-arrow-active');
//     });
//   }

//   /* If the user clicks anywhere outside the select box,
//   then close all select boxes: */
//   document.addEventListener('click', closeAllSelect);
// }

// function closeAllSelect(elmnt) {
//   /* A function that will close all select boxes in the document,
//   except the current select box: */
//   var x, y, i, arrNo = [];
//   x = document.getElementsByClassName('select-items');
//   y = document.getElementsByClassName('select-selected');
//   for (i = 0; i < y.length; i++) {
//     if (elmnt == y[i]) {
//       arrNo.push(i);
//     } else {
//       y[i].classList.remove('select-arrow-active');
//     }
//   }
//   for (i = 0; i < x.length; i++) {
//     if (arrNo.indexOf(i)) {
//       x[i].classList.add('select-hide');
//     }
//   }
// }



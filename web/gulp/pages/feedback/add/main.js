// var x, i, j, selElmnt, a, b, c;
// /* Look for any elements with the class "custom-select": */
// x = document.getElementsByClassName("custom-select");
// for (i = 0; i < x.length; i++) {
//     selElmnt = x[i].getElementsByTagName("select")[0];
//     /* For each element, create a new DIV that will act as the selected item: */
//     a = document.createElement("DIV");
//     a.setAttribute("class", "select-selected");
//     a.innerHTML = selElmnt.options[selElmnt.selectedIndex].innerHTML;
//
//     x[i].appendChild(a);
//     /* For each element, create a new DIV that will contain the option list: */
//     b = document.createElement("DIV");
//     b.setAttribute("class", "select-items select-hide");
//     for (j = 1; j < selElmnt.length; j++) {
//
//         /* For each option in the original select element,
//         create a new DIV that will act as an option item: */
//         c = document.createElement("DIV");
//
//         c.innerHTML = selElmnt.options[j].innerHTML;
//
//         c.setAttribute("data-id-cite",selElmnt.options[j].value);
//         c.setAttribute("class","select-city");
//         c.addEventListener("click", function (e) {
//             /* When an item is clicked, update the original select box,
//             and the selected item: */
//             var y, i, k, s, h;
//             s = this.parentNode.parentNode.getElementsByTagName("select")[0];
//             h = this.parentNode.previousSibling;
//
//             for (i = 0; i < s.length; i++) {
//                 if (s.options[i].innerHTML == this.innerHTML) {
//                     s.selectedIndex = i;
//                     h.innerHTML = this.innerHTML;
//
//                     if (h.clientWidth < 310) {
//                         const str = h.textContent.slice(0, 15) + "...";
//                         h.innerHTML = str;
//                     } else {
//                         return;
//                     }
//
//                     y = this.parentNode.getElementsByClassName("same-as-selected");
//
//                     for (k = 0; k < y.length; k++) {
//                         y[k].removeAttribute("class");
//                     }
//                     this.setAttribute("class", "same-as-selected");
//                     break;
//                 }
//             }
//             h.click();
//         });
//         b.appendChild(c);
//     }
//     x[i].appendChild(b);
//     a.addEventListener("click", function (e) {
//         /* When the select box is clicked, close any other select boxes,
//         and open/close the current select box: */
//         e.stopPropagation();
//         closeAllSelect(this);
//         this.nextSibling.classList.toggle("select-hide");
//         this.classList.toggle("select-arrow-active");
//     });
// }
//
// function closeAllSelect(elmnt) {
//     /* A function that will close all select boxes in the document,
//     except the current select box: */
//     var x, y, i, arrNo = [];
//     x = document.getElementsByClassName("select-items");
//     y = document.getElementsByClassName("select-selected");
//     for (i = 0; i < y.length; i++) {
//         if (elmnt == y[i]) {
//             arrNo.push(i)
//         } else {
//             y[i].classList.remove("select-arrow-active");
//         }
//     }
//     for (i = 0; i < x.length; i++) {
//         if (arrNo.indexOf(i)) {
//             x[i].classList.add("select-hide");
//         }
//     }
// }

/* If the user clicks anywhere outside the select box,
then close all select boxes: */
// document.addEventListener('click', closeAllSelect);

var data = {
  'id_city': '0',
  'id_compani': '0',
  'head': '0',
  'text': '0',
  'star': '0',
  'letter': '0',
  'kpp': '0',
  'name_user_no_Authorized': '',
};
$('.star').click(function() {
  data.star = $(this).attr('data-value');
});

// $(document).on("click",".custom-select-js-cite > .select-items > div",function() {
//
//   data.id_city = $(this).attr('data-id-cite');
//
//   $.ajax({
//     url: "/ajax/form_city.php",
//     type: 'POST',
//     data: data,
//     dataType: "html",
//     beforeSend: function() {
//       $(".item_select").each(function() {
//         $(this).remove();
//       })
//     }
//   }).done(function(msg) {
//     let list_compani = JSON.parse(msg);
//
//     list_compani.forEach(function(item) {
//         $(".select-items").append('<div class="item_select" data-id-company="'+item.ID+'" >'+item.NAME+'</div>');
//      });
//         $(".custom-select-js").removeClass("no_click");
//   });
//
//
// });
// $(document).on("click",".item_select",function() {
//     let name_company_div = $(this).text();
//     $(".select-arrow-active").text(name_company_div);
//
//   data.id_compani = $(this).attr('data-id-company');
//
// });

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
      $this.attr('data-id_region', '');
      $('.hospital').each(function() {
        $(this).remove();
      });

      $('#hospital').remove();
      $('#referal_two').val('');
      $('#referal_two').attr('data-id_region', '');
    }
    $('#search_result').css({'display': 'block'});
    clearTimeout($this.data('timer'));
    $this.data('timer', setTimeout(function() {
      $this.removeData('timer');
      $.post('/ajax/personal-cabinet/search_region.php',
          {name_city: $this.val()}, function(msg) {
            if ($('.error_region').length != 0) {
              $('.error_region').remove();
            }
            $('.region').each(function() {
              $(this).remove();
            });
            if (msg == 'error_region') {

              if ($('.error_region').length != 0) {
                $('.error_region').remove();
                $('#search_result').
                    append('<li class="error_region" >Регион не найден</li>');
              } else {
                $('#search_result').
                    append('<li class="error_region" >Регион не найден</li>');
              }
            } else {
              setTimeout(function() {
                $('#search_result').append(msg);
              }, 100);
            }
          });
    }, delay));
  });

  $(".region-select").click(function () {
    $(".search-second").css({"pointer-events":"inherit"});
    let id_region = $(this).attr('value');
    $('#referal').attr('data-id_region', id_region);
    $('#referal').attr('data-region_check', 'check');
    $('#referal_two').val('');
    $('#region_select_id').attr('value', id_region);

    $.post("/reviews/region-select", {
      region_id:$("#referal").attr("data-id_region")
    }, function(msg) {
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

  $('body').on('click', '.company-select-item', function () {
    let id_company = $(this).attr('value');
    $('#company_select_id').attr('value', id_company);
  });

  $('.star-item').click(function () {
    let star = $(this).attr('data-value');
    $('#rating_select').attr('value', star);
  });

  $(document).on('click', '.region', function() {

    let id_region = $(this).attr('value');
    $('#referal').attr('data-id_region', id_region);
    $('#referal').attr('data-region_check', 'check');
    $('#referal_two').attr('data-id_region', '0');
    $('#referal_two').attr('data-id_region_kpp', '0');
    $('#referal_two').val('');

    $.post('/ajax/personal-cabinet/search_company.php',
        {region_id: $('#referal').attr('data-id_region')}, function(msg) {
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
    let kpp_region = $(this).attr('data-kpp');
    let select_region = $(this).text();
    $('#referal_two').val(select_region);
    $('#referal_two').attr('data-id_region', id_region);
    $('#referal_two').attr('data-id_region_kpp', kpp_region);
    $('#referal_two').attr('data-region_check', 'check');
  });

  $('#referal_two').on('keyup', function() {
    var $this = $(this);
    var delay = 500;
    if ($this.val() == '') {
      $this.attr('data-id_region', '');
    }
    $('#search_result_hospital').css({'display': 'block'});
    clearTimeout($this.data('timer'));
    $this.data('timer', setTimeout(function() {
      $this.removeData('timer');
      $.post('/ajax/personal-cabinet/search_company.php', {
        name_hospital: $this.val(),
        region_id: $('#referal').attr('data-id_region'),
      }, function(msg) {
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
            $('#search_result_hospital').
                append('<li class="error_region" >Компания не найдена</li>');
            $('.hospital-empty').remove();

          } else {
            $('#search_result_hospital').
                append('<li class="error_region" >Компания не найдена</li>');
            $('.hospital-empty').remove();
          }
        } else {
          setTimeout(function() {
            $('#search_result_hospital').append(msg);
          }, 100);

        }
      });
    }, delay));
  });

  $(document).on('click', '.hospital', function() {
    let id_region = $(this).attr('value');
    let select_region = $(this).text();
    $('#referal_two').val(select_region);
    $('#referal_two').attr('data-id_region', id_region);
    $('#referal_two').attr('data-region_check', 'check');

  });
  var obj_review = {
    empty_field: [],
    user_Authorized: function(data_form) {

      let region = $('#referal').attr('data-id_region');
      if (region == '' || region == undefined) {
        $('#referal').after('   <div class="popover_error">\n' +
            '          <div class="popover_error_arrow"></div>\n' +
            '          <div class="popover_error_image">\n' +
            '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
            '          </div>\n' +
            '          <div class="popover_error_text">\n' +
            '        Выберите регион.\n' +
            '      </div>\n' +
            '      </div>');
        this.empty_field.push('error');
      } else {
        data.id_city = region;
      }
      let hospital = $('#referal_two').attr('data-id_region');
      if (hospital === '' || hospital === undefined || hospital === '0') {
        $('#referal_two').after('   <div class="popover_error">\n' +
            '          <div class="popover_error_arrow"></div>\n' +
            '          <div class="popover_error_image">\n' +
            '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
            '          </div>\n' +
            '          <div class="popover_error_text">\n' +
            '        Выберите компанию.\n' +
            '      </div>\n' +
            '      </div>');

        this.empty_field.push('error');
      } else {
        let kpp = $('#referal_two').attr('data-id_region_kpp');
        data.kpp = kpp;
        data.id_compani = hospital;
      }

      if(data_form[0]['value'] === "" ){
        $("#"+data_form[0]["name"]).after('   <div class="popover_error">\n' +
            '          <div class="popover_error_arrow"></div>\n' +
            '          <div class="popover_error_image">\n' +
            '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
            '          </div>\n' +
            '          <div class="popover_error_text">\n' +
            '        Заполните заголовок.\n' +
            '      </div>\n' +
            '      </div>');
        this.empty_field.push('error');
      }else{
        data.head = data_form[0]['value'];
      }
      if(data_form[1]['value'] === "" ){
        $("#"+data_form[1]["name"]).after('   <div class="popover_error">\n' +
            '          <div class="popover_error_arrow"></div>\n' +
            '          <div class="popover_error_image">\n' +
            '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
            '          </div>\n' +
            '          <div class="popover_error_text">\n' +
            '        Заполните описание.\n' +
            '      </div>\n' +
            '      </div>');
        this.empty_field.push('error');
      }else{
        data.text = data_form[1]['value'];
      }
      if (data.id_city === 0) {
        $('[data-select=city]').next().css({'display': 'block'});
        this.empty_field.push('city');
      }
      if (data.id_compani === 0) {
        $('[data-select=company]').next().css({'display': 'block'});
        this.empty_field.push('company');
      }

      if (getCookie('letter') !== undefined) {
        data.letter = '1';
      }


    },
    user_no_Authorized: function(data_form) {



      let region = $('#referal').attr('data-id_region');
      if (region == '' || region == undefined) {
        $('#referal').after('   <div class="popover_error">\n' +
            '          <div class="popover_error_arrow"></div>\n' +
            '          <div class="popover_error_image">\n' +
            '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
            '          </div>\n' +
            '          <div class="popover_error_text">\n' +
            '        Выберите регион.\n' +
            '      </div>\n' +
            '      </div>');
        this.empty_field.push('error');
      } else {
        data.id_city =region;
      }

      let hospital = $('#referal_two').attr('data-id_region');
      if (hospital === '' || hospital === undefined || hospital === '0') {
        $('#referal_two').after('   <div class="popover_error">\n' +
            '          <div class="popover_error_arrow"></div>\n' +
            '          <div class="popover_error_image">\n' +
            '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
            '          </div>\n' +
            '          <div class="popover_error_text">\n' +
            '        Выберите компанию.\n' +
            '      </div>\n' +
            '      </div>');

        this.empty_field.push('error');
      } else {
        let kpp = $('#referal_two').attr('data-id_region_kpp');
        data.kpp = kpp;
        data.id_compani = hospital;
      }


      if(data_form[0]['value'] === "" ){
        $("#"+data_form[0]["name"]).after('   <div class="popover_error">\n' +
            '          <div class="popover_error_arrow"></div>\n' +
            '          <div class="popover_error_image">\n' +
            '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
            '          </div>\n' +
            '          <div class="popover_error_text">\n' +
            '        Введите свое имя.\n' +
            '      </div>\n' +
            '      </div>');
        this.empty_field.push('error');
      }else{
        data.name_user_no_Authorized = data_form[0]['value'];
      }

      if(data_form[1]['value']  === "" ){
        $("#"+data_form[1]["name"]).after('   <div class="popover_error">\n' +
            '          <div class="popover_error_arrow"></div>\n' +
            '          <div class="popover_error_image">\n' +
            '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
            '          </div>\n' +
            '          <div class="popover_error_text">\n' +
            '        Заполните заголовок.\n' +
            '      </div>\n' +
            '      </div>');
        this.empty_field.push('error');
      }else{
        data.head = data_form[1]['value'];
      }
      if(data_form[2]['value'] === ""){
        $("#"+data_form[2]["name"]).after('   <div class="popover_error">\n' +
            '          <div class="popover_error_arrow"></div>\n' +
            '          <div class="popover_error_image">\n' +
            '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
            '          </div>\n' +
            '          <div class="popover_error_text">\n' +
            '        Заполните описание.\n' +
            '      </div>\n' +
            '      </div>');
        this.empty_field.push('error');
      }else{
        data.text = data_form[2]['value'];
      }



      if (data.id_city === 0) {
        $('[data-select=city]').next().css({'display': 'block'});
        this.empty_field.push('city');
      }
      if (data.id_compani === 0) {
        $('[data-select=company]').next().css({'display': 'block'});
        this.empty_field.push('company');
      }

      if (getCookie('letter') !== undefined) {
        data.letter = '1';
      }

    },

    send_data: function(data) {

      $.ajax({
        url: '/ajax/add_reviews.php',
        type: 'POST',
        data: data,
        dataType: 'html',
        beforeSend: function() {
          delete_cookie('letter');

        },

      }).done(function(msg) {
        if (msg == 1) {
          $.magnificPopup.open({
            items: {
              src: '<div class="white-popup custom_styles_popup" >Спасибо за отзыв. Он будет опубликован после модерации.</div>',
              type: 'inline',
            },
            callbacks: {
              afterClose: function() {
                document.location.href = '/feedback/'
              },
            },
          });
        }
      });
    },
  };
  $('#submit').click(function(e) {
    e.preventDefault();

    var data_form = $('#form-comments').serializeArray();
    obj_review.empty_field = [];

    if ($('.header__r_auth_login').length > 0 &&
        $('.header__r_auth_reg').length > 0) {
      obj_review.user_no_Authorized(data_form);

      if (obj_review.empty_field.length === 0) {

        obj_review.send_data(data);

      }
    } else {
      obj_review.user_Authorized(data_form);

      if (obj_review.empty_field.length === 0) {

        obj_review.send_data(data);

      }
    }

  });
});

$("#name_user").on('keyup', function() {

  var $this = $(this);
  clearTimeout($this.data('timer'));
  $this.data('timer', setTimeout(function() {
    $this.removeData('timer');

 var str = "";



    if($this.val().length > 0) {

      var harder_str = $this.val().split(" ");


      for(var i= 0 ;i <harder_str.length ; i++){
        if(harder_str[i][0] !== undefined) {

          if(harder_str[i].search("-") !== -1){

              var harder_str_str = harder_str[i].split("-");

              for (var j = 0; j < harder_str_str.length; j++) {

                if (harder_str_str[j][0] !== undefined) {
                  var first_simvol_first_str_str = harder_str_str[j][0].toUpperCase();
                  var other_first_str_str = harder_str_str[j].slice(1).
                      toLowerCase();

                  str += first_simvol_first_str_str + other_first_str_str;
                  if (harder_str_str.length - 1 !== j) {

                    str += "-";
                  }
                }
              }


          }else {
            if (i == 0) {
              var first_simvol_first_str = harder_str[i][0].toUpperCase();
              var other_first_str = harder_str[i].slice(1).toLowerCase();

              str += first_simvol_first_str + other_first_str;
              console.log();
              if (harder_str.length - 1 !== i) {
                str += " ";
              }
            }else{
              var first_simvol_first_str = harder_str[i][0];
              var other_first_str = harder_str[i].slice(1);

              str += first_simvol_first_str + other_first_str;
              console.log();
              if (harder_str.length - 1 !== i) {
                str += " ";
              }
            }
          }
        }

      }
    }





    $this.val(str);

  },2200));
});


function delete_cookie(cookie_name) {
  var cookie_date = new Date();  // Текущая дата и время
  cookie_date.setTime(cookie_date.getTime() - 1);
  document.cookie = cookie_name += '=; expires=' + cookie_date.toGMTString();
}

function getCookie(name) {
  let matches = document.cookie.match(new RegExp(
      '(?:^|; )' + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') +
      '=([^;]*)',
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}
$('#name_user').bind('change keyup input click', function() {
  if (this.value.match(/[^а-яёА-ЯЁ\-\s\.]/g)) {
    this.value = this.value.replace(/[^а-яёА-ЯЁ\-\s\.]/g, '');
  }
});

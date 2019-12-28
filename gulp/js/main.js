//= ../../node_modules/jquery/dist/jquery.min.js
//= ../../node_modules/magnific-popup/dist/jquery.magnific-popup.min.js
//= ../../node_modules/jquery-mask-plugin/dist/jquery.mask.min.js
//= ../../node_modules/air-datepicker/dist/js/datepicker.min.js
$(document).ready(function() {

 var url =  window.location.pathname;

  if(url.search("/forma-obrashenija/") == -1){
    if($(".header__r_auth_reg").length != 0) {
      $(".header__r_auth_reg").attr("data-rigstration", "2");
    }
  }

  $(document).mouseup(function(e) {
    var div = $('.danger');
    if (!div.is(e.target)) {
      div.remove();
    }
    $('.error_step-card-1').removeClass('error_block');
    $('.error_step-card-3').removeClass('error_block');
    $('.error_step-card-4').removeClass('error_block');
  });

  //authorization
  console.log('start');
  $('.header__r_auth_reg').click(function() {
    setTimeout(function() {
      FormReg();
    }, 1500);
  });
  $('.header__r_auth_login').click(function() {
    setTimeout(function() {
      FormAuth();
    }, 1500);
  });

  function FormReg() {

    $('#phone').mask('+7(000)000-00-00');

    $('.datepicker-here').datepicker();
    $('#datepickers-container').css({'z-index': '9999'});

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
        $('.hospital_reg').each(function() {
          $(this).remove();
        });
        $('#search_result').css({'display': 'block'});
        $('#hospital').remove();
        $('#referal_two').val('');
        $('#referal_two').attr('data-id_region', '');
      }
      clearTimeout($this.data('timer'));
      $this.data('timer', setTimeout(function() {
        $this.removeData('timer');
        $.post('/ajax/search_for_reg/search_region.php',
            {name_city: $this.val()}, function(msg) {
              if ($('.error_region').length != 0) {
                $('.error_region').remove();
              }
              $('.region_reg').each(function() {
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

    $(document).on('click', '.region_reg', function() {

      let id_region = $(this).attr('value');
      $('#referal').attr('data-id_region', id_region);
      $('#referal').attr('data-region_check', 'check');
      $.post('/ajax/search_for_reg/search_company.php',
          {region_id: $('#referal').attr('data-id_region')}, function(msg) {
            if ($('.error_region').length != 0) {
              $('.error_region').remove();
            }
            $('.hospital_reg').each(function() {
              $(this).remove();
            });
            $('.hospital-empty').remove();
            setTimeout(function() {
              $('#search_result_hospital').append(msg);
            }, 100);

          });
    });

    $(document).on('click', '.hospital_reg', function() {
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
        $this.attr('data-id_region', '');
      }
      $('#search_result_hospital').css({'display': 'block'});
      clearTimeout($this.data('timer'));
      $this.data('timer', setTimeout(function() {
        $this.removeData('timer');
        $.post('/ajax/search_for_reg/search_company.php', {
          name_hospital: $this.val(),
          region_id: $('#referal').attr('data-id_region'),
        }, function(msg) {
          if ($('.error_region').length != 0) {
            $('.error_region').remove();
          }
          $('.hospital_reg').each(function() {
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
            console.log('2323');
            setTimeout(function() {
              $('#search_result_hospital').append(msg);
            }, 100);

          }
        });
      }, delay));
    });

    $('.accept-phone-js').click(function() {
      if ($('#phone')['0'].validity.valid === true) {
        $('.hidden_wrap_phone').css('display', 'none');
        $('#sms_confirm_error').css('display', 'none');
        $('#sms_confirm').css('display', 'block');
        $.ajax({
          url: '/ajax/sms_code_generate.php',
          type: 'POST',
          data: {phone: $('#phone').val()},
          success: function(code) {
            console.log(code);
          },
        });
      } else {
        $('#sms_confirm_error').css('display', 'block');
      }
    });

    document.getElementById('password').onchange = validatePassword;
    document.getElementById('pass_conf').onchange = validatePassword;
    $(".check-img").click(function() {
      $(this).toggleClass("click");
    })
    $('#auth-form-reg').validator().on('submit', function(e) {
      e.preventDefault();
      if ($('#strax-sluchay').length != 0) {
        $('#auth-form-reg').
            append('<input type=\'hidden\'  name=\'review\' value=\'review\'>');
      }

      var errors = [];
      var vozrast = $('.datepicker-here').val();

      if (vozrast == '' || vozrast == undefined) {
        $('.datepicker-here').after(
            '<span class="label danger"  >Выберите дату рождения</span>');
        errors.push('error');
      } else {

      }
      if($(".check-img").hasClass("click") === false){
        $(".check-img").after(
            '<span class="label danger"  >Подтвердите свое согласие</span>');
        errors.push('error');
      }

      var data_form = $('#auth-form-reg').serializeArray();

      let region = $('#referal').attr('data-id_region');
      if (region == '' || region == undefined || region == "0") {
        $('#referal').after(
            '<span class="label danger"  >Выберите регион</span>');
        errors.push('error');
      } else {

        data_form.push({'name': 'id_region', 'value': region});
      }
      let company = $('#referal_two').attr('data-id_region');
      if (company == '' || company == undefined || company == "0") {
        $('#referal_two').after(
            '<span class="label danger"  >Выберите компанию</span>');
        errors.push('error');
      } else {
        data_form.push({'name': 'company', 'value': company});
      }


      if (errors.length == 0) {

        $.ajax({
          url: '/ajax/registration.php',
          type: 'POST',
          data: data_form,
          success: function(msg) {

            var suc = JSON.parse(msg);
            console.log(suc);
            if (suc.error !== undefined) {
              var email = $('#phone');
              email.after(
                  '<div class="danger" data-danger-email>' + suc.error +
                  '</div>');

            } else if (suc.birthday == '18'){

              $('.datepicker-here').after(
                  '<span class="label danger"  >Регистрация лиц, не достигших 18 лет, не допускается</span>');


            }else if (suc.user == 'Уже существует') {
              var email = $('#email');
              email.after(
                  '<div class="danger" data-danger-email>Пользовватель с таким эмейлом уже сущесвуте</div>');
            } else if (suc.company == 'Нет компании') {
              var email = $('#company');
              email.after(
                  '<div class="danger" data-danger-company>В нашей базе нет этой компании ,мы не можем вас зарегестрировать </div>');
            }
            else if (suc.user != 0 ) {

              if ($('.header__r_auth_reg').attr('data-rigstration') == "0") {

                $('#auth-form-reg').find($('.close-modal')).trigger('click');

                $('.header__r_auth_reg').attr('data-rigstration', '1');
                $('body').css({'overflow': 'hidden'});
                setTimeout(function() {
                  $.magnificPopup.open({
                    items: {
                      src: '<div class="white-popup custom_styles_popup">Регистрация  успешно завершена . Теперь вы можете проверить свой диагноз.</div>',
                      type: 'inline',
                    },
                  });
                  $('body').css({'overflow': 'initial'});
                }, 1000);

              } else {
                 location.reload();
              }
            }
          },
        });

      }
      return false;
    });

    // $('#company').on('input', function(ev) { // скрипт для подгрузки компаний
    //   if ($(ev.target).val().length > 2) {
    //     var data = {
    //       'name': $(ev.target).val(),
    //     };
    //     $.ajax({
    //       dataType: 'html',
    //       url: '/ajax/search_company.php',
    //       type: 'POST',
    //       data: data,
    //       beforeSend: function() {
    //         $('.search_company > div').remove();
    //       },
    //     }).done(function(html) {
    //       console.log(html);
    //       $('.search_company').append(html);
    //
    //     });
    //   }
    // });

    $('.search_company').on('click', '.primer_company', function() {
      var name_check_comapany = $(this).text();
      $('#company').val(name_check_comapany);
      $('.primer_company').remove();
    });
  }

  function FormAuth() {

    console.log('auth');
    $('#auth-form-login').validator().on('submit', function(e) {
      e.preventDefault();
      $.ajax({
        type: 'POST',
        url: '/ajax/authorization.php',
        data: {
          mode: 'login',
          login: $('#auth-form-login input[name=login]').val(),
          password: $('#auth-form-login input[name=password]').val(),
        },
        dataType: 'json',
        success: function(result) {
          if (result.status)
            location.reload();
          else {
            $('.message.error').html(result.message);
            $('.message.error').show();
          }
        },
      });

      return false;
    });
  }

  // end authorization

  $('#login-link').magnificPopup({
    type: 'ajax',
    modal: true,
    focus: '#name',

    callbacks: {
      beforeOpen: function() {
        if ($(window).width() < 700) {
          this.st.focus = false;
        } else {
          this.st.focus = '#name';
        }
      },
    },
  });

  $('#reg-link').magnificPopup({
    type: 'ajax',
    modal: true,
    focus: '#email',

    callbacks: {
      beforeOpen: function() {
        if ($(window).width() < 700) {
          this.st.focus = false;
        } else {
          this.st.focus = '#email';
        }
      },
    },
  });

  //feedback modal
  $('#write-us_modal').magnificPopup({
    type: 'ajax',
    modal: true,
    focus: '#name',

    callbacks: {
      beforeOpen: function() {
        if ($(window).width() < 700) {
          this.st.focus = false;
        } else {
          this.st.focus = '#name';
        }
      },
    },
  });

});

$(document).on('click', '.close-modal', function(e) {
  e.preventDefault();
  $.magnificPopup.close();
});

// Star rating
$(document).ready(function() {

  /* 1. Visualizing things on Hover - See next part for action on click */
  $('#stars li').on('mouseover', function() {
    var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

    // Now highlight all the stars that's not after the current hovered star
    $(this).parent().children('li.star').each(function(e) {
      if (e < onStar) {
        $(this).addClass('hover');
      }
      else {
        $(this).removeClass('hover');
      }
    });

  }).on('mouseout', function() {
    $(this).parent().children('li.star').each(function(e) {
      $(this).removeClass('hover');
    });
  });

  /* 2. Action to perform on click */
  $('#stars li').on('click', function() {
    var onStar = parseInt($(this).data('value'), 10); // The star currently selected
    var stars = $(this).parent().children('li.star');

    for (i = 0; i < stars.length; i++) {
      $(stars[i]).removeClass('selected');
    }

    for (i = 0; i < onStar; i++) {
      $(stars[i]).addClass('selected');
    }

    // JUST RESPONSE (Not needed)
    var ratingValue = parseInt($('#stars li.selected').last().data('value'),
        10);
    var msg = '';
    if (ratingValue > 1) {
      msg = 'Спасибо! Ваша оценка ' + ratingValue + '.';
    }
    else {
      msg = 'Мы будем стараться лучше. Ваша оценка ' + ratingValue +
          '.';
    }
    responseMessage(msg);

  });

});

function responseMessage(msg) {
  $('.success-box').fadeIn(200);
  $('.success-box').addClass('active');
  $('.success-box div.text-message').html('<span>' + msg + '</span>');
}

// show menu toggle
var theToggle = document.getElementById('show-mnu');

// hasClass
function hasClass(elem, className) {
  return new RegExp(' ' + className + ' ').test(' ' + elem.className + ' ');
}

// addClass
function addClass(elem, className) {
  if (!hasClass(elem, className)) {
    elem.className += ' ' + className;
  }
}

// removeClass
function removeClass(elem, className) {
  var newClass = ' ' + elem.className.replace(/[\t\r\n]/g, ' ') + ' ';
  if (hasClass(elem, className)) {
    while (newClass.indexOf(' ' + className + ' ') >= 0) {
      newClass = newClass.replace(' ' + className + ' ', ' ');
    }
    elem.className = newClass.replace(/^\s+|\s+$/g, '');
  }
}

// toggleClass
function toggleClass(elem, className) {
  var newClass = ' ' + elem.className.replace(/[\t\r\n]/g, ' ') + ' ';
  if (hasClass(elem, className)) {
    while (newClass.indexOf(' ' + className + ' ') >= 0) {
      newClass = newClass.replace(' ' + className + ' ', ' ');
    }
    elem.className = newClass.replace(/^\s+|\s+$/g, '');
  } else {
    elem.className += ' ' + className;
  }
}

if (theToggle) {
  theToggle.onclick = function() {
    toggleClass(this, 'active');
    return false;
  };
}

function lazyloadImage() {
  const images = document.querySelectorAll('[data-src]');
  const imagesSource = document.querySelectorAll('[data-srcset]');

  function preloadImage(img) {
    const src = img.getAttribute('data-src');
    const dataSrc = img.getAttribute('data-srcset');
    if (!src) {
      img.srcset = dataSrc;
    }
    img.src = src;
  }

  const imgOptions = {
    threshold: 0,
    rootMargin: '0px 0px 0px 0px',
  };

  const imgObserver = new IntersectionObserver(function(entries, imgObserver) {
    entries.forEach(entrie => {
      if (!entrie.isIntersecting) {
        return;
      } else {
        entrie.target.classList.remove('lazy-kdteam');
        preloadImage(entrie.target);
        imgObserver.unobserve(entrie.target);
      }
    });
  }, imgOptions);

  images.forEach(image => {
    imgObserver.observe(image);
  });

  imagesSource.forEach(image => {
    imgObserver.observe(image);
  });
}

function validatePassword() {
  let pass2 = document.getElementById('pass_conf').value;
  let pass1 = document.getElementById('password').value;
  if (pass1 != pass2)
    document.getElementById('pass_conf').
        setCustomValidity('Пароли не совпадают');
  else
    document.getElementById('pass_conf').setCustomValidity('');
//empty string means no validation error
}

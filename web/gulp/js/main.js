//= ../../landing/js/vendors/jquery-3.4.1.min.js
//= ../../node_modules/magnific-popup/dist/jquery.magnific-popup.min.js
//= ../../node_modules/jquery-mask-plugin/dist/jquery.mask.js
//= ../../node_modules/air-datepicker/dist/js/datepicker.min.js
$(document).ready(function() {
  var obj_correction_name = {
    is_harder_str : function(str) {
      if (str.val().search("-") === -1) {
        return false;
      }
    },
    correction_plain_str :function(str) {
      var first_simvol = str.val()[0].toUpperCase();
      var other = str.val().slice(1).toLowerCase();
      return first_simvol + other;
    },
    correction_harder_str : function(str) {
      var harder_str = str.val().split("-");
      var first_simvol_first_str = harder_str[0][0].toUpperCase();
      var other_first_str = harder_str[0].slice(1).toLowerCase();
      var first_simvol_second_str = harder_str[1][0].toUpperCase();
      var other_second_str = harder_str[1].slice(1).toLowerCase();
      return first_simvol_first_str + other_first_str + '-' + first_simvol_second_str + other_second_str;
    }
  };

  // $('#write-us_modal').click(function() {
  //   setTimeout(function() {
  //     form_us();
  //   }, 1500);
  // });

  var url = window.location.pathname;

  if (url.search('contact_us/') != -1) {

    form_us_two();

  }

  // ?
  if (url.search('/forma-obrashenija/') == -1) {
    if ($('.header__r_auth_reg').length != 0) {
      $('.header__r_auth_reg').attr('data-rigstration', '2');
    }
  }

  $(document).mouseup(function(e) {
    var div = $('.danger');
    if (!div.is(e.target)) {
      div.remove();
    }
    var popover = $('.popover_error');
    if (!popover.is(e.target)) {
      popover.fadeToggle(400, 'linear', function() {
        popover.remove();
      });
    }
    $('.error_step-card-1').removeClass('error_block');
    $('.error_step-card-3').removeClass('error_block');
    $('.error_step-card-4').removeClass('error_block');
  });

  //authorization

  $('.header__r_auth_login').click(function(e) {
    e.preventDefault();
    setTimeout(function() {
      FormAuth();
    }, 700);
  });

  function FormAuth() {
    const buttonAuthNewHeader = !!$('.v2').length;

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
          if (result.status) {
            if (buttonAuthNewHeader) {
              window.location.href = '/personal-cabinet/';
            }
            $('#auth-form-login').find($('.close-modal')).trigger('click');

            $('.header__r_auth_reg').attr('data-rigstration', '1');
            $('body').css({'overflow': 'hidden'});

            $.ajax({
              dataType: 'html',
              url: '/ajax/forma-obrashenija/reload_header.php',
              type: 'POST',
              beforeSend: function() {

              },
              success: function(msg) {

                if(window.location.href.search("/add-feedback/") !== -1){
                  $(".name_user_no_Authorized").remove();
                }
                $('.header__r').html('');
                $('.header__r').html(msg);
              },
            }).done(function(msg) {

            });

          } else {
            $('.message.error').html(result.message);
            $('.message.error').show();
          }
        },
      });

      return false;
    });
      $('#trigger-reg-form').click(function() {
      $('#auth-form-login').find($('.close-modal')).trigger('click');
      setTimeout(function() {
        $('#reg-link').trigger('click');
        if($('.v2').length) {
          setTimeout(() => {
            FormReg();
          }, 1000);
        }
      }, 1000);
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
  // $('#write-us_modal').magnificPopup({
  //   type: 'ajax',
  //   modal: true,
  //   focus: '#name',
  //
  //   callbacks: {
  //     beforeOpen: function() {
  //       if ($(window).width() < 700) {
  //         this.st.focus = false;
  //       } else {
  //         this.st.focus = '#name';
  //       }
  //     },
  //   },
  // });

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
      msg = 'Спасибо! Ваша оценка ' + ratingValue +
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

$(document).ready(function() {
  $(document).on('click', '#show-mnu', function() {
    $('#show-mnu').toggleClass('active');
  });
  $(document).on('click', function(e) {
    if (!$(e.target).closest('#show-mnu , #menu ').length) {
      $('#show-mnu').removeClass('active');
    }
    e.stopPropagation();
  });
});

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

function form_us() {

  $('#feedback_modal').validator().on('submit', function(e) {

    e.preventDefault();
    var fd = new FormData();
    var error = [];

    var data_FORM = $('#feedback_modal').serializeArray();

    var input_file = $('.file-simple');

    if (input_file.prop('files')[0] != undefined) {
      fd.append('import_file1', input_file.prop('files')[0]);
    }
    if (input_file.prop('files')[1] != undefined) {
      fd.append('import_file2', input_file.prop('files')[1]);
    }
    if (input_file.prop('files')[2] != undefined) {
      fd.append('import_file3', input_file.prop('files')[2]);
    }
    if (input_file.prop('files')[3] != undefined) {
      fd.append('import_file4', input_file.prop('files')[3]);
    }
    if (input_file.prop('files')[4] != undefined) {
      fd.append('import_file5', input_file.prop('files')[4]);
    }
    if (input_file.prop('files')[5] != undefined) {
      $('.file_input_half').after(
          '<p class="label danger"  >Максимально 5 картинок</p>');
      error.push('mane_img');
    }

    fd.append('name', data_FORM[0]['value']);
    fd.append('email', data_FORM[1]['value']);
    fd.append('text', data_FORM[2]['value']);
    fd.append('captcha_code', data_FORM[3]['value']);
    fd.append('captcha_word', data_FORM[4]['value']);
    if (error.length == '0') {
      $.ajax({

        url: '/ajax/form_ask/ask.php',
        type: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        beforeSend: function() {

        },
        success: function(msg) {
          var suc = JSON.parse(msg);

        if (suc.suc == "1") {
          $.magnificPopup.open({
            items: {
              src: '<div class="white-popup custom_styles_popup"><button title="Закрыть" type="button" class="mfp-close">×</button>' +
              '<h3 class="popup__wrap_tabs_title">Ваше письмо отправлено успешно</h3></div>',
              type: 'inline',
            },
            callbacks: {
              afterClose: function() {
                document.location.reload(true);
              },
            },
          });
        } else if (suc.captcha == "1") {
          $("#captcha-error_parent").after(
              '<p class="label danger"  >Код капчи неверный</p>');

          } else {
            if (suc.size == '1') {
              $('.file_input_half').after(
                  '<p class="label danger"  >Файлы с недопустимым размером</p>');
            }
            if (suc.format == '1') {
              $('.file_input_half').after(
                  '<p class="label danger"  >Файлы с недопустимым форматом</p>');
            }
          }

        },
      }).done(function(msg) {

      });
    }

    return false;
  });
}

function form_us_two() {
  $('#feedback_modal_two').validator().on('submit', function(e) {

    e.preventDefault();
    var fd = new FormData();
    var error = [];

    var data_FORM = $('#feedback_modal_two').serializeArray();

    var input_file = $('.file-simple');

    if (input_file.prop('files')[0] != undefined) {
      fd.append('import_file1', input_file.prop('files')[0]);
    }
    if (input_file.prop('files')[1] != undefined) {
      fd.append('import_file2', input_file.prop('files')[1]);
    }
    if (input_file.prop('files')[2] != undefined) {
      fd.append('import_file3', input_file.prop('files')[2]);
    }
    if (input_file.prop('files')[3] != undefined) {
      fd.append('import_file4', input_file.prop('files')[3]);
    }
    if (input_file.prop('files')[4] != undefined) {
      fd.append('import_file5', input_file.prop('files')[4]);
    }
    if (input_file.prop('files')[5] != undefined) {
      $('.file_input_half').after(
          '<p class="label danger"  >Максимально 5 картинок</p>');
      error.push('mane_img');
    }

    fd.append('name', data_FORM[0]['value']);
    fd.append('email', data_FORM[1]['value']);
    fd.append('text', data_FORM[2]['value']);
    fd.append('captcha_code', data_FORM[3]['value']);
    fd.append('captcha_word', data_FORM[4]['value']);
    if (error.length == '0') {
      $.ajax({

        url: '/ajax/form_ask/ask.php',
        type: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        beforeSend: function() {

        },
        success: function(msg) {
          var suc = JSON.parse(msg);

          if (suc.suc == '1') {
            $.magnificPopup.open({
              items: {
                src: '<div class="white-popup custom_styles_popup"><button title="Закрыть" type="button" class="mfp-close">×</button>' +
                '<h3 class="popup__wrap_tabs_title">Ваше письмо отправлено успешно</h3></div>',
                type: 'inline',
              },
              callbacks: {
                afterClose: function() {
                  document.location.reload(true);
                },
              },
            });
          } else if (suc.captcha == "1") {
            $("#captcha-error_parent").after(
                '<p class="label danger"  >Код капчи неверный</p>');

          } else {
            if (suc.size == '1') {
              $('.file_input_half').after(
                  '<p class="label danger"  >Файлы с недопустимым размером</p>');
            }
            if (suc.format == '1') {
              $('.file_input_half').after(
                  '<p class="label danger"  >Файлы с недопустимым форматом</p>');
            }
          }

        },
      }).done(function(msg) {

      });
    }

    return false;
  });
}

function isValidEmailAddress(emailAddress) {
  var pattern = new RegExp(
      /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
  return pattern.test(emailAddress);
}

/*policy number input number only!*/
$('.numberInput').bind('change keyup input click', function() {
  if (this.value.match(/[^0-9]/g)) {
    this.value = this.value.replace(/[^0-9]/g, '');
  }
});

function timer_for_sms() {
  setTimeout(function() {
    $('.sms-again-button').removeAttr('disabled');
  }, 30000);
}
function getCookie(name) {
  var value = "; " + document.cookie;
  var parts = value.split("; " + name + "=");
  if (parts.length == 2) return parts.pop().split(";").shift();
}

function deleteCookie(name) {
  setCookie(name, "", {
    'max-age': -1
  })
}


function setCookie(name, value, options = {}) {

  options = {
    path: '/',

  };

  if (options.expires instanceof Date) {
    options.expires = options.expires.toUTCString();
  }

  let updatedCookie = name + "=" + value;


  for (let optionKey in options) {
    updatedCookie += "; " + optionKey;
    let optionValue = options[optionKey];
    if (optionValue !== true) {
      updatedCookie += "=" + optionValue;
    }
  }

  document.cookie = updatedCookie;
}


"use strict";

function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }

/* ^^^
 * Глобальные-вспомогательные функции
 * ========================================================================== */

/**
 * Возвращает HTML-код иконки из SVG-спрайта
 *
 * @param {String} name Название иконки из спрайта
 * @param {Object} opts Объект настроек для SVG-иконки
 *
 * @example SVG-иконка
 * getSVGSpriteIcon('some-icon', {
 *   tag: 'div',
 *   type: 'icons', // colored для подключения иконки из цветного спрайта
 *   class: '', // дополнительные классы для иконки
 *   mode: 'inline', // external для подключаемых спрайтов
 *   url: '', // путь до файла спрайта, необходим только для подключаемых спрайтов
 * });
 */
function getSVGSpriteIcon(name, opts) {
  opts = _extends({
    tag: 'div',
    type: 'icons',
    "class": '',
    mode: 'inline',
    url: ''
  }, opts);
  var external = '';
  var typeClass = '';

  if (opts.mode === 'external') {
    external = "".concat(opts.url, "/sprite.").concat(opts.type, ".svg");
  }

  if (opts.type !== 'icons') {
    typeClass = " svg-icon--".concat(opts.type);
  }

  opts["class"] = opts["class"] ? " ".concat(opts["class"]) : '';
  return "\n    <".concat(opts.tag, " class=\"svg-icon svg-icon--").concat(name).concat(typeClass).concat(opts["class"], "\" aria-hidden=\"true\" focusable=\"false\">\n      <svg class=\"svg-icon__link\">\n        <use xlink:href=\"").concat(external, "#").concat(name, "\"></use>\n      </svg>\n    </").concat(opts.tag, ">\n  ");
}
/* ^^^
 * JQUERY Actions
 * ========================================================================== */

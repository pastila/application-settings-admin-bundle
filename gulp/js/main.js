//= ../../node_modules/jquery/dist/jquery.min.js
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

  $('#write-us_modal').click(function() {
    setTimeout(function() {
      form_us();
    }, 1500);
  });

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
  $('.header__r_auth_reg').click(function() {
    setTimeout(function() {
      FormReg();
    }, 1500);
  });
  $('.header__r_auth_login').click(function() {
    setTimeout(function() {
      FormAuth();
    }, 700);
  });

  function FormReg() {
    $('#famaly-name, #name , #last-name').
        bind('change keyup input click', function() {
          if (this.value.match(/[^а-яёА-ЯЁ\-]/g)) {
            this.value = this.value.replace(/[^а-яёА-ЯЁ\-]/g, '');
          }
        });

    $('#phone').mask('+7(000)000-00-00');
    $('#famaly-name').on('keyup', function() {
      var $this = $(this);
      clearTimeout($this.data('timer'));
      $this.data('timer', setTimeout(function() {
        $this.removeData('timer');

        if ($this.val().length > 0) {
          if (obj_correction_name.is_harder_str($this) === false) {
            var new_str = obj_correction_name.correction_plain_str($this);
            $this.val(new_str);
          } else {
            var new_str = obj_correction_name.correction_harder_str($this);
            $this.val(new_str);
          }
        }
      }, 2200));
    });

    $('#name').on('keyup', function() {
      var $this = $(this);
      clearTimeout($this.data('timer'));
      $this.data('timer', setTimeout(function() {
        $this.removeData('timer');

        if ($this.val().length > 0) {
          if (obj_correction_name.is_harder_str($this) === false) {
            var new_str = obj_correction_name.correction_plain_str($this);
            $this.val(new_str);
          } else {
            var new_str = obj_correction_name.correction_harder_str($this);
            $this.val(new_str);
          }
        }

      }, 2200));
    });
    $('#last-name').on('keyup', function() {
      var $this = $(this);
      clearTimeout($this.data('timer'));
      $this.data('timer', setTimeout(function() {
        $this.removeData('timer');
        if ($this.val().length > 0) {
          if (obj_correction_name.is_harder_str($this) === false) {
            var new_str = obj_correction_name.correction_plain_str($this);
            $this.val(new_str);
          } else {
            var new_str = obj_correction_name.correction_harder_str($this);
            $this.val(new_str);
          }
        }
      }, 2200));
    });
    $('.numberInput').bind('change keyup input click', function() {
      if (this.value.match(/[^0-9]/g)) {
        this.value = this.value.replace(/[^0-9]/g, '');
      }
    });
    let today = new Date();
    let year = today.getFullYear() - 18;
    today.setFullYear(year);
    $('#datepicker_reg').datepicker({
      maxDate: today,
    });
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
            setTimeout(function() {
              $('#search_result_hospital').append(msg);
            }, 100);

          }
        });
      }, delay));
    });

    $('.accept-phone-js').click(function() {
      $('#tel_confirm_error').css('display', 'none');
      if ($('#phone')['0'].validity.valid === true) {
        setCookie("phone",$('#phone').val());
        $('#sms_confirm_error').css('display', 'none');
        $.ajax({
          url: '/ajax/sms_code_generate.php',
          type: 'POST',
          data: {phone: $('#phone').val()},
          success: function(code) {
            if (code === 'error') {
              $('#tel_confirm_error').css('display', 'block');

            } else {
              $('.hidden_wrap_phone').css('display', 'none');
              $('#sms_confirm').css('display', 'block');
            }
          },
        });
      } else {
        $('#sms_confirm_error').css('display', 'block');
      }
    });

    $('.sms-again-button').click(function() {
      $('#tel_confirm_error').css('display', 'none');
      if ($('#phone')['0'].validity.valid === true) {
        $('#sms_confirm_error').css('display', 'none');
        $.ajax({
          url: '/ajax/sms_code_generate.php',
          type: 'POST',
          data: {phone: $('#phone').val()},
          success: function(code) {
            if (code === 'error') {
              $('#tel_confirm_error').css('display', 'block');
            } else {
              $('.hidden_wrap_phone').css('display', 'none');
              $('#sms_confirm').css('display', 'block');
              $('.sms-again-button').prop('disabled', true);
              timer_for_sms();
            }
          },
        });
      } else {
        $('#sms_confirm_error').css('display', 'block');
      }

    });

    document.getElementById('password').onchange = validatePassword;
    document.getElementById('pass_conf').onchange = validatePassword;
    // $(".check-img").click(function() {
    //   $(this).toggleClass("click");
    // })

    $('#auth-form-reg').validator().on('submit', function(e) {
      e.preventDefault();
      if ($('#strax-sluchay').length != 0) {
        $('#auth-form-reg').
            append('<input type=\'hidden\'  name=\'review\' value=\'review\'>');
      }

      var errors = [];
      // var vozrast = $('.datepicker-here').val();
      //
      // if (vozrast == '' || vozrast == undefined) {
      //   $('.datepicker-here').after(
      //       '<span class="label danger"  >Выберите дату рождения</span>');
      //   errors.push('error');
      // } else {
      //
      // }

      var data_form = $('#auth-form-reg').serializeArray();






      let region = $('#referal').attr('data-id_region');
      if (region == '' || region == undefined || region == '0') {
        $('#referal').after('   <div class="popover_error">\n' +
            '          <div class="popover_error_arrow"></div>\n' +
            '          <div class="popover_error_image">\n' +
            '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
            '          </div>\n' +
            '          <div class="popover_error_text">\n' +
            '        Выберите регион.\n' +
            '      </div>\n' +
            '      </div>');
        errors.push('error');
      } else {

        data_form.push({'name': 'id_region', 'value': region});
      }
      let company = $('#referal_two').attr('data-id_region');
      if (company == '' || company == undefined || company == '0') {
        $('#referal_two').after('   <div class="popover_error">\n' +
            '          <div class="popover_error_arrow"></div>\n' +
            '          <div class="popover_error_image">\n' +
            '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
            '          </div>\n' +
            '          <div class="popover_error_text">\n' +
            '        Выберите компанию.\n' +
            '      </div>\n' +
            '      </div>');
        errors.push('error');
      } else {
        data_form.push({'name': 'company', 'value': company});
      }

      let plan = [];
      let input = $('.checkbox_registration_modal');
      $(input).find('input:checked').each(function() {
        plan.push(this.value);
      });

      // if(plan.length == 0 ){
      //     input.after(
      //         '<p class="label danger">Подтвердите свое согласие</p>');
      //     errors.push("error8");
      // }

      if (plan.length == 0) {
        input.children('.check-label').
            children('.check-img_reg').
            after('   <div class="popover_error">\n' +
                '          <div class="popover_error_arrow"></div>\n' +
                '          <div class="popover_error_image">\n' +
                '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
                '          </div>\n' +
                '          <div class="popover_error_text">\n' +
                '          Подтвердите свое согласие.\n' +
                '      </div>\n' +
                '      </div>');
        errors.push('error8');

      }
      var data = $('.datepicker-here').val();
      var re = /^(\d+)[.](\d+)[.](\d+)/;
      if (re.test(data)) {
      } else {
        $('.datepicker-here').after('   <div class="popover_error">\n' +
            '          <div class="popover_error_arrow"></div>\n' +
            '          <div class="popover_error_image">\n' +
            '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
            '          </div>\n' +
            '          <div class="popover_error_text">\n' +
            '          Выберите дату рождения.\n' +
            '      </div>\n' +
            '      </div>');
        errors.push('error9');
      }

      if ($('#check-code-js').val().length === 0) {
        $('#check-code-js').after('   <div class="popover_error">\n' +
            '          <div class="popover_error_arrow"></div>\n' +
            '          <div class="popover_error_image">\n' +
            '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
            '          </div>\n' +
            '          <div class="popover_error_text">\n' +
            '         Введите код подтверждения.\n' +
            '      </div>\n' +
            '      </div>');
        $('.accept-phone-js').after('   <div class="popover_error">\n' +
            '          <div class="popover_error_arrow"></div>\n' +
            '          <div class="popover_error_image">\n' +
            '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
            '          </div>\n' +
            '          <div class="popover_error_text">\n' +
            '         Введите код подтверждения.\n' +
            '      </div>\n' +
            '      </div>');
        errors.push('error10');




      } else if ($('#check-code-js').val().length > 0) {

        var phone =  getCookie("phone");
        if (phone != data_form[5]['value']) {

          $('#phone').after('   <div class="popover_error">\n' +
              '          <div class="popover_error_arrow"></div>\n' +
              '          <div class="popover_error_image">\n' +
              '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
              '          </div>\n' +
              '          <div class="popover_error_text">\n' +
              '        Введный вами номер для подтверждения не соответсвтует текущему .\n' +
              '      </div>\n' +
              '      </div>');
          errors.push('error');
        }


        if (errors.length === 0) {
          $.ajax({
            url: '/ajax/registration.php',
            type: 'POST',
            data: data_form,
            success: function(msg) {

              var suc = JSON.parse(msg);
              if (suc.error_phone !== undefined) {
                var email = $('#phone');
                email.after(
                    '<div class="danger" data-danger-email>' + suc.error +
                    '</div>');

              }

              if (suc.error === 'mail') {
                var email = $('#email');
                email.after('   <div class="popover_error">\n' +
                    '          <div class="popover_error_arrow"></div>\n' +
                    '          <div class="popover_error_image">\n' +
                    '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
                    '          </div>\n' +
                    '          <div class="popover_error_text">\n' +
                    '         Пользователь с таким эмейлом уже сущесвуте.\n' +
                    '      </div>\n' +
                    '      </div>');
              }

              if (suc.error === 'polic') {
                var email = $('#number_polic');
                email.after('   <div class="popover_error">\n' +
                    '          <div class="popover_error_arrow"></div>\n' +
                    '          <div class="popover_error_image">\n' +
                    '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
                    '          </div>\n' +
                    '          <div class="popover_error_text">\n' +
                    '         Пользователь с таким полисом уже сущесвуте.\n' +
                    '      </div>\n' +
                    '      </div>');
              }

              if (suc.company === 'Нет компании') {
                var company = $('#company');
                company.after('   <div class="popover_error">\n' +
                    '          <div class="popover_error_arrow"></div>\n' +
                    '          <div class="popover_error_image">\n' +
                    '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
                    '          </div>\n' +
                    '          <div class="popover_error_text">\n' +
                    '        В нашей базе нет этой компании ,мы не можем вас зарегестрировать.\n' +
                    '      </div>\n' +
                    '      </div>');
              }

              if (suc.error_sms !== undefined) {
                $('#check-code-js').after('   <div class="popover_error">\n' +
                    '          <div class="popover_error_arrow"></div>\n' +
                    '          <div class="popover_error_image">\n' +
                    '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
                    '          </div>\n' +
                    '          <div class="popover_error_text">\n' +
                    '        Неверный код подтверждения.\n' +
                    '      </div>\n' +
                    '      </div>');
              }

              if (suc.user_success === 'success') {

                if ($('.header__r_auth_reg').attr('data-rigstration') == '0') {

                  $('#auth-form-reg').find($('.close-modal')).trigger('click');

                  $('.header__r_auth_reg').attr('data-rigstration', '1');
                  $('body').css({'overflow': 'hidden'});

                  $.ajax({
                    dataType: 'html',
                    url: '/ajax/forma-obrashenija/reload_header.php',
                    type: 'POST',
                    beforeSend: function() {

                    },
                    success: function(msg) {
                      $('.header__r').html('');
                      $('.header__r').html(msg);
                    },
                  }).done(function(msg) {
                    deleteCookie("phone");
                    setTimeout(function() {
                      $.magnificPopup.open({
                        items: {
                          src: '<div class="white-popup custom_styles_popup">Регистрация  успешно завершена.</div>',
                          type: 'inline',
                        },
                      });
                      $('body').css({'overflow': 'initial'});
                    }, 1000);
                  });

                } else {

                  location.reload();
                }
              }

            },
          });

        }
      }
      return false;
    });
    // $.ajax({
    //   url: '/ajax/sms_confirm.php',
    //   type: 'POST',
    //   data: {ID: $('#check-code-js').val()},
    //   success: function(confirm) {
    //     if (confirm === 'error') {
    //       $("#check-code-js").after(
    //           '<p class="label danger">Неверный код подтверждения</p>');
    //     } else if (confirm === 'success') {
    //
    //     }
    //   }
    // });
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
    /*policy number input number only!*/
    $('.numberInput').bind('change keyup input click', function() {
      if (this.value.match(/[^0-9]/g)) {
        this.value = this.value.replace(/[^0-9]/g, '');
      }
    });
  }

  function FormAuth() {

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
              '<h3 class="popup__wrap_tabs_title">Ваше письмо отправленно успешно</h3></div>',
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
                '<h3 class="popup__wrap_tabs_title">Ваше письмо отправленно успешно</h3></div>',
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
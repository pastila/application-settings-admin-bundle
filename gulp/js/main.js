//= ../../node_modules/jquery/dist/jquery.min.js
//= ../../node_modules/magnific-popup/dist/jquery.magnific-popup.min.js
//= ../../node_modules/jquery-mask-plugin/dist/jquery.mask.min.js
//= ../../node_modules/air-datepicker/dist/js/datepicker.min.js
$(document).ready(function() {


  $(document).mouseup(function (e){
    var div = $(".danger");
    if (!div.is(e.target)) {
      div.hide();
    }
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

     $(".datepicker-here").datepicker();
     $("#datepickers-container").css({"z-index":"9999"});

    $('#sel_reg').change(function() {
      let sVal = $(this).val();
      $.ajax({
        dataType: 'html',
        url: '/ajax/search_company.php',
        type: 'POST',
        data: {id :sVal},
      }).done(function(html) {
        $('#oms_company').html(html);
        console.log('html');
        $('#oms_company').change(function() {
          let company = $(this).val();
          console.log(company);
          $('#company').attr('value', company);
        })
      });
    });
    $('.accept-phone-js').click(function() {
      if ($('#phone')['0'].validity.valid === true) {
        $(this).css('display', 'none');
        $('#sms_confirm').css('display', 'block');
        $.ajax({
          url: '/ajax/sms_code_generate.php',
          type: 'POST',
          data: {phone: $('#phone').val()},
          success: function(code) {
            console.log(code);
          }
        });
      }
    });

    document.getElementById("password").onchange = validatePassword;
    document.getElementById("pass_conf").onchange = validatePassword;

    $('#auth-form-reg').validator().on('submit', function(e) {
       e.preventDefault();
      if($("#generate_form").length != 0){
        $('#auth-form-reg').append("<input type='hidden'  name='review' value='review'>")
      }
        var data_form = $('#auth-form-reg').serializeArray();
      var vozrast =  $(".datepicker-here").val();
      var y =   Date.parse(vozrast);
      var now = new Date();
      var total = parseInt(now) - parseInt(568036800000);
      if( total < y ){
        $(".date").css({"display":"block"})
      } else {
        $.ajax({
          url: '/ajax/registration.php',
          type: 'POST',
          data: data_form,
          success: function(msg) {

            var suc = JSON.parse(msg);

            if (suc.user == 'Уже существует') {
              var email = $('#email');
              email.after(
                  '<div class="danger" data-danger-email>Пользовватель с таким эмейлом уже сущесвуте</div>');
            } else if (suc.company == "Нет компании") {
              var email = $('#company');
              email.after(
                  '<div class="danger" data-danger-company>В нашей базе нет этой компании ,мы не можем вас зарегестрировать </div>');
            }
            else if (suc.user != 0 && suc.review != "register_with_review") {
              location.reload()

            } else if (suc.review == "register_with_review") {

              $(".close-modal").trigger("click");
              $("#generate_form").attr("href", "/ajax/form_statement.php");
              $("body").css({"overflow": "hidden"});
              setTimeout(function() {
                $.magnificPopup.open({
                  items: {
                    src: '<div class="white-popup custom_styles_popup">Регистрация и создание обращения успешно завершены.' +
                    'Для перехода в личный кабинет нажмите <a href="/obrashcheniya/" >сюда</a></div>',
                    type: 'inline'
                  }
                });
                $("body").css({"overflow": "initial"});
              }, 1000)
            }
          },
        });
        return false;

      }
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
  const images = document.querySelectorAll("[data-src]");
  const imagesSource = document.querySelectorAll("[data-srcset]");

  function preloadImage(img) {
      const src = img.getAttribute("data-src");
      const dataSrc = img.getAttribute("data-srcset");
      if (!src) {
          img.srcset = dataSrc;
      }
      img.src = src;
  }

  const imgOptions = {
      threshold: 0,
      rootMargin: "0px 0px 0px 0px"
  };

  const imgObserver = new IntersectionObserver(function (entries, imgObserver) {
      entries.forEach(entrie => {
          if (!entrie.isIntersecting) {
              return;
          } else {
              entrie.target.classList.remove("lazy-kdteam");
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

function validatePassword(){
  let pass2=document.getElementById("pass_conf").value;
  let pass1=document.getElementById("password").value;
  if(pass1 != pass2)
    document.getElementById("pass_conf").setCustomValidity("Пароли не совпадают");
  else
    document.getElementById("pass_conf").setCustomValidity('');
//empty string means no validation error
}

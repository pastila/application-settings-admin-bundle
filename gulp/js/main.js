//= ../../node_modules/jquery/dist/jquery.min.js
//= ../../node_modules/magnific-popup/dist/jquery.magnific-popup.min.js

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

  function FormReg() {


    $('#auth-form-reg').validator().on('submit', function(e) {
       e.preventDefault();
      if($("#generate_form").length != 0){
        $('#auth-form-reg').append("<input type='hidden'  name='review' value='review'>")
      }
        var data_form = $('#auth-form-reg').serializeArray();


          $.ajax({
            url: '/ajax/registration.php',
            type: 'POST',
            data: data_form,
            success: function(msg) {
              console.log(msg);
              var suc = JSON.parse(msg);
              console.log(suc);
              if (suc.user == 'Уже существует') {
                var email = $('#email');
                email.after(
                    '<div class="danger" data-danger-email>Пользовватель с таким эмейлом уже сущесвуте</div>');
              } else if (suc.user != 0 && suc.review !="register_with_review") {
               // location.reload()

              }else if(suc.review == "register_with_review"){

                $(".close-modal").trigger("click");
                $("#generate_form").attr("href","/ajax/form_statement.php");
                $("body").css({"overflow":"hidden"});
           setTimeout(function() {
             $.magnificPopup.open({
               items: {
                 src: '<div class="white-popup custom_styles_popup" style="min-width: 350px;\n' +
                 '    min-height: 170px;\n' +
                 '    font-size: 21px;\n' +
                 '    padding: 15px;">Регистрация и создание обращения успешно завершены.' +
                 'для перехода в личный кабинет нажмите <a href="/obrashcheniya/" >сюда</a></div>',
                 type: 'inline'
               }
             });
             $("body").css({"overflow":"initial"});
           },1000)
              }
            },
          });
          return false;


    });

    $('#company').on('input', function(ev) { // скрипт для подгрузки компаний
      if ($(ev.target).val().length > 2) {
        var data = {
          'name': $(ev.target).val(),
        };
        $.ajax({
          dataType: 'html',
          url: '/ajax/search_company.php',
          type: 'POST',
          data: data,
          beforeSend: function() {
          },
        }).done(function(html) {
          console.log(html);
          $('.search_company').append(html);

        });
      }
    });

    $('.search_company').on('click', '.primer_company', function() {
      var name_check_comapany = $(this).text();
      $('#company').val(name_check_comapany);
      $('.primer_company').remove();
    });
  }

  $(document).on('click', '#auth_btn', function(e) {
    console.log('auth');
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
    e.preventDefault();
    return false;
  });
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
      msg = 'Thanks! You rated this ' + ratingValue + ' stars.';
    }
    else {
      msg = 'We will improve ourselves. You rated this ' + ratingValue +
          ' stars.';
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


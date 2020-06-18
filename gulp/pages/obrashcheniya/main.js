//= ../../node_modules/jquery/dist/jquery.min.js

//= ../../node_modules/magnific-popup/dist/jquery.magnific-popup.min.js
//= ../../node_modules/air-datepicker/dist/js/datepicker.min.js
$(document).ready(function() {
  $('.add-user__js').click(function (){
    $(this).addClass('current');
    $(this).next().removeClass('current');
    if( $("[data-block_img="+ $(this).attr("data_el")+"]").find("[id*=img_block_"+ $(this).attr("data_el")).length !== 0  && $("#time_"+ $(this).attr("data_el")).text().search("2") != "-1"){
      $("#send_" + id).attr("onclick","send_ms(this)");
      $("#send_" + id).removeClass("disabled");
    }else {
      $("#send_" + $(this).attr("data_el")).addClass("disabled");
    }
  });
  $('.add-child__js').click(function (){
    $(this).addClass('current');
    $(this).prev().removeClass('current');
    if( $("[data-block_img="+ $(this).attr("data_el")+"]").find("[id*=img_block_"+ $(this).attr("data_el")).length !== 0  && $("#time_"+ $(this).attr("data_el")).text().search("2") != "-1"
        && $("#children_input_"+$(this).attr("data_el")).attr("data-id_child") !== ""){
      $("#send_" + id).attr("onclick","send_ms(this)");
      $("#send_" + id).removeClass("disabled");
    }else {
      $("#send_" + $(this).attr("data_el")).addClass("disabled");
    }

  });

 var arr_date_picker =   $("[class *=datepicker-here_obrashcheniya]");
  arr_date_picker.each(function() {
   var min_year =$(this).attr("data-date");
   $(this).datepicker({
     minDate: new Date(min_year+'-01-01T00:00:01'),
     maxDate: new Date(min_year+'-12-31T23:59:59')
   });
  });
  var arr_block_obrashcheniya =   $(".block__items_flex");
  arr_block_obrashcheniya.each(function() {
    var _= $(this);
    var id =  _.attr("data-block_img");
    if(_.children().length === 0 ){
      $("#send_"+id).removeAttr("onclick");
      $("#send_"+id).addClass("disabled");
    }else{

      if($("[data-pdf-id=" + id + "]").find(".pdf").attr("href") === undefined) {

          $("#send_" + id).removeAttr("onclick");
          $("#send_" + id).addClass("disabled");

      }
    }

  })

  });


  function addChild() {
    const items = document.querySelectorAll('.obrashcheniya__content');

    items.forEach(function(item) {
      const btn = item.querySelector('#add_child-button');
      const hideButton = item.querySelector('#remove_child-button');
      const showBlock = item.querySelector('.hidden_child-block');


        if (btn) {
          btn.addEventListener('click', function(e) {

            this.classList.add('active');

            if (btn.classList.contains('active')) {
              showBlock.classList.add('active');
              let data_el = btn.getAttribute('data_el');
              $('#selected_sender_'+data_el).val('child');
              commutator('child', data_el);
              showBlock.scrollIntoView({block: "center", behavior: "smooth"});
            } else {
              showBlock.classList.remove('active');
            }
          });
        }
       else {
        return
      }
      if (hideButton) {
        hideButton.addEventListener('click', function(e) {
          showBlock.classList.remove('active');
          let data_el = hideButton.getAttribute('data_el');
          $('#selected_sender_'+data_el).val('my');
          commutator('my', data_el);
        });
      }
      else {
        return
      }
    });
  }

  addChild();

  //Загрузка изображений

$( "input[type='file']" ).click(function() {
  $(this).addClass("click");
})


  $( "input[type='file']" ).change(function() {

    if($(this).hasClass("click")=== true) {
      let element = this.id;
      let reader = new FileReader();
      reader.readAsDataURL(this.files[0]);
      let $input = $(this);
      let fd = new FormData();
      fd.append("import_file", $input.prop('files')[0]);
      fd.append("id_elem", element);
      let n = $('#card_' + element);
      let error = $('#error_' + element);
      let success = $('#success_' + element);
      let length = n.find($(".js-img-add")).children().length;
      if (length < 5) {
        $.ajax({
          url: '/ajax/img_add.php',
          cache: false,
          contentType: false,
          processData: false,
          data: fd,
          type: 'post',
          success: function(result) {
            let result2 = JSON.parse(result);
            let src;
            if (result2.ERROR !== undefined) {
              error.text(result2.ERROR);
              success.text('');
            } else {
              if (result2.RES === false) {
                error.text('Не удается прочитать файл');
                success.text('');
              } else {
                error.text('');
                success.text(result2.SUCCESS);
                let r = result2.SRC;
                let fileName = result2.FILE_NAME;

                if (r.search(".pdf") != "-1") {
                  src = "/local/templates/kdteam/images/svg/pdf_icon.svg";
                } else {
                  src = r;
                }
                n.find($(".js-img-add")).append(
                    '<div id="img_block_' + result2.ID +
                    '" class="obrashcheniya__content_sidebar_blocks">\n' +
                    '    <div class="obrashcheniya__content_sidebar_blocks_img">\n' +
                    '        <img src="' + src + '">\n' +
                    '    </div>\n' +
                    '    <div class="obrashcheniya__content_sidebar_blocks_text">\n' +
                    '        <div class="obrashcheniya__content_sidebar_blocks_text_title">' + fileName + '</div>\n' +
                    '        <a id="download_img" download href="' + r + '"\n' +
                    '           class="obrashcheniya__content_sidebar_blocks_text_link">скачать</a>\n' +
                    '        <a href="#" rel="nofollow" onclick="del(this)" id="delete_' +
                    result2.ID + '" class="delete_img_js">удалить</a>\n' +
                    '    </div>\n' +
                    '</div>'
                );
                $(".photo_empty_all_" + element).html("");
                if ($("[data-block_img=" + element + "]").
                        find("[id=img_block_" + element).length !== "0" &&
                    $("#time_" + element).text().search("2") != "-1") {
                  $("#send_" + element).attr("onclick", "send_ms(this)");
                  $("#send_" + element).removeClass("disabled");
                }
              }
            }
          }
        });
      } else {
        error.text('Вы превысили лимит по загруженным картинкам');
        success.text('');
      }
    }
  });
  // ------ choose_class ------

  $(document).on('click', '.children_input', function() {
    let idItem = $(this).attr('data-value');

      search(idItem);

    $('#searchul_' + idItem).css('display', 'block');
  });






  function search(idItem) {
    $(document).on('click', '#searchul_'+idItem+' li', function() {

        $('#children_input_' + idItem).val($(this).text());
        $('#children_input_' + idItem).
            attr('data-id_child', $(this).attr('value'));
        $('#searchul_' + idItem).fadeOut();



      if($("#time_"+idItem).text().search(".20") !== -1) {
        $.post('/ajax/obrasheniya/selected_child.php',
            {ID: $(this).attr('value')}, function(result) {
              $('#selected-child_' + idItem).html(result);
              $.ajax({
                url: '/pdf/file_children_pdf.php',
                data: {
                  id: $('#children_input_' + idItem).attr('data-id_child'),
                  oplata: $('#time_' + idItem).text(),
                  id_obr: idItem
                },
                type: 'post',
                success: function(result) {
                  $('[data-obrashenie-id=' + idItem + ']').
                      find(".pdf").
                      attr("href", result);
                  $('[data-obrashenie-id=' + idItem + ']').
                      find(".pdf").
                      removeClass("error");
                  $('[data-obrashenie-id=' + idItem + ']').
                      find(".pdf").
                      text("Просмотреть");
                  $(".ready_pdf").removeClass("hidden");
                  $(".with_out_pdf").addClass("hidden");
                  if( $("[data-block_img="+idItem+"]").find("[id*=img_block_"+idItem).length !== 0  && $("#time_"+idItem).text().search("2") != "-1"){
                    $("#send_" + idItem).attr("onclick","send_ms(this)");
                    $("#send_" + idItem).removeClass("disabled");
                  }
                }









              });
            }, 'html');
      }
    });
    $(document).mouseup(function(e) {
      let container = $('#class_input_'+idItem);
      if (container.has(e.target).length === 0) {
        $('#searchul_'+idItem).fadeOut();
      }
    });

  }


function del(f) {

  let element = $(f)[0].id;
  $.ajax({
    url: '/ajax/img_delete.php',
    data: {ID: element},
    type: 'post',
    success: function(result) {

      let r = JSON.parse(result);
      $('#img_block_' + r.ID).remove();
      history.scrollRestoration = 'manual';
      $('#success_' + r.ID_EL).text('Файл успешно удален!');
      $('#error_' + r.ID_EL).text('');
      document.getElementById(r.ID_EL).value = "";
      history.scrollRestoration = 'manual';
      if( $("[data-block_img="+r.ID_EL+"]").find("[id*=img_block_"+r.ID_EL+"]").length === 0 ){
        $(".photo_empty_all_"+r.ID_EL).html("Нужно добавить скан документа/изображения для отправки обращения страховой компании");
        $("#send_" + r.ID_EL).removeAttr("onclick");
        $("#send_" + r.ID_EL).addClass("disabled");
      }
      history.scrollRestoration = 'manual';
    }
  });
}

function delete_el(el) {
  let element = $(el)[0].id;
  $.ajax({
    url: '/ajax/delete_appeal.php',
    data: {ID: element},
    type: 'post',
    success: function(result) {
      var suc = JSON.parse(result);

      if(suc.delete !== "") {
        $('#appeal_' + suc.delete).remove();
        if(suc.count == 1){
          $('.page-title').after(
              '<div class="obrashcheniya"></div>');
          $(".obrashcheniya").append('<p>У вас нет готовых обращений.</p>');
          $(".obrashcheniya").after('<a href="/forma-obrashenija/" class="mainBtn "">Сформировать обращение</a>');
        }

        }


    }
  });
}

function edit(ed) {
  let element = $(ed)[0].id.split('_');
  let cur_el = $('#appeal_' + element[1]);

  $("[data-id-date_picker_edit="+element[1]+"]").removeAttr("onclick");
  let usrname = $("input[name='usrname']");
  let policy = $("input[name='policy']");
  let time = $("input[name='time']");

  let usrname_p = $('#usrname_' + element[1]);
  let policy_p = $('#policy_' + element[1]);
  let time_p = $('#time_' + element[1]);


  $('#save_' + element[1]).css('display', 'inline-block');

  cur_el.find(usrname).css('display', 'block');
  cur_el.find(policy).css('display', 'block');
  cur_el.find(time).css('display', 'block');

  $('#edit_' + element[1]).css('display', 'none');

  usrname_p.css('display', 'none');
  policy_p.css('display', 'none');
  time_p.css('display', 'none');
}
function save(sv) {

  let element = $(sv)[0].id.split('_');
  let cur_el = $('#appeal_' + element[1]);
  let usrname = $("input[name='usrname']");
  let policy = $("input[name='policy']");
  let time = $("input[name='time']");

  let usrname_p = $('#usrname_' + element[1]);
  let policy_p = $('#policy_' + element[1]);
  let time_p = $('#time_' + element[1]);
  let hospitl = $('#hospitl_' + element[1]);

  let regExp = /^[А-ЯЁ][а-яё]*([-][А-ЯЁ][а-яё]*)?\s[А-ЯЁ][а-яё]*\s[А-ЯЁ][а-яё]*$/;
  let regExp2 = /^(\d+)[.](\d+)[.](\d+)/;
if(cur_el.find(policy).val().length === 16) {
  if (cur_el.find(time).val() !== "" || regExp2.test(cur_el.find(time).val())) {
    if (cur_el.find(usrname).val() !== "" && regExp.test(cur_el.find(usrname).val())) {
      if ($('#selected_sender_' + element[1]).val() === 'child') {
        $.ajax({
          url: '/ajax/edit_appeal.php',
          data: {
            ID: element[1],
            NAME: cur_el.find(usrname).val(),
            POLICY: cur_el.find(policy).val(),
            TIME: cur_el.find(time).val()
          },
          type: 'post',
          beforeSend: function() {
          // $("#gif_save_"+element[1]).css({"display":"block"})
          },
         success: function(result) {
            if (result === 'false') {
              $('#success_' + element[1]).html('');
              $('#error_' + element[1]).html('Заполните дату оплаты услуг');
            } else {

              $('#save_' + element[1]).css('display', 'none');

              cur_el.find(usrname).css('display', 'none');
              cur_el.find(policy).css('display', 'none');
              cur_el.find(time).css('display', 'none');

              usrname_p.html(cur_el.find(usrname).val());
              policy_p.html(cur_el.find(policy).val());
              time_p.html(cur_el.find(time).val());

              $('#edit_' + element[1]).css('display', 'inline-block');

              $('#success_' + element[1]).html(result);
              $('#error_' + element[1]).html('');

              usrname_p.css('display', 'block');
              policy_p.css('display', 'block');
              time_p.css('display', 'block');
              var time_p_str = time_p.text().replace(/\s/g, '');

              var policy_str = cur_el.find(policy).val();
              policy_str = policy_str.replace(/\s/g, '');

              var usrname_str = cur_el.find(usrname).val();
              usrname_str = usrname_str.replace(/\s/g, '');

              if (time_p_str.length <= 3 || policy_str.length <= 1 || usrname_str.length <= 3) {

              } else {
                if ($('#children_input_' + element[1]).
                    attr('data-id_child').length > 0) {
                  $.ajax({
                    url: '/pdf/file_children_pdf.php',
                    data: {
                      id: $('#children_input_' + element[1]).
                          attr('data-id_child'),
                      oplata: $('#time_' + element[1]).text(),
                      id_obr: element[1]
                    },
                    type: 'post',
                    success: function(result) {
                      $('[data-obrashenie-id=' + element[1] + ']').
                          find(".pdf").
                          attr("href", result);
                      $('[data-obrashenie-id=' + element[1] + ']').
                          find(".pdf").
                          removeClass("error");
                      $('[data-obrashenie-id=' + element[1] + ']').
                          find(".pdf").
                          text("Просмотреть");
                      $(".ready_pdf").removeClass("hidden");
                      $(".with_out_pdf").addClass("hidden");
                      $(".datepicker-here_"+element[1]).remove();

                      if( $("[data-block_img="+element[1]+"]").find("[id*=img_block_"+element[1]).length !== 0){
                        $("#send_" + element[1]).attr("onclick","send_ms(this)");
                        $("#send_" + element[1]).removeClass("disabled");

                      }

                    }
                  });
                }
              }
            }

          }
        });
      } else if ($('#selected_sender_' + element[1]).val() === 'my') {
        $.ajax({
          url: '/ajax/edit_appeal.php',
          data: {
            ID: element[1],
            NAME: cur_el.find(usrname).val(),
            POLICY: cur_el.find(policy).val(),
            TIME: cur_el.find(time).val()
          },
          type: 'post',
          success: function(result) {
            if (result === 'false') {
              $('#success_' + element[1]).html('');
              $('#error_' + element[1]).html('Заполните дату оплаты услуг');
            } else {
              $('#save_' + element[1]).css('display', 'none');

              cur_el.find(usrname).css('display', 'none');
              cur_el.find(policy).css('display', 'none');
              cur_el.find(time).css('display', 'none');

              usrname_p.html(cur_el.find(usrname).val());
              policy_p.html(cur_el.find(policy).val());
              time_p.html(cur_el.find(time).val());

              $('#edit_' + element[1]).css('display', 'inline-block');

              $('#success_' + element[1]).html(result);
              $('#error_' + element[1]).html('');

              usrname_p.css('display', 'block');
              policy_p.css('display', 'block');
              time_p.css('display', 'block');
              var time_p_str = time_p.text().replace(/\s/g, '');

              var policy_str = cur_el.find(policy).val();
              policy_str = policy_str.replace(/\s/g, '');

              var usrname_str = cur_el.find(usrname).val();
              usrname_str = usrname_str.replace(/\s/g, '');

              if (time_p_str.length <= 3 || policy_str.length <= 1 ||
                  usrname_str.length <= 3) {

              } else {
                let data = {
                  "number_polic": cur_el.find(policy).val(),
                  "data_user_oplata_POST": time_p.text(),
                  "data_checkout": "1",
                  "usrname": cur_el.find(usrname).val(),
                  "id": element[1],
                  "hospitl": cur_el.find(hospitl).text(),
                };
                $.ajax({
                  url: '/pdf/file_pdf.php',
                  type: 'POST',
                  data: data,
                  success: function(msg) {

                    $('[data-obrashenie-id=' + element[1] + ']').
                        find(".pdf").
                        attr("href", msg);
                    $('[data-obrashenie-id=' + element[1] + ']').
                        find(".pdf").
                        removeClass("error");
                    $('[data-obrashenie-id=' + element[1] + ']').
                        find(".pdf").
                        text("Просмотреть");
                    $(".ready_pdf").removeClass("hidden");
                    $(".with_out_pdf").addClass("hidden")
                  },
                }).done(function(msg) {
                  $(".datepicker-here_"+element[1]).remove();
                  if( $("[data-block_img="+element[1]+"]").find("[id*=img_block_"+element[1]).length !== 0){

                    $("#send_" + element[1]).attr("onclick","send_ms(this)");
                    $("#send_" + element[1]).removeClass("disabled");
                  }
                  // $("#gif_save_"+element[1]).css({"display":"none"})
                });

              }
            }

          }
        });
      }
    } else {
      $('#success_' + element[1]).html('');
      $('#error_' + element[1]).html('Введите ФИО в формате: "Фамилия Имя Отчество"');
    }
  } else {
    $('#success_' + element[1]).html('');
    $('#error_' + element[1]).html('Введите корректно дату');
  }
}else{
  $('#success_' + element[1]).html('');
  $('#error_' + element[1]).html('Введите корректно полис');
}
}

function send_ms(sd) {
  let element = $(sd)[0].id.split('_');
  if ($('#selected_sender_'+element[1]).val() === 'my') {

    let cur_el = $('#appeal_' + element[1]);
    let usrname = $("input[name='usrname']");
    let policy = $("input[name='policy']");
    let hospitl = $('#hospitl_' + element[1]);
    let time_p = $('#time_' + element[1]);

    let data = {
      "number_polic": cur_el.find(policy).val(),
      "data_user_oplata_POST": time_p.text(),
      "data_checkout": "2",
      "usrname": cur_el.find(usrname).val(),
      "id": element[1],
      "hospitl": cur_el.find(hospitl).text(),
    };


if(time_p.text() != "") {
  $.ajax({
    url: '/pdf/file_pdf.php',
    type: 'POST',
    data: data,
    success: function(msg) {
if(time_p.text().search("2") != -1) {
  $('[data-obrashenie-id=' + element[1] + ']').
      find(".pdf").
      attr("href", msg);

  if ($(".ready_pdf").is(":visible")) {
    $(".ready_pdf").addClass("hidden");
  }
  $(".updata_pdf").removeClass("hidden");
}
      $.ajax({
        url: '/ajax/send_message.php',
        data: {
          ID: element[1],
        },
        type: 'post',
        success: function(result) {
          let result2 = JSON.parse(result);
          let error = $('#error_' + element[1]);

          let success = $('#success_' + element[1]);
          if (result2.error !== undefined) {
            error.text(result2.error);
            success.text('');
          } else if (result2.success !== undefined) {
            $('#appeal_' + element[1]).addClass("sended");
            $.magnificPopup.open({
              items: {
                src: '<div class="white-popup custom_styles_popup">Ваше обращение успешно отправлено!</div>',
                type: 'inline',
              },
              callbacks: {
                afterClose: function() {
                  document.location.reload(true);
                },
              },
            });
            error.text('');
            success.text(result2.success);
          }

        }
      });
    },
  });
}
  } else if ($('#selected_sender_'+element[1]).val() === 'child') {
    if ($('#children_input_' + element[1]).attr('data-id_child').length > 0) {
      if($('#time_' + element[1]).text() !=="") {
        $.ajax({
          url: '/pdf/file_children_pdf.php',
          data: {
            id: $('#children_input_' + element[1]).attr('data-id_child'),
            oplata: $('#time_' + element[1]).text(),
            id_obr: element[1]
          },
          type: 'post',
          success: function(result) {

            if($('#time_' + element[1]).text().search("2") !== -1) {
              $('[data-obrashenie-id=' + element[1] + ']').
                  find(".pdf").
                  attr("href", result);
              if ($(".ready_pdf").is(":visible")) {
                $(".ready_pdf").addClass("hidden");
              }
              $(".updata_pdf").removeClass("hidden");
            }
            $.ajax({
              url: '/ajax/send_message.php',
              data: {
                ID: element[1],
                CHILD: $('#children_input_' + element[1]).attr('data-id_child')
              },
              type: 'post',
              success: function(result) {
                let result2 = JSON.parse(result);
                let error = $('#error_' + element[1]);
                let success = $('#success_' + element[1]);
                if (result2.error !== undefined) {
                  error.text(result2.error);
                  success.text('');
                } else if (result2.success !== undefined) {
                  $('#appeal_' + element[1]).addClass(" sended");
                  $.magnificPopup.open({
                    items: {
                      src: '<div class="white-popup custom_styles_popup">Ваше обращение успешно отправлено!</div>',
                      type: 'inline',
                    },
                    callbacks: {
                      afterClose: function() {
                        document.location.reload(true);
                      },
                    },
                  });
                  error.text('');
                  success.text(result2.success);
                }

              }
            });

          }
        });
      }
    }

  }
}

function commutator(ajax, id) {

  if (ajax === 'child') {
    if ($('#children_input_' + id).attr('data-id_child') != "") {
      $.ajax({
        url: '/pdf/file_children_pdf.php',
        data: {
          id: $('#children_input_' + id).attr('data-id_child'),
          oplata: $('#time_' + id).text(),
          id_obr: id
        },
        type: 'post',
        success: function(result) {
          $('[data-obrashenie-id=' + id + ']').
              find(".pdf").
              attr("href", result);
          $('[data-obrashenie-id=' + id + ']').
              find(".pdf").
              removeClass("error");
          $('[data-obrashenie-id=' + id + ']').
              find(".pdf").
              text("Просмотреть");
          $(".ready_pdf").removeClass("hidden");
          $(".with_out_pdf").addClass("hidden");
        }
      });
    }
  } else if (ajax === 'my') {

      let cur_el = $('#appeal_' + id);
      let usrname = $("input[name='usrname']");
      let policy = $("input[name='policy']");
      let time_p = $('#time_' + id);
      let hospitl = $('#hospitl_' + id);
      let data = {
        "number_polic": cur_el.find(policy).val(),
        "data_user_oplata_POST": time_p.text(),
        "data_checkout": "1",
        "usrname": cur_el.find(usrname).val(),
        "id": id,
        "hospitl": cur_el.find(hospitl).text(),
      };

      if(time_p.text().search(".20") !==  -1 ) {

        $.ajax({
          url: '/pdf/file_pdf.php',
          type: 'POST',
          data: data,
          success: function(msg) {

            $('[data-obrashenie-id=' + id + ']').
                find(".pdf").
                attr("href", msg);
            $('[data-obrashenie-id=' + id + ']').
                find(".pdf").
                removeClass("error");
            $('[data-obrashenie-id=' + id + ']').
                find(".pdf").
                text("Просмотреть");
            $(".ready_pdf").removeClass("hidden");
            $(".with_out_pdf").addClass("hidden")
          },
        })
      }
  }
}
function edit_date(element) {
  var _ = $(element);
  var id = _.attr("data-id-date_picker_edit");
  $("[data-id-date_picker_edit="+id+"]").css({"display":"none"});
  $("[data-id-date_picker_save="+id+"]").css({"display":"block"});
  $("#date_picker_"+id).css({"display":"block"});
  $("#edit_"+id).removeAttr("onclick");
  $("#time_"+id).css({"display":"none"});
  $(".start_"+id).remove();
}
function save_date(sv) {

  var _ = $(sv);
  var id = _.attr("data-id-date_picker_save");
  let usrname = $("input[name='usrname']");
  let cur_el = $('#appeal_' + id);
  let policy = $("input[name='policy']");
  let time = $("input[name='time']");

  let usrname_p = $('#usrname_' + id);
  let policy_p = $('#policy_' + id);
  let time_p = $('#time_' + id);
  let hospitl = $('#hospitl_' + id);


  let regExp2 = /^(\d+)[.](\d+)[.](\d+)/;

    if (cur_el.find(time).val() !== "" || regExp2.test(cur_el.find(time).val())) {

      if ($('#selected_sender_' + id).val() === 'child') {
        $.ajax({
          url: '/ajax/edit_appeal.php',
          data: {
            ID: id,
            TIME: cur_el.find(time).val(),
            CASE:"case",
          },
          type: 'post',

          beforeSend: function() {
            $("#gif_"+id).css({"display":"block"});
          },
          success: function(result) {
            if (result === 'false') {
              $('#success_' + id).html('');
              $('#error_' + id).html('Заполните дату оплаты услуг');
              $("#gif_"+id).css({"display":"none"});
            } else {
              cur_el.find(usrname).css('display', 'none');
              cur_el.find(policy).css('display', 'none');
              cur_el.find(time).css('display', 'none');

              usrname_p.html(cur_el.find(usrname).val());
              policy_p.html(cur_el.find(policy).val());
              time_p.html(cur_el.find(time).val());

              $('#edit_' + id).css('display', 'inline-block');

              $('#success_' + id).html(result);
              $('#error_' + id).html('');

              usrname_p.css('display', 'block');
              policy_p.css('display', 'block');
              time_p.css('display', 'block');
              var time_p_str = time_p.text().replace(/\s/g, '');

              var policy_str = cur_el.find(policy).val();
              policy_str = policy_str.replace(/\s/g, '');

              var usrname_str = cur_el.find(usrname).val();
              usrname_str = usrname_str.replace(/\s/g, '');
              $("#gif_"+id).css({"display":"none"});
              $(".datepicker-here_"+id).remove();


              $("[data-id-date_picker_save="+id+"]").css({"display":"none"});
              $("[data-id-date_picker_edit="+id+"]").css({"display":"block"});
              if (time_p_str.length <= 3 || policy_str.length <= 1 ||
                  usrname_str.length <= 3) {
                $("#gif_"+id).css({"display":"none"});
              } else {
                if ($('#children_input_' + id).attr('data-id_child') != "") {
                  $.ajax({
                    url: '/pdf/file_children_pdf.php',
                    data: {
                      id: $('#children_input_' + id).
                          attr('data-id_child'),
                      oplata: $('#time_' + id).text(),
                      id_obr: id
                    },
                    type: 'post',
                    success: function(result) {
                      console.log('success');
                      $('[data-obrashenie-id=' + id + ']').
                          find(".pdf").
                          attr("href", result);
                      $('[data-obrashenie-id=' + id + ']').
                          find(".pdf").
                          removeClass("error");
                      $('[data-obrashenie-id=' + id + ']').
                          find(".pdf").
                          text("Просмотреть");
                      $(".ready_pdf").removeClass("hidden");
                      $(".with_out_pdf").addClass("hidden");
                      $("#gif_"+id).css({"display":"none"});
                      $("[data-id-date_picker_save="+id+"]").css({"display":"none"});
                      $("[data-id-date_picker_edit="+id+"]").css({"display":"block"});

                      $("#date_picker_"+id).css({"display":"none"});
                      $("#edit_"+id).attr("onclick","edit(this)");
                      $(".datepicker-here_"+id).remove();
                      if( $("[data-block_img="+id+"]").find("[id*=img_block_"+id).length !== 0  && $("#time_"+id).text().search("2") != "-1"){
                        $("#send_" + id).attr("onclick","send_ms(this)");
                        $("#send_" + id).removeClass("disabled");
                      }

                    }
                  });
                }
              }
            }

          }
        });
      } else if ($('#selected_sender_' + id).val() === 'my') {
        $.ajax({
          url: '/ajax/edit_appeal.php',
          data: {
            ID: id,
            CASE:"case",
            TIME: cur_el.find(time).val()
          },
          type: 'post',
          beforeSend: function() {
          $("#gif_"+id).css({"display":"block"});
          },
          success: function(result) {
            if (result === 'false') {
              $('#success_' + id).html('');
              $('#error_' +id).html('Заполните дату оплаты услуг');
              $("#gif_"+id).css({"display":"none"});
            } else {
              cur_el.find(usrname).css('display', 'none');
              cur_el.find(policy).css('display', 'none');
              cur_el.find(time).css('display', 'none');

              usrname_p.html(cur_el.find(usrname).val());
              policy_p.html(cur_el.find(policy).val());
              time_p.html(cur_el.find(time).val());

              $('#edit_' + id).css('display', 'inline-block');

              $('#success_' + id).html(result);
              $('#error_' + id).html('');

              usrname_p.css('display', 'block');
              policy_p.css('display', 'block');
              time_p.css('display', 'block');
              var time_p_str = time_p.text().replace(/\s/g, '');

              var policy_str = cur_el.find(policy).val();
              policy_str = policy_str.replace(/\s/g, '');

              var usrname_str = cur_el.find(usrname).val();
              usrname_str = usrname_str.replace(/\s/g, '');

              if (time_p_str.length <= 3 || policy_str.length <= 1 ||
                  usrname_str.length <= 3) {

              } else {
                let data = {
                  "number_polic": cur_el.find(policy).val(),
                  "data_user_oplata_POST": time_p.text(),
                  "data_checkout": "1",
                  "usrname": cur_el.find(usrname).val(),
                  "id": id,
                  "hospitl": cur_el.find(hospitl).text(),
                };
                $.ajax({
                  url: '/pdf/file_pdf.php',
                  type: 'POST',
                  data: data,
                  success: function(msg) {

                    $('[data-obrashenie-id=' + id + ']').
                        find(".pdf").
                        attr("href", msg);
                    $('[data-obrashenie-id=' + id + ']').
                        find(".pdf").
                        removeClass("error");
                    $('[data-obrashenie-id=' + id + ']').
                        find(".pdf").
                        text("Просмотреть");
                    $(".ready_pdf").removeClass("hidden");
                    $(".with_out_pdf").addClass("hidden")
                  },
                }).done(function(msg) {
                  $("#gif_"+id).css({"display":"none"});
                  $("[data-id-date_picker_save="+id+"]").css({"display":"none"});
                  $("[data-id-date_picker_edit="+id+"]").css({"display":"block"});

                  $("#date_picker_"+id).css({"display":"none"});
                  $("#edit_"+id).attr("onclick","edit(this)");
                  $(".datepicker-here_"+id).remove();
                  if( $("[data-block_img="+id+"]").find("[id*=img_block_"+id).length !== 0  && $("#time_"+id).text().search("2") != "-1"){
                    $("#send_" + id).attr("onclick","send_ms(this)");
                    $("#send_" + id).removeClass("disabled");
                  }
                });

              }
            }

          }
        });
      }

  } else {
    $('#success_' + id).html('');
    $('#error_' + id).html('Введите корректно дату');
  }

}








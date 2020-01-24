//= ../../node_modules/jquery/dist/jquery.min.js

//= ../../node_modules/magnific-popup/dist/jquery.magnific-popup.min.js

$(document).ready(function() {

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
  $( "input[type='file']" ).change(function() {
    let element = this.id;
    let reader = new FileReader();
    reader.readAsDataURL(this.files[0]);
    let $input = $(this);
    let fd = new FormData();
    fd.append("import_file", $input.prop('files')[0]);
    fd.append("id_elem", element);
    let n =  $('#card_' + element);
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
        success: function(result){
          let result2 = JSON.parse(result);

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
              n.find($(".js-img-add")).append(
                  '<div id="img_block_'+ result2.ID +'" class="obrashcheniya__content_sidebar_blocks">\n' +
                  '    <div class="obrashcheniya__content_sidebar_blocks_img">\n' +
                  '        <img src="'+ r +'">\n' +
                  '    </div>\n' +
                  '    <div class="obrashcheniya__content_sidebar_blocks_text">\n' +
                  '        <div class="obrashcheniya__content_sidebar_blocks_text_title">Загруженный документ</div>\n' +
                  '        <a id="download_img" download href="'+ r +'"\n' +
                  '           class="obrashcheniya__content_sidebar_blocks_text_link">скачать</a>\n' +
                  '        <a href="#" rel="nofollow" onclick="del(this)" id="delete_'+ result2.ID +'" class="delete_img_js">удалить</a>\n' +
                  '    </div>\n' +
                  '</div>'
              );
            }
          }
        }
      });
    } else {
      error.text('Вы превысили лимит по загруженным картинкам');
      success.text('');
    }
  });
  // ------ choose_class ------
  $(document).on('click', '.children_input', function() {
    let idItem = $(this).attr('data-value');
    search(idItem);
    $('#searchul_'+idItem).css('display', 'block');
  });
  function search(idItem) {
    $(document).on('click', '#searchul_'+idItem+' li', function() {
      $('#children_input_'+idItem).val($(this).text());
      $('#children_input_'+idItem).attr('data-id_child', $(this).attr('value'));
      $('#searchul_'+idItem).fadeOut();
      $.post('/ajax/obrasheniya/selected_child.php', {ID: $(this).attr('value')}, function(result) {
        $('#selected-child_'+idItem).html(result);
        $.ajax({
          url: '/pdf/file_children_pdf.php',
          data: {
            id: $('#children_input_'+idItem).attr('data-id_child'),
            oplata: $('#time_' + idItem).text(),
            id_obr: idItem
          },
          type: 'post',
          success: function(result) {
            console.log('success');
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
          }
        });
      }, 'html');
    });
    $(document).mouseup(function(e) {
      let container = $('#class_input_'+idItem);
      if (container.has(e.target).length === 0) {
        $('#searchul_'+idItem).fadeOut();
      }
    });

  }
});

function del(f) {

  let element = $(f)[0].id;
  $.ajax({
    url: '/ajax/img_delete.php',
    data: {ID: element},
    type: 'post',
    success: function(result) {
      let r = JSON.parse(result);
      $('#img_block_' + r.ID).remove();
      $('#success_' + r.ID_EL).text('Файл успешно удален!');
      $('#error_' + r.ID_EL).text('');
      document.getElementById(r.ID_EL).value = "";
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
          $(".obrashcheniya").append('<p>У вас нет готовых обращений. Сформировать обращение на возврат средств за медицинскую помощь по программе ОМС можно\n' +
              '           </p>');
        }

        }


    }
  });
}

function edit(ed) {
  let element = $(ed)[0].id.split('_');
  let cur_el = $('#appeal_' + element[1]);
  console.log(cur_el);
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
console.log(cur_el.find(usrname).val());
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
          success: function(result) {
            if (result === 'false') {
              $('#success_' + element[1]).html('');
              $('#error_' + element[1]).html('Заполните дату оплаты услуг');
            } else {
              $.ajax({
                url: '/pdf/file_pdf.php',
                data: {
                  "data_user_oplata_POST": cur_el.find(time).val(),
                  "number_polic": cur_el.find(policy).val(),
                },
                type: 'post',
                success: function(result) {
                  console.log(result)
                }
              });
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
                      console.log('success');
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
              $.ajax({
                url: '/pdf/file_pdf.php',
                data: {
                  "data_user_oplata_POST": cur_el.find(time).val(),
                  "number_polic": cur_el.find(policy).val(),
                },
                type: 'post',
                success: function(result) {
                  console.log(result)
                }
              });
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

    let time_p = $('#time_' + element[1]);

    let data = {
      "number_polic": cur_el.find(policy).val(),
      "data_user_oplata_POST": time_p.text(),
      "data_checkout": "2",
      "usrname": cur_el.find(usrname).val(),
      "id": element[1],
    };

    $.ajax({
      url: '/pdf/file_pdf.php',
      type: 'POST',
      data: data,
      success: function(msg) {

        $('[data-obrashenie-id=' + element[1] + ']').
            find(".pdf").
            attr("href", msg);
        if ($(".ready_pdf").is(":visible")) {
          $(".ready_pdf").addClass("hidden");
        }
        $(".updata_pdf").removeClass("hidden");



        $.ajax({
          url: '/ajax/send_message.php',
          data: {
            ID: element[1],
          },
          type: 'post',
          success: function(result) {
            let result2 = JSON.parse(result);
            console.log(result2);
            let error = $('#error_' + element[1]);
            let success = $('#success_' + element[1]);
            if (result2.error !== undefined) {
              error.text(result2.error);
              success.text('');
            } else if (result2.success !== undefined) {
              $('#appeal_' + element[1]).addClass(" sended");
              error.text('');
              success.text(result2.success);
            }


          }
        });
      },
    });
  } else if ($('#selected_sender_'+element[1]).val() === 'child') {
    console.log('here');
    if ($('#children_input_' + element[1]).attr('data-id_child').length > 0) {
      $.ajax({
        url: '/pdf/file_children_pdf.php',
        data: {
          id: $('#children_input_' + element[1]).attr('data-id_child'),
          oplata: $('#time_' + element[1]).text(),
          id_obr: element[1]
        },
        type: 'post',
        success: function(result) {
          $('[data-obrashenie-id=' + element[1] + ']').
              find(".pdf").
              attr("href", result);
          if ($(".ready_pdf").is(":visible")) {
            $(".ready_pdf").addClass("hidden");
          }
          $(".updata_pdf").removeClass("hidden");
          $.ajax({
            url: '/ajax/send_message.php',
            data: {
              ID: element[1],
              CHILD: $('#children_input_' + element[1]).attr('data-id_child')
            },
            type: 'post',
            success: function(result) {
              let result2 = JSON.parse(result);
              console.log(result2);
              let error = $('#error_' + element[1]);
              let success = $('#success_' + element[1]);
              if (result2.error !== undefined) {
                error.text(result2.error);
                success.text('');
              } else if (result2.success !== undefined) {
                $('#appeal_' + element[1]).addClass(" sended");
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

function commutator(ajax, id) {
  console.log(id);

  if (ajax === 'child') {
    if ($('#children_input_' + id).attr('data-id_child').length > 0) {
      $.ajax({
        url: '/pdf/file_children_pdf.php',
        data: {
          id: $('#children_input_' + id).attr('data-id_child'),
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







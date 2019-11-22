//= ../../node_modules/jquery/dist/jquery.min.js
//= ../../node_modules/air-datepicker/dist/js/datepicker.min.js
//= ../../node_modules/magnific-popup/dist/jquery.magnific-popup.min.js

$(document).ready(function() {
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
      $('#appeal_' + result).remove();
    }
  });
}

function edit(ed) {
  console.log('edit');
  let element = $(ed)[0].id.split('_');
  let cur_el = $('#appeal_' + element[1]);
  console.log(cur_el);
  let usrname = $("input[name='usrname']");
  let policy = $("input[name='policy']");
  let time = $("input[name='time']");

  let usrname_p = $('#usrname_' + element[1]);
  let policy_p = $('#policy_' + element[1]);
  let time_p = $('#time_' + element[1]);


  $('#save_' + element[1]).css('display', 'block');

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
      $.ajax({
        url: '/pdf/file_pdf.php',
        data: {
          "data_user_oplata_POST":cur_el.find(time).val(),
          "number_polic":cur_el.find(policy).val(),
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

      $('#edit_' + element[1]).css('display', 'block');

      $('#success_' + element[1]).html(result);
      $('#error_' + element[1]).html('');

      usrname_p.css('display', 'block');
      policy_p.css('display', 'block');
      time_p.css('display', 'block');
     var time_p_str = time_p.text().replace(/\s/g, '');

     var policy_str = cur_el.find(policy).val();
      policy_str = policy_str.replace(/\s/g, '');

     var usrname_str = cur_el.find(usrname).val()
      usrname_str= usrname_str.replace(/\s/g, '');

      if (time_p_str.length <= 3 || policy_str.length <= 1 || usrname_str.length <= 3) {

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
  });
}

function send_ms(sd) {
  let element = $(sd)[0].id.split('_');
 if( $(".pdf").hasClass("error") ){
  $(".with_out_pdf").removeClass("hidden");
 }else {
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

         },
       }).done(function(msg) {

       });

     }
   });
 }
}







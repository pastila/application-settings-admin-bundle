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
    $.ajax({
      url: '/ajax/img_add.php',
      cache: false,
      contentType: false,
      processData: false,
      data: fd,
      type: 'post',
      success: function(result){
        let result2 = JSON.parse(result);
        let error = $('#error_' + result2.ID);
        let success = $('#success_' + result2.ID);
        if (result2.ERROR !== undefined) {
          error.text(result2.ERROR);
          success.text('');
        } else {
          if (result2.RES === false) {
            error.text('Не удается прочитать файл');
            success.text('');
          } else {
            let n =  $('#card_' + result2.ID);
            error.text('');
            success.text(result2.SUCCESS);
            let r = result2.SRC;
            n.find($(".js-img-add")).html(
                '<div id="img_block_'+ result2.ID +'" class="obrashcheniya__content_sidebar_blocks">\n' +
                '    <div class="obrashcheniya__content_sidebar_blocks_img">\n' +
                '        <img src="'+ r +'">\n' +
                '    </div>\n' +
                '    <div class="obrashcheniya__content_sidebar_blocks_text">\n' +
                '        <div class="obrashcheniya__content_sidebar_blocks_text_title">Загруженный документ</div>\n' +
                '        <a id="download_img" download href="'+ r +'"\n' +
                '           class="obrashcheniya__content_sidebar_blocks_text_link">скачать</a>\n' +
                '        <a href="#" rel="nofollow" onclick="del(this)" id="delete_'+ result2.ID +'" class="delete_img_js">Удалить</a>\n' +
                '    </div>\n' +
                '</div>'
            );
          }
        }
      }
    });
  });
});

function del(f) {
  let element = $(f)[0].id;
  $.ajax({
    url: '/ajax/img_delete.php',
    data: {ID: element},
    type: 'post',
    success: function(result) {
      $('#img_block_' + result).remove();
      $('#success_' + result).text('Файл успешно удален!');
      $('#error_' + result).text('');
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
      $('#save_' + element[1]).css('display', 'none');

      cur_el.find(usrname).css('display', 'none');
      cur_el.find(policy).css('display', 'none');
      cur_el.find(time).css('display', 'none');

      usrname_p.html(cur_el.find(usrname).val());
      policy_p.html(cur_el.find(policy).val());
      time_p.html(cur_el.find(time).val());

      $('#edit_' + element[1]).css('display', 'block');

      $('#success_' + element[1]).html(result);

      usrname_p.css('display', 'block');
      policy_p.css('display', 'block');
      time_p.css('display', 'block');
    }
  });
}





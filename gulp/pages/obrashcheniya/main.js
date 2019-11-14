//= ../../node_modules/jquery/dist/jquery.min.js
//= ../../node_modules/magnific-popup/dist/jquery.magnific-popup.min.js

$(document).ready(function() {



  // $('.delete_img_js').click(function() {
  //   console.log(this);
  //   let element = this.id;
  //   console.log(element);
  //
  //   $.ajax({
  //     url: '/ajax/img_delete.php',
  //     data: {ID: element},
  //     type: 'post',
  //     success: function(result) {
  //       console.log(result);
  //       $('#img_block_' + result).remove();
  //     }
  //   });
  // });

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
                '        <button onclick="del(this)" id="delete_'+ result2.ID +'" class="delete_img_js">Удалить</button>\n' +
                '    </div>\n' +
                '</div>'
            );
            n.find($("img")).attr("src",r);
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



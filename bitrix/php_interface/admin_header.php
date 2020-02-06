<?php

?>

    <style>
        .calendar-custom-styles input {
            height: 22px;
        }

        #ajax-add-answer {
            display: none;
            width: 800px;
            min-height: 400px;
        }

        #layer {
            position: fixed;
            display: none;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .7);
            z-index: 11110;
        }

        #exp-ord {
            position: fixed;
            display: none;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .7);
            z-index: 11110;
        }
        #exp-cmo {
            position: fixed;
            display: none;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .7);
            z-index: 11110;
        }
        #exp-reviews {
            position: fixed;
            display: none;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .7);
            z-index: 11110;
        }
        #export-mo {
            position: fixed;
            display: none;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .7);
            z-index: 11110;
        }
        #exp-company-cmo {
            position: fixed;
            display: none;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, .7);
            z-index: 11110;
        }

        .close-js-price {
            position: fixed;
            top: 180px;
            z-index: 1000;
            background-color: #fff;
            padding: 25px;
            left: calc(50% - 200px);
            cursor: default;
        }

        .close-content-ord {
            text-align: right;
            color: #000;
            font-weight: bold;
            display: block;
            float: right;
            padding: 5px;
            cursor: pointer;
        }
        .close-content-ord-cmo {
            text-align: right;
            color: #000;
            font-weight: bold;
            display: block;
            float: right;
            padding: 5px;
            cursor: pointer;
        }
        .close-content-ord-company-cmo {
            text-align: right;
            color: #000;
            font-weight: bold;
            display: block;
            float: right;
            padding: 5px;
            cursor: pointer;
        }
   .close-content-ord-reviews {
            text-align: right;
            color: #000;
            font-weight: bold;
            display: block;
            float: right;
            padding: 5px;
            cursor: pointer;
        }
        .close-content-ord-export-mo {
            text-align: right;
            color: #000;
            font-weight: bold;
            display: block;
            float: right;
            padding: 5px;
            cursor: pointer;
        }

        .preloader-image {
            position: fixed;
            top: 180px;
            left: 45%;
            z-index: 11111;
        }

        .footer > button {
            margin: 10px 0;
            -webkit-border-radius: 4px;
            border-radius: 4px;
            border: none;
            -webkit-box-shadow: 0 0 1px rgba(0, 0, 0, .11), 0 1px 1px rgba(0, 0, 0, .3), inset 0 1px #fff, inset 0 0 1px rgba(255, 255, 255, .5);
            box-shadow: 0 0 1px rgba(0, 0, 0, .3), 0 1px 1px rgba(0, 0, 0, .3), inset 0 1px 0 #fff, inset 0 0 1px rgba(255, 255, 255, .5);
            background-color: #e0e9ec;
            background-image: -webkit-linear-gradient(bottom, #d7e3e7, #fff) !important;
            background-image: -moz-linear-gradient(bottom, #d7e3e7, #fff) !important;
            background-image: -ms-linear-gradient(bottom, #d7e3e7, #fff) !important;
            background-image: -o-linear-gradient(bottom, #d7e3e7, #fff) !important;
            background-image: linear-gradient(bottom, #d7e3e7, #fff) !important;
            color: #3f4b54;
            cursor: pointer;
            display: inline-block;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-weight: bold;
            font-size: 13px;
            text-shadow: 0 1px rgba(255, 255, 255, 0.7);
            text-decoration: none;
            position: relative;
            vertical-align: middle;
            -webkit-font-smoothing: antialiased;
        }

        .body > input {
            -webkit-box-shadow: none;
            box-shadow: none;
            color: #000;
            height: 19px;
            font-family: Arial, "Helvetica Neue", sans-serif;
            font-size: 13px;
            margin: 0;
            outline: none;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .header > h5 {
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
        }

    </style>
    <div id="layer">
        <div class="preloader-image">
            <img class="preloader" width="500px" src=""/>
        </div>
    </div>
    <div id="ajax-add-answer"></div>
    <div id="exp-ord" style="display: none">
        <div class="close-js-price">
            <div class="close-content-ord">x</div>
            <div class="content">
                <div class="header">
                    <h5 class="title">Импортировать МО</h5>
                </div>
                <div class="body">
                    <input type="file" name="file" id="id-file_mo"/>
                </div>
                <div class="footer">
                    <button id="my_adm_btn_exp_ord" type="button" class="btn btn-primary">Запустить импорт</button>
                </div>
            </div>
        </div>

    </div>
    <div id="exp-cmo" style="display: none">
        <div class="close-js-price">
            <div class="close-content-ord-cmo">x</div>
            <div class="content">
                <div class="header">
                    <h5 class="title">Импортировать СМО</h5>
                </div>
                <div class="body">
                    <input type="file" name="file" id="id-file_cmo"/>
                </div>
                <div class="footer">
                    <button id="my_adm_btn_exp_ord-cmo" type="button" class="btn btn-primary">Запустить импорт</button>
                </div>
            </div>
        </div>
    </div>
    <div id="export-mo" style="display: none">
        <div class="close-js-price">
            <div class="close-content-ord-export-mo">x</div>
            <div class="content">
                <div class="header">
                    <h5 class="title">Экспорт MO</h5>
                </div>
                <div class="body body-export" >
                    <div class="load">Подождите ,идет формирование файла.Примерное время формирование 15 секунд.</div><br>
                    <div class="loaded">После формирования появится кнопка скачать</div>
                </div>
                <div class="footer">

                </div>
            </div>
        </div>
    </div>

    <div id="exp-reviews" style="display: none">
        <div class="close-js-price">
            <div class="close-content-ord-reviews">x</div>
            <div class="content">
                <div class="header">
                    <h5 class="title">Выгрузить отзывы</h5>
                </div>
                <div class="body">
                    <div style="display: flex">

                        <input type="text" value="" tabindex="4" onfocus="setTimeout(function(){$('#first_comment_input').parent().find('.bx-calendar-icon-first_comment').click()},300);" name="TASK[DEADLINE]" id="first_comment_input" />
                        <div style="font-size:15px;font-weight:600;"> C <input  onclick="BX.calendar({node:this, field:'first_comment_input', form: 'ADD_NEW_TASK', bTime: true, currentTime: '<?=(time()+date("Z")+CTimeZone::GetOffset())?>', bHideTime: true});"  class="bx-calendar-icon-first_comment" type="date" style="margin-right: 20px" id="first_comment" ></div>
                        <input type="text" value="" tabindex="4" onfocus="setTimeout(function(){$('#second_comment_input').parent().find('.bx-calendar-icon-second_comment').click()},300);" name="TASK[DEADLINE]" id="second_comment_input" />
                        <div style="font-size:15px;font-weight:600;"> По <input  onclick="BX.calendar({node:this, field:'second_comment_input', form: 'ADD_NEW_TASK-second_comment', bTime: true, currentTime: '<?=(time()+date("Z")+CTimeZone::GetOffset())?>', bHideTime: true});"  class="bx-calendar-icon-second_comment" type="date" style="margin-right: 20px" id="second_comment" ></div>

                    </div>
                </div>
                <div class="footer">
                    <button id="my_adm_btn_exp_ord-reviews" type="button" class="btn btn-primary">Запустить выгрузку</button>
                </div>
            </div>
        </div>
    </div>


    <div id="exp-company-cmo" style="display: none">
        <div class="close-js-price">
            <div class="close-content-ord-company-cmo">x</div>
            <div class="content">
                <div class="header">
                    <h5 class="title">Выгрузить СМО</h5>
                </div>
                <div class="body">
                    <div >
                        <div class="load">Подождите ,идет формирование файла.Примерное время формирование 3 секунды.</div>
                        <div class="loaded">После формирования появится кнопка скачать</div>
                    </div>
                </div>
                <div class="footer footer-cmo">

                </div>
            </div>
        </div>
    </div>


<?php CJSCore::Init('jquery'); ?>


    <script type="text/javascript">
      $(function() {
        /**
         * Добавляем кнопку
         */
        <?php
        ?>
        $('.adm-btn.adm-btn-desktop-gadgets.adm-btn-menu').before('<a title="" id="show-ord-w"' +
            ' class="adm-btn adm-btn-desktop-gadgets adm-btn-test-btn_event" hidefocus="true"' +
            ' href="#">Импортировать МО</a>');
        $('.adm-btn.adm-btn-desktop-gadgets.adm-btn-menu').before('<a title="" id="show-ord-cmo"' +
            ' class="adm-btn adm-btn-desktop-gadgets adm-btn-test-btn_event" hidefocus="true"' +
            ' href="#">Импортировать СМО</a>');
        $('.adm-btn.adm-btn-desktop-gadgets.adm-btn-menu').before('<a title="" id="show-ord-reviews"' +
            ' class="adm-btn adm-btn-desktop-gadgets adm-btn-test-btn_event" hidefocus="true"' +
            ' href="#">Выгрузить отзывы</a>');
        $('.adm-btn.adm-btn-desktop-gadgets.adm-btn-menu').before('<a title="" id="show-ord-export-mo"' +
            ' class="adm-btn adm-btn-desktop-gadgets adm-btn-test-btn_event" hidefocus="true"' +
            ' href="#">Выгрузить MO</a>');
        $('.adm-btn.adm-btn-desktop-gadgets.adm-btn-menu').before('<a title="" id="show-ord-export-cmo"' +
            ' class="adm-btn adm-btn-desktop-gadgets adm-btn-test-btn_event" hidefocus="true"' +
            ' href="#">Выгрузить СMO</a>');

        $(document).ready(function() {


          $('#show-ord-w').on('click', function() {
            $('#exp-ord').show();
          });


          $('#show-ord-cmo').on('click', function() {
            $('#exp-cmo').show();
          });


          $('#show-ord-reviews').on('click', function() {
            $('#exp-reviews').show();
          });





          $('.close-content-ord-company-cmo').on('click', function() {
            $('#exp-company-cmo').hide();
          });


          $('.close-content-ord ').on('click', function() {
            $('#exp-ord').hide();
          });


          $('.close-content-ord-mo ').on('click', function() {
            $('#exp-cmo').hide();
          });


          $('.close-content-ord-export-mo').on('click', function() {
            $('#export-mo').hide();
          });



          $('.close-content-ord-reviews').on('click', function() {
            $('#exp-reviews').hide();
          });



          $("#show-ord-export-cmo").click(function() {
              $.ajax({
                dataType: 'html',
                url: '/ajax/for_admin/export/export_cmo.php',
                type: 'POST',
                beforeSend: function() {

                  $('#exp-company-cmo').show();
                  $(".download-2").remove();

                },
                success: function(msg){
                  $(".footer-cmo").
                      append('<div class="download-2"><a style="font-size:15px;font-weight:600;" href="/' + msg + '">Скачать</a></div>')
                },
              }).done(function(msg) {

              });

          });


          $("#show-ord-export-mo").click(function() {
                $.ajax({
                       dataType: 'html',
                      url: '/ajax/for_admin/export/export_mo.php',
                      type: 'POST',
                      beforeSend: function() {

                        $('#export-mo').show();
                        $(".download").remove();

                      },
                      success: function(msg){


                        $(".loaded").
                            after('<div class="download"><a style="font-size:15px;font-weight:600;" href="/' + msg + '">Скачать</a></div>')
                      },
                    }).done(function(msg) {

                    });
          });














          $('#my_adm_btn_exp_ord-reviews').click(function() {


            var first,second;

            first = $("#first_comment_input").val();
            second = $("#second_comment_input").val();

            var first_split = first.split(".");
            var second_split = second.split(".");
            var new_first_data = first_split[1] +'.'+ first_split[0] +'.'+ first_split [2];
            var new_second_data = second_split[1] +'.'+ second_split[0] +'.'+ second_split [2];

            first  =   Date.parse(new_first_data);
            second  =   Date.parse(new_second_data);


            var total = second - first;

            if(total > 0) {
              var comment_data = {
                "data_1": $("#first_comment_input").val(),
                "data_2": $("#second_comment_input").val(),
              };

              $.ajax({
                dataType: 'html',
                url: '/ajax/for_admin/export/export_reviews.php',
                type: 'POST',
                data: comment_data,
                beforeSend: function() {
                  $(".download").remove();
                },
                success: function(msg) {
                  $("#my_adm_btn_exp_ord-reviews").
                      after('<div class="download"><a style="font-size:15px;font-weight:600;" href="/' + msg + '">Скачать</a></div>')
                },
              }).done(function(msg) {

              });
            }else{
              if($(".download").length != 0){
                $(".download").remove();
              }
              alert("Проверте вводимые даты ,что-то не так");
              return false;
            }










          });




          $('#my_adm_btn_exp_ord-cmo').click(function() {
            var fd = new FormData();

            var input_file = $('#id-file_cmo');

            if (input_file.prop('files')[0] != undefined) {
              fd.append('import_file', input_file.prop('files')[0]);
            }

            $.ajax({

              url: '/ajax/for_admin/import/import_mo.php',
              type: 'POST',
              data: fd,
              processData: false,
              contentType: false,
              beforeSend: function() {

              },
              success: function(msg) {
                if (msg != '0') {
                  $.ajax({

                    url: '/ajax/for_admin/import/run_import_cmo.php',
                    type: 'POST',

                    beforeSend: function() {
                      $('#my_adm_btn_exp_ord-cmo').css({'display': 'none'});
                      $('#my_adm_btn_exp_ord-cmo').
                          after(
                              '<span class="run_span"> импорт файла запущен ,ожидаемое время 20 минут,страницу можно закрыть. </span>');
                      $(document).mouseup(function(e) { // событие клика по веб-документу
                        var div = $('.run_span'); // тут указываем ID элемента
                        if (!div.is(e.target) // если клик был не по нашему блоку
                            && div.has(e.target).length === 0) { // и не по его дочерним элементам
                          div.remove(); // скрываем его
                        }

                      });

                    },
                    success: function(msg) {

                    },
                  }).done(function(msg) {

                  });
                } else {
                  $('#my_adm_btn_exp_ord-cmo').after('<span class="error_span"> Файл не удалось сохранить</span>');
                  $(document).mouseup(function(e) { // событие клика по веб-документу
                    var div = $('.error_span'); // тут указываем ID элемента
                    if (!div.is(e.target) // если клик был не по нашему блоку
                        && div.has(e.target).length === 0) { // и не по его дочерним элементам
                      div.remove(); // скрываем его
                    }
                  });
                }
              },
            }).done(function(msg) {

            });

          });

          $('#my_adm_btn_exp_ord').click(function() {

            var fd = new FormData();

            var input_file = $('#id-file_mo');

            if (input_file.prop('files')[0] != undefined) {
              fd.append('import_file', input_file.prop('files')[0]);
            }

            $.ajax({

              url: '/ajax/for_admin/import/import_mo.php',
              type: 'POST',
              data: fd,
              processData: false,
              contentType: false,
              beforeSend: function() {
                $('#my_adm_btn_exp_ord').css({'display': 'none'});
              },
              success: function(msg) {
                if (msg != 0) {
                  $.ajax({
                    url: '/ajax/for_admin/import/run_import_mo.php',
                    type: 'POST',

                    beforeSend: function() {

                      $('#my_adm_btn_exp_ord').
                          after(
                              '<span class="run_span"> импорт файла запущен ,ожидаемое время 20 минут,страницу можно закрыть. </span>');
                      $(document).mouseup(function(e) { // событие клика по веб-документу
                        var div = $('.run_span'); // тут указываем ID элемента
                        if (!div.is(e.target) // если клик был не по нашему блоку
                            && div.has(e.target).length === 0) { // и не по его дочерним элементам
                          div.remove(); // скрываем его
                        }

                      });
                    },
                    success: function(msg) {
                      console.log(msg);

                    },
                  }).done(function(msg) {

                  });
                } else {
                  $('#my_adm_btn_exp_ord').after('<span class="error_span"> Файл не удалось сохранить</span>');
                  $(document).mouseup(function(e) { // событие клика по веб-документу
                    var div = $('.error_span'); // тут указываем ID элемента
                    if (!div.is(e.target) // если клик был не по нашему блоку
                        && div.has(e.target).length === 0) { // и не по его дочерним элементам
                      div.remove(); // скрываем его
                    }
                  });
                }
              },
            }).done(function(msg) {

            });

          });

        });
      });

    </script>


    <style>

        #content-amodal-schema table {
            text-align: left;
            border-collapse: separate;
            border-spacing: 5px;
            background: #ECE9E0;
            color: #656665;
            border: 16px solid #ECE9E0;
            border-radius: 20px;
        }

        #content-amodal-schema th {
            font-size: 13px;
            text-align: center;
        }

        #content-amodal-schema td {
            background: #F5D7BF;
            padding: 10px;
        }


    </style>

<?php
?>
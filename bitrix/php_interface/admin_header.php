<?php
?>

    <style>
        .calendar-custom-styles input{
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
        #exp-ord{
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
        .preloader-image {
            position: fixed;
            top: 180px;
            left: 45%;
            z-index: 11111;
        }
        .footer > button{
            margin: 10px 0;
            -webkit-border-radius: 4px;
            border-radius: 4px;
            border: none;
            -webkit-box-shadow: 0 0 1px rgba(0,0,0,.11), 0 1px 1px rgba(0,0,0,.3), inset 0 1px #fff, inset
            0 0 1px rgba(255,255,255,.5);
            box-shadow: 0 0 1px rgba(0,0,0,.3), 0 1px 1px rgba(0,0,0,.3), inset 0 1px 0 #fff, inset
            0 0 1px rgba(255,255,255,.5);
            background-color: #e0e9ec;
            background-image: -webkit-linear-gradient(bottom, #d7e3e7, #fff)!important;
            background-image: -moz-linear-gradient(bottom, #d7e3e7, #fff)!important;
            background-image: -ms-linear-gradient(bottom, #d7e3e7, #fff)!important;
            background-image: -o-linear-gradient(bottom, #d7e3e7, #fff)!important;
            background-image: linear-gradient(bottom, #d7e3e7, #fff)!important;
            color: #3f4b54;
            cursor: pointer;
            display: inline-block;
            font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
            font-weight: bold;
            font-size: 13px;
            text-shadow: 0 1px rgba(255,255,255,0.7);
            text-decoration: none;
            position: relative;
            vertical-align: middle;
            -webkit-font-smoothing: antialiased;
        }
        .body > input{
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
        .header > h5{
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
        }

    </style>
    <div id="layer">
        <div class="preloader-image">
            <img class="preloader" width="500px" src="/img/preloader.gif"/>
        </div>
    </div>
    <div id="ajax-add-answer"></div>
    <div id="exp-ord" style="display: none">
        <div class="close-js-price">
            <div class="close-content-ord">x</div>
            <div class="content">
                <div class="header">
                    <h5 class="title">Выгрузка МО</h5>
                </div>
                <div class="body">
                    <input  type="file" name="file"   id="id-from"/>
                </div>
                <div class="footer">
                    <button id="my_adm_btn_exp_ord" type="button" class="btn btn-primary">Выгрузить</button>
                </div>
            </div>
        </div>
    </div>




    <?php CJSCore::Init('jquery'); ?>


    <script type="text/javascript">
        $(function () {
            /**
             * Добавляем кнопку
             */
            <?php
            ?>
            $('.adm-btn.adm-btn-desktop-gadgets.adm-btn-menu').before('<a title="" id="show-ord-w"' +
                ' class="adm-btn adm-btn-desktop-gadgets adm-btn-test-btn_event" hidefocus="true"' +
                ' href="#">Выгрузить МО</a>');


            $(document).ready(function () {
                $('#show-ord-w').on('click',function () {
                    $('#exp-ord').show();
                });
              $('.close-content-ord ').on('click',function () {
                $('#exp-ord').hide();
              });
                $('#my_adm_btn_exp_ord').click(function () {





                    // if (from.length > 0 && to.length > 0) {
                            window.open('/ajax/export_orders_to_csv.php?from=' + from + '&to=' + to);
                    // }
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
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");



CModule::IncludeModule('iblock');
global $USER;?>

<?if($USER->IsAdmin()){?>
    <div style="display: flex">
        <div style="font-size:15px;font-weight:600;"> С <input class="datepicker-here" type="text" style="margin-right: 20px" id="first_comment" ></div>
        <div style="font-size:15px;font-weight:600;"> по <input class="datepicker-here" type="text" style="margin-right: 20px" id="second_comment" ></div>

        <div id="unloading_reviews" style="font-size:15px;font-weight:600;margin-right: 20px">Выгрузить </div>
    </div>

    <script type="text/javascript">
      $("#unloading_reviews").click(function(){
        var first,second;

         first  =   Date.parse($("#first_comment").val());
        second  =   Date.parse($("#second_comment").val());

        var total = second - first;
        if(total > 0) {
          var comment_data = {
            "data_1": $("#first_comment").val(),
            "data_2": $("#second_comment").val(),
          };

          $.ajax({
            dataType: 'html',
            url: '/unloading/unloading.php',
            type: 'POST',
            data: comment_data,
            beforeSend: function() {

            },
            success: function(msg) {
              $("#unloading_reviews").
                  after('<div><a style="font-size:15px;font-weight:600;" href="/' + msg + '">Скачать</a></div>')
            },
          }).done(function(msg) {

          });
        }else{
          alert("Первая дата больше второй");
          return false;
        }
      });
    </script>
<?}else{

}
?>




<?php  require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>

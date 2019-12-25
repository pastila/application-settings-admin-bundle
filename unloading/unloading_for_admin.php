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

        first = $("#first_comment").val();
        second = $("#second_comment").val();

        var first_split = first.split(".");
        var second_split = second.split(".");
        var new_first_data = first_split[1] +'.'+ first_split[0] +'.'+ first_split [2];
        var new_second_data = second_split[1] +'.'+ second_split[0] +'.'+ second_split [2];

         first  =   Date.parse(new_first_data);
        second  =   Date.parse(new_second_data);

        console.log(first,second);
        var total = second - first;
        console.log(total);
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
              $(".download").remove();
            },
            success: function(msg) {
              $("#unloading_reviews").
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
    </script>
<?}else{

}
?>




<?php  require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>

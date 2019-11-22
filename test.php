<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

?>
<div class="e3w">sdsd</div>
<script>
    $(".e3w").click(function() {

      $.ajax({
        type: 'POST',
        url: '/pdf/file_pdf.php',
        data: "sd",

        success: function(result){
         if(result.search("/upload/")!= -1){
             $(".e3w").append('<a href="'+result+'">кликай сюда</a>')
           }else{
           $(".e3w").append('<div>'+result+'</div>')
         }
        }
      });
    })

</script>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>
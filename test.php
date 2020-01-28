<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");


//$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","IBLOCK_SECTION_ID","PROPERTY_*");
//$arFilter = Array("IBLOCK_ID"=>16,"CODE"=>"vlad-volkov");
//$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
//while($ob = $res->GetNextElement()){
//    $arProps = $ob->GetFields();
//   echo '<pre>';
//    print_r($arProps);
//   echo '</pre>';
//}






?>
<!--<div class="e3w">sdsd</div>-->
<script>
  $('.e3w').click(function() {

    $.ajax({
      type: 'POST',
      url: '/pdf/file_children_pdf.php',
      data: 'sd',

      success: function(result) {
        if (result.search('/upload/') != -1) {
          $('.e3w').append('<a href="' + result + '">кликай сюда</a>');
        } else {
          $('.e3w').append('<div>' + result + '</div>');
        }
      },
    });
  });

</script>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
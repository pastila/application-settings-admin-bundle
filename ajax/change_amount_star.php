<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
global $USER;
$section = new CIBlockSection();
$el = new CIBlockElement();
$msg = "";

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_EVALUATION");
$arFilter = Array("IBLOCK_ID"=>13,"ID"=> $_POST['id_rewievs'] );
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement()){
    $arProps = $ob->GetFields();

  if((int)$arProps["PROPERTY_EVALUATION_VALUE"] == (int)$_POST["star"]){
         echo "0";
  }else {
      if ($_POST["star"] != 0) {

      $Prop["EVALUATION"] = $_POST["star"];
      CIBlockElement::SetPropertyValuesEx($arProps["ID"], 13, $Prop);
      echo "1";
  }elseif($_POST["star"] == (int)0){
          $Prop["EVALUATION"] = $_POST["star"];
          CIBlockElement::SetPropertyValuesEx($arProps["ID"], 13, $Prop);
          echo "1";
      }
  }
}

<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
global $USER;
$section = new CIBlockSection();
$el = new CIBlockElement();
$msg = "";
$date_change = date("d.m.Y H:i:s");

$date["DATE_CHANGE_BY_USER"] = $date_change;
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
      CIBlockElement::SetPropertyValuesEx($arProps["ID"], 13, $date);

          $prop = CIBlockElement::GetByID($_POST['id_rewievs'])->GetNextElement()->GetProperties();

          $id_company = $prop["NAME_COMPANY"]["VALUE"];
          $arSelect_otzev = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
          $arFilter_otzev = Array(
              "IBLOCK_ID" => 13,
              "PROPERTY_NAME_COMPANY" => $id_company,
              "!PROPERTY_VERIFIED" => false,
              "PROPERTY_REJECTED" => false,
              "!PROPERTY_EVALUATION"=> 0,
          );
          $res_otzev = CIBlockElement::GetList(Array(), $arFilter_otzev, false, false, $arSelect_otzev);
          $total = 0;
          $count_otzev = $res_otzev->SelectedRowsCount();

              while ($ob_otzev = $res_otzev->GetNextElement()) {

                  $arProp__otzev = $ob_otzev->GetProperties();

                  $total = $total + (int)$arProp__otzev["EVALUATION"]["VALUE"];

              }
              $result = $total / $count_otzev;

              $star_clear = Array(
                  "AMOUNT_STAR" =>round($result,1),
              );
              CIBlockElement::SetPropertyValuesEx($id_company, 16, $star_clear);

          $arSelect = Array("ID", "IBLOCK_ID", "NAME","PROPERTY_AMOUNT_STAR");
          $arFilter = Array("IBLOCK_ID"=>16, "PROPERTY_KPP"=> $prop["KPP"]["VALUE"],"!PROPERTY_AMOUNT_STAR"=> false);
          $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
          $count_company_with_this_kpp =  $res->SelectedRowsCount();

          $total_star = 0;
          $result =0 ;
          $arProps_company =array();
          $array_id_company_with_kpp = array();
          while($ob = $res->GetNextElement()){
              $arProps_company = $ob->GetFields();
              array_push($array_id_company_with_kpp,$arProps_company["ID"]);
              $total_star = $total_star + $arProps_company["PROPERTY_AMOUNT_STAR_VALUE"];
          }



          $result = $total_star / $count_company_with_this_kpp;

          $All_star = Array(
              "ALL_AMOUNT_STAR" => round($result,1),
          );
          foreach ($array_id_company_with_kpp as $key){
              CIBlockElement::SetPropertyValuesEx($key, 16, $All_star);
          }

      echo "1";
  }elseif($_POST["star"] == (int)0){
          $Prop["EVALUATION"] = $_POST["star"];
          CIBlockElement::SetPropertyValuesEx($arProps["ID"], 13, $Prop);
          CIBlockElement::SetPropertyValuesEx($arProps["ID"], 13, $date);

          $prop = CIBlockElement::GetByID($_POST['id_rewievs'])->GetNextElement()->GetProperties();
          $id_company = $prop["NAME_COMPANY"]["VALUE"];

          $arSelect_otzev = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
          $arFilter_otzev = Array(
              "IBLOCK_ID" => 13,
              "PROPERTY_NAME_COMPANY" => $id_company,
              "!PROPERTY_VERIFIED" => false,
              "PROPERTY_REJECTED" => false,
              "!PROPERTY_EVALUATION"=> 0,
          );
          $res_otzev = CIBlockElement::GetList(Array(), $arFilter_otzev, false, false, $arSelect_otzev);
          $total = 0;
          $count_otzev = $res_otzev->SelectedRowsCount();

          while ($ob_otzev = $res_otzev->GetNextElement()) {

              $arProp__otzev = $ob_otzev->GetProperties();

              $total = $total + (int)$arProp__otzev["EVALUATION"]["VALUE"];

          }
          $result = $total / $count_otzev;

          $star_clear = Array(
              "AMOUNT_STAR" =>round($result,1),
          );
          CIBlockElement::SetPropertyValuesEx($id_company, 16, $star_clear);

          $arSelect = Array("ID", "IBLOCK_ID", "NAME","PROPERTY_AMOUNT_STAR");
          $arFilter = Array("IBLOCK_ID"=>16, "PROPERTY_KPP"=> $prop["KPP"]["VALUE"],"!PROPERTY_AMOUNT_STAR"=> false);
          $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
          $count_company_with_this_kpp =  $res->SelectedRowsCount();
          $total_star = 0;
          $result =0 ;
          $arProps_company =array();
          $array_id_company_with_kpp = array();
          while($ob = $res->GetNextElement()){
              $arProps_company = $ob->GetFields();
              array_push($array_id_company_with_kpp,$arProps_company["ID"]);
              $total_star = $total_star + $arProps_company["PROPERTY_AMOUNT_STAR_VALUE"];
          }


          $result = $total_star / $count_company_with_this_kpp;
          $All_star = Array(
              "ALL_AMOUNT_STAR" => round($result,1),
          );
          foreach ($array_id_company_with_kpp as $key){
              CIBlockElement::SetPropertyValuesEx($key, 16, $All_star);
          }


          echo "1";
      }
  }
}

<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
if($_POST){
    CModule::IncludeModule("iblock");


    if($_POST["case"]== "cation"){
      $result =   CIBlockElement::Delete($_POST["id"]);
        if($result == true){
            echo 1;
        }

    }
    if($_POST["case"]== "comment"){
        $result =     CIBlockElement::Delete($_POST["id"]);
        if($result == true){
            echo 1;
        }
    }
    if($_POST["case"]== "review") {

        $prop = CIBlockElement::GetByID($_POST["id"])->GetNextElement()->GetProperties();

        $id_company = $prop["NAME_COMPANY"]["VALUE"];

        $result = CIBlockElement::Delete($_POST["id"]);


        $arSelect_otzev = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
        $arFilter_otzev = Array(
            "IBLOCK_ID" => 13,
            "PROPERTY_NAME_COMPANY" => $id_company,
            "!PROPERTY_VERIFIED" => false,
            "PROPERTY_REJECTED" => false
        );
        $res_otzev = CIBlockElement::GetList(Array(), $arFilter_otzev, false, false, $arSelect_otzev);
        $total = 0;
        $count_otzev = $res_otzev->SelectedRowsCount();
        if ($count_otzev == "0") {
            $star_clear = Array(
                "AMOUNT_STAR" => "",
            );

            CIBlockElement::SetPropertyValuesEx($id_company, 16, $star_clear);
        } else {
            while ($ob_otzev = $res_otzev->GetNextElement()) {

                $arProp__otzev = $ob_otzev->GetProperties();


                $total = $total + (int)$arProp__otzev["EVALUATION"]["VALUE"];


            }
            $result = $total / $count_otzev;

            $total_result = round($result);


            $star_clear = Array(
                "AMOUNT_STAR" =>$total_result,
            );


            CIBlockElement::SetPropertyValuesEx($id_company, 16, $star_clear);

        }
        if ($result == true) {
            echo 1;
        }
    }

}
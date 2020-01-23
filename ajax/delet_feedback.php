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


        $arSelect_otzev = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_*");
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

        if ($count_otzev == "0") {
            $star_clear = Array(
                "AMOUNT_STAR" => "",
            );
            CIBlockElement::SetPropertyValuesEx($id_company, 16, $star_clear);
            $star_clear = Array(
                "ALL_AMOUNT_STAR" => "",
            );
            CIBlockElement::SetPropertyValuesEx($id_company, 16, $star_clear);

            $arSelect = Array("ID", "IBLOCK_ID", "NAME","PROPERTY_AMOUNT_STAR");
            $arFilter = Array("IBLOCK_ID"=>16,"ACTIVE"=> "Y", "PROPERTY_KPP"=> $prop["KPP"]["VALUE"],"!PROPERTY_AMOUNT_STAR"=> false);
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
                "ALL_AMOUNT_STAR" => round($result,2),
            );
            foreach ($array_id_company_with_kpp as $key){
               CIBlockElement::SetPropertyValuesEx($key, 16, $All_star);
            }





        } else {
            while ($ob_otzev = $res_otzev->GetNextElement()) {
                $arProp__otzev = $ob_otzev->GetProperties();
                $total = $total + (int)$arProp__otzev["EVALUATION"]["VALUE"];
            }
            $result = $total / $count_otzev;
            $star_clear = Array(
                "AMOUNT_STAR" =>round($result,2),
            );
            CIBlockElement::SetPropertyValuesEx($id_company, 16, $star_clear);

            $arSelect = Array("ID", "IBLOCK_ID", "NAME","PROPERTY_AMOUNT_STAR");
            $arFilter = Array("IBLOCK_ID"=>16,"ACTIVE"=> "Y", "PROPERTY_KPP"=> $prop["KPP"]["VALUE"],"!PROPERTY_AMOUNT_STAR"=> false);
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
                "ALL_AMOUNT_STAR" => round($result,2),
            );
            foreach ($array_id_company_with_kpp as $key){
                CIBlockElement::SetPropertyValuesEx($key, 16, $All_star);
            }

        }
        if ($result == true) {
            echo 1;
        }
    }

}
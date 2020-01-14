<?php

CModule::AddAutoloadClasses(
    '', // не указываем имя модуля
    array(
        // ключ     - имя класса,
        // значение - путь относительно корня сайта к файлу с классом
        '\Kdteam\ApiSms' => '/kdteam-classes/ApiSms.php',
    )
);

AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "IBlockElementAfterSaveHandler");

AddEventHandler("iblock", "OnBeforeIBlockElementDelete", "OnBeforeIBlockElementDeleteHandler");




    function OnBeforeIBlockElementDeleteHandler($arFields)
    {


        $arSelect = Array("ID", "IBLOCK_ID", "NAME","PROPERTY_NAME_COMPANY","PROPERTY_KPP");
        $arFilter = Array("IBLOCK_ID"=>13,"ID"=>$arFields );
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

        if ($res->SelectedRowsCount() != 0 ) {

           $ob = $res->GetNextElement();
            $Field =  $ob->GetFields();
            $id_company = $Field["PROPERTY_NAME_COMPANY_VALUE"];
            $kpp = $Field["PROPERTY_KPP_VALUE"];

            $arSelect_otzev = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_*");
            $arFilter_otzev = Array(
                "IBLOCK_ID" => 13,
                "PROPERTY_NAME_COMPANY" => $id_company,
                "!PROPERTY_VERIFIED" => false,
                "PROPERTY_REJECTED" => false,
                "!ID" => $arFields,
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
                $arFilter = Array("IBLOCK_ID"=>16, "PROPERTY_KPP"=> $kpp,"!PROPERTY_AMOUNT_STAR"=> false);
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
                    "ALL_AMOUNT_STAR" => $result,
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
                    "AMOUNT_STAR" =>$result,
                );
               CIBlockElement::SetPropertyValuesEx($id_company, 16, $star_clear);

                $arSelect = Array("ID", "IBLOCK_ID", "NAME","PROPERTY_AMOUNT_STAR");
                $arFilter = Array("IBLOCK_ID"=>16, "PROPERTY_KPP"=> $kpp,"!PROPERTY_AMOUNT_STAR"=> false);
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
                    "ALL_AMOUNT_STAR" => $result,
                );
                foreach ($array_id_company_with_kpp as $key){
                   CIBlockElement::SetPropertyValuesEx($key, 16, $All_star);
                }

            }
           }
    }

function IBlockElementAfterSaveHandler($arg1, $arg2 = false, $bInternal = false)
{

    if ($arg1['IBLOCK_ID'] == 13) {

        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
        $arFilter = Array(
            "IBLOCK_ID" => 13,
            "ID" => $arg1["ID"],
        );
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        if ($ob = $res->GetNextElement()) {

            $arProps = $ob->GetProperties();
            $array_id_company_with_kpp = array();
            if ($arProps["NAME_COMPANY"]["VALUE"] != "") {

                $arSelect_otzev = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
                $arFilter_otzev = Array(
                    "IBLOCK_ID" => 13,
                    "PROPERTY_NAME_COMPANY" => $arProps["NAME_COMPANY"]["VALUE"],
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
                    "AMOUNT_STAR" => $result,
                );

                CIBlockElement::SetPropertyValuesEx($arProps["NAME_COMPANY"]["VALUE"], 16, $star_clear);



                $arSelect = Array("ID", "IBLOCK_ID", "NAME","PROPERTY_AMOUNT_STAR");
                $arFilter = Array("IBLOCK_ID"=>16, "PROPERTY_KPP"=> $arProps["KPP"]["VALUE"],"!PROPERTY_AMOUNT_STAR"=> false);
                $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                $count_company_with_this_kpp =  $res->SelectedRowsCount();
                $total_star = 0;

                while($ob = $res->GetNextElement()) {
                    $arProps_company = $ob->GetFields();
                    array_push($array_id_company_with_kpp, $arProps_company["ID"]);


                    $total_star = $total_star + $arProps_company["PROPERTY_AMOUNT_STAR_VALUE"];
                }

                $result = $total_star / $count_company_with_this_kpp;

                $All_star = Array(
                    "ALL_AMOUNT_STAR" => $result,
                );
               foreach ($array_id_company_with_kpp as $key){
                   CIBlockElement::SetPropertyValuesEx($key, 16, $All_star);
               }

            }

        }


    }



}

<?php

CModule::AddAutoloadClasses(
    '', // не указываем имя модуля
    array(
        // ключ     - имя класса,
        // значение - путь относительно корня сайта к файлу с классом
        '\Kdteam\ApiSms' => '/kdteam-classes/ApiSms.php',
        'Mobile_Detect' => '/kdteam-classes/Mobile_Detect.php',
    )
);

AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "IBlockElementAfterSaveHandler");

AddEventHandler("iblock", "OnBeforeIBlockElementDelete", "OnBeforeIBlockElementDeleteHandler");
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "OnBeforeIBlockElementAddHandler");



    function OnBeforeIBlockElementAddHandler(&$arFields)
    {

        if($arFields["IBLOCK_ID"] == "24"){


foreach ($arFields["PROPERTY_VALUES"]['145'] as $key => $region_id){

            $arFields_add = array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => 16,
                "IBLOCK_SECTION_ID" => $region_id["VALUE"],
                "NAME" => $arFields["NAME"],

                "PROPERTY_VALUES" => array(
                    "NAME_BOSS" => $arFields["PROPERTY_VALUES"]['140']["n0"]["VALUE"],
                    "MOBILE_NUMBER" => $arFields["PROPERTY_VALUES"]['134']["n0"]["VALUE"],
                    "MOBILE_NUMBER2" => $arFields["PROPERTY_VALUES"]['142']["n0"]["VALUE"],
                    "MOBILE_NUMBER3" => $arFields["PROPERTY_VALUES"]['143']["n0"]["VALUE"],
                    "EMAIL_FIRST" => $arFields["PROPERTY_VALUES"]['135']["n0"]["VALUE"],
                    "EMAIL_SECOND" => $arFields["PROPERTY_VALUES"]['136']["n0"]["VALUE"],
                    "EMAIL_THIRD" => $arFields["PROPERTY_VALUES"]['137']["n0"]["VALUE"],
                    "KPP" => $arFields["PROPERTY_VALUES"]['144']["n0"]["VALUE"],
                    "LOGO_IMG" => $arFields["PROPERTY_VALUES"]['139']["n0"]["VALUE"],

                )
            );
            $oElement = new CIBlockElement();
            $idElement = $oElement->Add($arFields_add, false, false, true);
            if (!$idElement) {
                echo $oElement->LAST_ERROR;
            }



            }

        }
}




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
                "ACTIVE"=> "Y"
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
                $arFilter = Array("IBLOCK_ID"=>16,"ACTIVE"=> "Y", "PROPERTY_KPP"=> $kpp,"!PROPERTY_AMOUNT_STAR"=> false);
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
                $arFilter = Array("IBLOCK_ID"=>16,"ACTIVE"=> "Y", "PROPERTY_KPP"=> $kpp,"!PROPERTY_AMOUNT_STAR"=> false);
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
           }
    }

function IBlockElementAfterSaveHandler($arg1, $arg2 = false, $bInternal = false)
{

    if ($arg1['IBLOCK_ID'] == 13) {

        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
        $arFilter = Array(
            "IBLOCK_ID" => 13,
            "ID" => $arg1["ID"],
            "ACTIVE"=> "Y",
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
                    "ACTIVE"=> "Y",
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
                    "AMOUNT_STAR" => round($result,2),
                );

                CIBlockElement::SetPropertyValuesEx($arProps["NAME_COMPANY"]["VALUE"], 16, $star_clear);



                $arSelect = Array("ID", "IBLOCK_ID", "NAME","PROPERTY_AMOUNT_STAR");
                $arFilter = Array("IBLOCK_ID"=>16, "ACTIVE"=> "Y", "PROPERTY_KPP"=> $arProps["KPP"]["VALUE"],"!PROPERTY_AMOUNT_STAR"=> false);
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
                    "ALL_AMOUNT_STAR" => round($result,2),
                );
               foreach ($array_id_company_with_kpp as $key){
                   CIBlockElement::SetPropertyValuesEx($key, 16, $All_star);
               }

            }

        }


    }

    if ($arg1['IBLOCK_ID'] == 24) {
        $arSelect = Array("ID", "IBLOCK_ID","ACTIVE","CODE", "NAME", "PROPERTY_*");
        $arFilter = Array(
            "IBLOCK_ID" => 24,
            "ID" => $arg1["ID"],

        );
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        if ($ob = $res->GetNextElement()) {
            $arProps = $ob->GetProperties();
            $arFields = $ob->GetFields();
           $file =    CFile::MakeFileArray($arProps["LOGO_IMG"]['VALUE']);
            $arField = array(
                    "NAME_BOSS" => $arProps["NAME_BOSS"]['VALUE'],
                    "MOBILE_NUMBER" => $arProps["MOBILE_NUMBER"]['VALUE'],
                    "MOBILE_NUMBER2" => $arProps["MOBILE_NUMBER2"]['VALUE'],
                    "MOBILE_NUMBER3" => $arProps["MOBILE_NUMBER3"]['VALUE'],
                    "EMAIL_FIRST" => $arProps["EMAIL_FIRST"]['VALUE'],
                    "EMAIL_SECOND" => $arProps["EMAIL_SECOND"]['VALUE'],
                    "EMAIL_THIRD" => $arProps["EMAIL_THIRD"]['VALUE'],
                    "KPP" => $arProps["KPP"]['VALUE'],
                    "LOGO_IMG" => $file,


            );

            $name =    htmlspecialchars_decode($arFields["NAME"]);

            $arSelect = Array("ID", "IBLOCK_ID", "NAME");
            $arFilter = Array("IBLOCK_ID"=>16,"PROPERTY_KPP"=> $arProps["KPP"]['VALUE'] );
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            while($ob = $res->GetNextElement()){
                $arProperty = $ob->GetFields();

            $el = new \CIBlockElement;
            $el->Update($arProperty["ID"], ['ACTIVE' => $arFields["ACTIVE"],"NAME"=> $name]);
            CIBlockElement::SetPropertyValuesEx($arProperty["ID"], 16, $arField);
            }


            foreach ($arProps["REGION"]["VALUE"] as $region_id) {


                $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
                $arFilter = Array("IBLOCK_ID" => 16, "SECTION_ID" => $region_id, "PROPERTY_KPP"=>$arProps["KPP"]['VALUE']);
                $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                if($ob = $res->SelectedRowsCount() == 0){
                    $arFields_add = array(
                        "ACTIVE" => "Y",
                        "IBLOCK_ID" => 16,
                        "IBLOCK_SECTION_ID" => $region_id,
                        "NAME" => $name,
                        "PROPERTY_VALUES" => array(
                            "NAME_BOSS" => $arProps["NAME_BOSS"]['VALUE'],
                            "MOBILE_NUMBER" => $arProps["MOBILE_NUMBER"]['VALUE'],
                            "MOBILE_NUMBER2" => $arProps["MOBILE_NUMBER2"]['VALUE'],
                            "MOBILE_NUMBER3" => $arProps["MOBILE_NUMBER3"]['VALUE'],
                            "EMAIL_FIRST" => $arProps["EMAIL_FIRST"]['VALUE'],
                            "EMAIL_SECOND" => $arProps["EMAIL_SECOND"]['VALUE'],
                            "EMAIL_THIRD" => $arProps["EMAIL_THIRD"]['VALUE'],
                            "KPP" => $arProps["KPP"]['VALUE'],
                            "LOGO_IMG" => $file,

                        )
                    );

                    $oElement = new CIBlockElement();
                    $idElement = $oElement->Add($arFields_add, false, false, true);
                    if (!$idElement) {
                        echo $oElement->LAST_ERROR;
                    }
                }

            }

        }
    }



}

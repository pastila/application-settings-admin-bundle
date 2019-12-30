<?php

CModule::AddAutoloadClasses(
    '', // не указываем имя модуля
    array(
        // ключ     - имя класса,
        // значение - путь относительно корня сайта к файлу с классом
        '\Kdteam\ApiSms' => '/kdteam-classes/ApiSms.php',
    )
);

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "IBlockElementAfterSaveHandler");


function IBlockElementAfterSaveHandler($arg1, $arg2 = false, $bInternal = false)
{

    if ($arg1['IBLOCK_ID'] == 13) {



    $arSelect = Array("ID", "IBLOCK_ID", "NAME");
    $arFilter = Array("IBLOCK_ID"=>16 );
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement()){
        $arProps = $ob->GetFields();
        $star_clear = Array(
            "AMOUNT_STAR" => "",
        );
        CIBlockElement::SetPropertyValuesEx($arProps["ID"], 16, $star_clear);
    }

        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM");
        $arFilter = Array("IBLOCK_ID"=>16 );
        $resComp = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

        while($ob = $resComp->GetNextElement()){
            $all_amount_star = 0;
            $count = 0; // просто счетчик
            $arProps = $ob->GetFields();
            $ID_company  = $arProps["ID"];
            $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");
            $arFilter = Array("IBLOCK_ID"=>13, "PROPERTY_NAME_COMPANY"=> $ID_company,"!PROPERTY_VERIFIED" => false ,"!PROPERTY_REJECTED" => false);
            $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            $all_otzev= $res->SelectedRowsCount();

            while($ob = $res->GetNextElement()){

                $arPropsElement = $ob->GetProperties();

                ++$count;// просто счетчик
                $all_amount_star = (int)$all_amount_star + (int)$arPropsElement["EVALUATION"]["VALUE"];

                if($count == $all_otzev){
                    $total_star = $all_amount_star /$all_otzev ;
                    $total_star = round($total_star); // средняя оценка из всех отызвов по этой компании

                    $arProperty = Array(
                        "AMOUNT_STAR" =>$total_star,
                    );
                    CIBlockElement::SetPropertyValuesEx($arProps["ID"], 16, $arProperty);
                }
            }
        }

    }


}

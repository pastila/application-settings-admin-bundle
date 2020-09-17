<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
$arOreder = Array("name"=> "asc");
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
$arFilter = Array("IBLOCK_ID" => 16,"SECTION_ID"=>$_POST["id_city"]);
$res = CIBlockElement::GetList($arOreder, $arFilter, false, false, $arSelect);
while ($ob = $res->GetNextElement()) {

    $arFields = $ob->GetFields();
    $arResult[]= [
            "ID"=>$arFields["ID"],
            "NAME"=> $arFields["NAME"]
    ];
    }
echo   json_encode($arResult);



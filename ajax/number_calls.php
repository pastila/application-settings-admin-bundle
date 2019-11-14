<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

//Количество обращений
global $USER;

if (CModule::IncludeModule("iblock")) {
    $arFilterSect = array('IBLOCK_ID' => 11, "UF_USER_ID" => $USER->GetID());
    $rsSect = CIBlockSection::GetList(array(), $arFilterSect);
    if ($arSect = $rsSect->GetNext()) {
        $arSelect = array("ID", "NAME", "DATE_ACTIVE_FROM");
        $arFilter = array("IBLOCK_ID" => 11, "!PROPERTY_SEND_REVIEW" => "1",
            "IBLOCK_SECTION_ID" => $arSect["ID"], "ACTIVE" => "Y");
        $res = CIBlockElement::GetList(
            array(),
            $arFilter,
            false,
            false,
            $arSelect
        );
        while ($ob = $res->GetNextElement()) {
            $arFields[] = $ob->GetFields();
        }
        $countAppeals = count($arFields);
    }
}

echo $countAppeals;


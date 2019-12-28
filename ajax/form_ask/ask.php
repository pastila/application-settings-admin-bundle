<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
global $USER;



$arFields_el = array(
    "ACTIVE" => "Y",
    "IBLOCK_ID" => 11,
    "NAME" => "",
    "PROPERTY_VALUES" => array(
        "FULL_NAME" => $arName,
        "MOBAIL_PHONE" => $phone,
        "HOSPITAL" => $arHospital["NAME"],
        "ADDRESS" => $arRegion["NAME"],

    )
);

$oElement = new CIBlockElement();
$idElement = $oElement->Add($arFields_el);



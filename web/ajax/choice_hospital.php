<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->IncludeComponent(
    "bitrix:catalog.section.list",
    "choice_hospital",
    array(
        "VIEW_MODE" => "LIST",
        "SHOW_PARENT_NAME" => "N",
        "IBLOCK_TYPE" => "",
        "IBLOCK_ID" => "16",
        "SECTION_ID" => $_POST['id'],
        "SECTION_CODE" => "",
        "SECTION_URL" => "",
        "COUNT_ELEMENTS" => "N",
        "TOP_DEPTH" => "1",
        "SECTION_FIELDS" => "",
        "SECTION_USER_FIELDS" => "",
        "ADD_SECTIONS_CHAIN" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
        "CACHE_NOTES" => "",
        "CACHE_GROUPS" => "Y"
    )
);
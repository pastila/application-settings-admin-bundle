<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

$arSelect = array("ID", "IBLOCK_ID", "NAME");
$arFilter = array("IBLOCK_ID" => 16, "SECTION_ID" => $_POST["region_id"], "%NAME" => $_POST["name_hospital"]);
$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
if ($res->SelectedRowsCount() > 0) {
    while ($ob = $res->GetNextElement()) {
        $arProps = $ob->GetFields();
        echo '<li value="' . $arProps['ID'] . '" class="custom-serach__items_item hospital-js">' . $arProps["NAME"] . '</li>';
    }
} else {
    echo 'error_hospital';
}



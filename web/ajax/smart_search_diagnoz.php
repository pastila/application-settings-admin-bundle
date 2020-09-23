<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

$arSelect = array("ID", "IBLOCK_ID", "NAME");
$arFilter = array("IBLOCK_ID" => 8, "SECTION_ID" => $_POST["id"] , "%NAME" => $_POST["name"]);
$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
if ($res->SelectedRowsCount() > 0) {
    while ($ob = $res->GetNextElement()) {
        $arProps = $ob->GetFields();
        echo '<li value="' . $arProps['ID'] . '" class="custom-serach__items_item diagnoz-js">' . $arProps["NAME"] . '</li>';
    }
} else {
    echo 'error_diagnoz';
}

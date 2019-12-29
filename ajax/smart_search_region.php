<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");


$arOrder = array("name" => "ASC");
$arFilter = array("IBLOCK_ID" => 9, "%NAME" => $_POST["name_city"]);
$res = CIBlockSection::GetList($arOrder, $arFilter, false, array());

if ($res->SelectedRowsCount() > 0) {
    while ($ob = $res->GetNext()) {
        echo '<li value="' . $ob['ID'] . '" class="custom-serach__items_item region-js">' . $ob["NAME"] . '</li>';
    }
} else {
    echo 'error_region';
}
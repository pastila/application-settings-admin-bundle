<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");


$arOrder = array("name" => "ASC");
$arFilter = array("IBLOCK_ID" => 8, "%NAME" => $_POST["name"]);
$res = CIBlockSection::GetList($arOrder, $arFilter, false, array());

if ($res->SelectedRowsCount() > 0) {
    while ($ob = $res->GetNext()) {

        echo '<li value="' . $ob['ID'] . '" class="custom-serach__items_item diagnoz_search_js">' . $ob["NAME"] . '</li>';
    }
} else {
    echo 'error';
}
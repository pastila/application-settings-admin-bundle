<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
//echo '<pre>';
//print_r($_POST["name_city"]);
//echo '</pre>';

$arOrder = Array("name "=>"ASC");
$arFilter = Array("IBLOCK_ID" => 9,  "%NAME" => $_POST["name_city"]);
$res = CIBlockSection::GetList(
    $arOrder, $arFilter, false, Array());

if($res->SelectedRowsCount() > 0) {

    while ($ob = $res->GetNext()) {
        echo '<li value="' . $ob['ID'] . '" class="custom-serach__items_item region">' . $ob["NAME"] . '</li>';
    }
}else{
    echo 'error_region';
}
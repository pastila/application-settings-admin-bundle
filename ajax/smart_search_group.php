<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

$arOrder = array("name" => "ASC");
$arFilter = array("IBLOCK_ID" => 8, 'SECTION_ID' => $_POST["id"],  "%NAME" => $_POST["name"], "DEPTH_LEVEL" => 2);
$res = CIBlockSection::GetList(
    $arOrder,
    $arFilter,
    false,
    array()
);

if ($res->SelectedRowsCount() > 0) {
    while ($ob = $res->GetNext()) {
        echo '<li value="' . $ob['ID'] . '" class="custom-serach__items_item group-js">' . $ob["NAME"] . '</li>';
    }
} else {
    echo 'error_group';
}
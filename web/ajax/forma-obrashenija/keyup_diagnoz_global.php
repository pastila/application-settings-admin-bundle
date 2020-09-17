<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
if ($_POST["name"]) {
    $arSelect = array("ID", "NAME", "IBLOCK_ID", "IBLOCK_SECTION_ID");
    $arOrder = array("name" => "ASC");
    $arFilter = array("IBLOCK_ID" => 8, "%NAME" => $_POST["name"]);
    $res = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);
    if ($res->SelectedRowsCount() > 0) {
        while ($ob = $res->GetNextElement()) {
            $arProps = $ob->GetFields();
            echo '<li value="' . $arProps['ID'] . '" data-section="' . $arProps['IBLOCK_SECTION_ID'] . '" class="custom-serach__items_item diagnoz_search_js">' . $arProps["NAME"] . '</li>';
        }
    } else {
        echo 'error';
    }
}

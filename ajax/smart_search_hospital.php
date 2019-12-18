<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
//echo '<pre>';
//print_r($_POST["name_city"]);
//echo '</pre>';



$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_NAME_BOSS","PROPERTY_STREET");
$arFilter = Array("IBLOCK_ID"=>9, "SECTION_ID"=>$_POST["region_id"] , "%NAME"=>$_POST["name_hospital"] );
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
if($res->SelectedRowsCount() > 0) {
    echo '<li value="" id="hospital" class="custom-serach__items_item">Здесь нет моей больницы</li>';
while($ob = $res->GetNextElement()) {
    $arProps = $ob->GetFields();

    echo '<li value="' . $arProps['ID'] . '" class="custom-serach__items_item hospital " data-name-boss="'.$arProps["PROPERTY_NAME_BOSS_VALUE"].'"  data-street="'.$arProps["PROPERTY_STREET_VALUE"].'">' . $arProps["NAME"] . '</li>';
}
}else{
    echo 'error_hospital';
}



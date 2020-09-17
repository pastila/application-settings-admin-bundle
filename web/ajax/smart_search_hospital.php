<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
//echo '<pre>';
//print_r($_POST);
//echo '</pre>';



$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_SECOND_NAME","PROPERTY_LOCATION","PROPERTY_PERSON_NAME","PROPERTY_MIDDLE_NAME","PROPERTY_FULL_NAME");
$arFilter = Array("IBLOCK_ID"=>9, "ACTIVE" => "Y", "SECTION_ID"=>$_POST["region_id"] , "%PROPERTY_FULL_NAME"=>$_POST["name_hospital"],"PROPERTY_YEAR"=>$_POST["year"] );
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
if($res->SelectedRowsCount() > 0) {
    echo '<li value="" id="hospital" class="custom-serach__items_item hospital-empty">Здесь нет моей больницы</li>';
while($ob = $res->GetNextElement()) {
    $arProps = $ob->GetFields();

    echo '<li value="' . $arProps['ID'] . '" class="custom-serach__items_item hospital " data-name-boss="'.$arProps["PROPERTY_SECOND_NAME_VALUE"].' '. $arProps["PROPERTY_PERSON_NAME_VALUE"].' '.$arProps["PROPERTY_MIDDLE_NAME_VALUE"].'"  data-street="'.$arProps["PROPERTY_LOCATION_VALUE"].'">'.$arProps["PROPERTY_FULL_NAME_VALUE"].'</li>';
}
}else{
    echo 'error_hospital';
}



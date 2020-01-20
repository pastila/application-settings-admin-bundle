<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
//echo '<pre>';
//print_r($_POST["name_city"]);
//echo '</pre>';



$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_SECOND_NAME","PROPERTY_LOCATION","PROPERTY_PERSON_NAME","PROPERTY_MIDDLE_NAME","PROPERTY_FULL_NAME","PROPERTY_KPP");
$arFilter = Array("IBLOCK_ID"=>16, "SECTION_ID"=>$_POST["region_id"] , "%NAME"=>$_POST["name_hospital"] );
$res = CIBlockElement::GetList(Array("NAME"=>"ASC"), $arFilter, false, false, $arSelect);
if($res->SelectedRowsCount() > 0) {

    while($ob = $res->GetNextElement()) {
        $arProps = $ob->GetFields();
        $allReviews[$arProps['PROPERTY_KPP_VALUE']] = $arProps;

    }
}else{
    echo 'error_company';
}

foreach ($allReviews as $key){
    echo '<li value="' . $key['ID'] . '" data-kpp="' . $key['PROPERTY_KPP_VALUE'] . '" class="custom-serach__items_item hospital "  >'.$key["NAME"].'</li>';
}

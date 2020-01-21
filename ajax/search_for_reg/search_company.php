<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
//echo '<pre>';
//print_r($_POST["name_city"]);
//echo '</pre>';



$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_KPP","PROPERTY_LOGO_SMO");
$arFilter = Array("IBLOCK_ID"=>16,  "ACTIVE" => "Y", "SECTION_ID"=>$_POST["region_id"],
    "%NAME"=>$_POST["name_hospital"] );
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
if($res->SelectedRowsCount() > 0) {

    while($ob = $res->GetNextElement()) {
        $arProps = $ob->GetFields();
        $allReviews[$arProps['PROPERTY_KPP_VALUE']] = $arProps;
    }
} else {
    echo '<li class="error_region">Компания не найдена</li>';
}

foreach ($allReviews as $key){
    echo '<li value="' . $key['ID'] . '" class="custom-serach__items_item hospital_reg "  >'.$key["NAME"].'</li>';
}



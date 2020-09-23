<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global  $USER;
CModule::IncludeModule('iblock');
$ID =  explode("_", $_POST['ID']);


$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","IBLOCK_SECTION_ID");
$arFilter = Array("IBLOCK_ID"=>11, "ID"=> $ID[2]);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
$section_id = "";
if($ob = $res->GetNextElement()){
    $arProps = $ob->GetFields();
    $section_id = $arProps["IBLOCK_SECTION_ID"];
}
$arSelect = Array("ID", "IBLOCK_ID", "NAME", );
$arFilter = Array("IBLOCK_ID"=>11, "SECTION_ID"=>$section_id, "PROPERTY_SEND_REVIEW"=>false );
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
$count = $res->SelectedRowsCount();

if ($ID[2]) {
    if (CModule::IncludeModule("iblock")) {
        $del = CIBlockElement::Delete($ID[2]);
        
         if ($del) {

             $result['delete'] = $ID[2];
             $result['count'] = $count;

             echo json_encode($result);
         }
    }
}
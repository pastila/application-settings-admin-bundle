<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$ID =  explode("_", $_POST['ID']);
if ($ID[2]) {
    if (CModule::IncludeModule("iblock")) {
         $del = CIBlockElement::Delete($ID[2]);
         if ($del) {
             echo $ID[2];
         }
    }
}
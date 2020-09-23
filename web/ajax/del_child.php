<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$ID = $_POST['ID'];
if ($ID) {
    if (CModule::IncludeModule("iblock")) {
        $del = CIBlockElement::Delete($ID);
    }
}
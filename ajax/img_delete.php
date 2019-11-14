<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$ID =  explode("_", $_POST['ID']);
if ($ID[1]) {
    if (CModule::IncludeModule("iblock")) {
        $el = new CIBlockElement;
        $res = $el->Update($ID[1], array("PREVIEW_PICTURE" => array('del' => 'Y')));
        echo $ID[1];
    }
}


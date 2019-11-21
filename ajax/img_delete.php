<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$ID =  explode("_", $_POST['ID']);
if ($ID[3] === "1") {
    if ($ID[1]) {
        if (CModule::IncludeModule("iblock")) {
            $el = new CIBlockElement;
            $res = $el->Update($ID[1], array("PREVIEW_PICTURE" => array('del' => 'Y')));
        }
    }
} else {
    CModule::IncludeModule("iblock");
    CIBlockElement::SetPropertyValuesEx(
        $ID[1],
        11,
        array("IMG_" . $ID[3] => array('del' => 'Y'))
    );
}

$result['ID'] = $ID[1] . "_img_" . $ID[3];
$result['ID_EL'] = $ID[1];

exit(json_encode($result));

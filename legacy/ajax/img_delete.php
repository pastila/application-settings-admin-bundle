<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/symfony-integration/config_obrashcheniya.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/symfony-integration/obrashcheniya_helper.php");


$ID =  explode("_", $_POST['ID']);



    CModule::IncludeModule("iblock");
    CIBlockElement::SetPropertyValuesEx(
        $ID[1],
        11,
        array("IMG_" . $ID[3] => array('del' => 'Y'))
    );

deleteAppealFileFromSymfony($ID[1], $ID[3]);
$result['ID'] = $ID[1] . "_img_" . $ID[3];
$result['ID_EL'] = $ID[1];

exit(json_encode($result));

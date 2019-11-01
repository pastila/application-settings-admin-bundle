<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if (!empty($_POST['APPEAL_VALUE'])) {

    CModule::IncludeModule("iblock");

    $arSelect = array("ID", "IBLOCK_ID", "PROPERTY_APPEAL", "PREVIEW_TEXT");
    $arFilter = array("IBLOCK_ID" => 10, "PROPERTY_APPEAL_VALUE" => $_POST['APPEAL_VALUE']);

    global $USER;



    $res = CIBlockElement::GetList(
        array(),
        $arFilter,
        false,
        false,
        $arSelect
    );
    if ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $result['FULL_NAME'] = "Ваш диагноз " . $USER->GetFullName();
        $result['DIAGNOZ'] = $arFields['~PREVIEW_TEXT'];

    }
} else {
    $result = 'Ошибка';
}


exit(json_encode($result));

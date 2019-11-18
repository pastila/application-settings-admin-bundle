<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
print_r($_POST);
if (!empty($_POST)) {
    if (CModule::IncludeModule("iblock")) {
        $arSelect = array("IBLOCK_ID", "ID", "PROPERTY_FULL_NAME", "PROPERTY_POLICY", "PROPERTY_VISIT_DATE");
        $arFilter = array("IBLOCK_ID" => 11, "ID" => $_POST['ID'], "!PROPERTY_SEND_REVIEW" => 1);
        $res = CIBlockElement::GetList(
            array(),
            $arFilter,
            false,
            false,
            $arSelect
        );
        if ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            print_r($arFields);

        }

        if ($arFields['PROPERTY_FULL_NAME_VALUE'] === $_POST['NAME']) {
            echo 'Сохранено без изменений';
        } else {
            $time = ConvertDateTime($_POST['TIME'], "DD.MM.YYYY", "s1");
            print_r($time);
            $PROP = array();
            $PROP['FULL_NAME'] = $_POST['NAME'];
            $PROP['POLICY'] = $_POST['POLICY'];
            $PROP['VISIT_DATE'] = $_POST['TIME'];
            CIBlockElement::SetPropertyValuesEx(
                $_POST['ID'],
                11,
                array("FULL_NAME" => $_POST['NAME'],
                    "POLICY" => $_POST['POLICY'],
                    "VISIT_DATE" => date("d.m.Y", strtotime($_POST['TIME']))
                )
            );

        }
    }
}

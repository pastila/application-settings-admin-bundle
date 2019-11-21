<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
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
        }
        if ($arFields['PROPERTY_FULL_NAME_VALUE'] === $_POST['NAME'] and
            $arFields['PROPERTY_POLICY_VALUE'] === $_POST['POLICY'] and
            $arFields['PROPERTY_VISIT_DATE_VALUE'] === $_POST['TIME']) {
            echo 'Сохранено без изменений';
        } else {
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
            echo 'Изменения успешно сохранены';
        }
    }
}

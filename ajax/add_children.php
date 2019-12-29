<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
global $USER;

if (empty($_POST['hospital'])) {
    $result = 'error';
} else {

    $ID = $_POST['id'];
    if ($ID) {
        $del = CIBlockElement::Delete($ID);
    }
    $arFilter = array("IBLOCK_ID" => 21, "NAME" => $USER->GetID());
    $arSelect = array("ID");
    $rsSections = CIBlockSection::GetList(array(), $arFilter, false, $arSelect, false)->GetNext();
    if (empty($rsSections)) {
        $arFields = array("IBLOCK_ID" => 21, "NAME" => $USER->GetID());
        $bs = new CIBlockSection();
        $ID = $bs->Add($arFields);
        if ($ID > 0) {
            $el = new CIBlockElement();
            $arFields_el = array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => 21,
                "IBLOCK_SECTION_ID" => $ID,
                "NAME" => $_POST['name'],
                "PROPERTY_VALUES" => array(
                    "COMPANY" => $_POST['hospital'],
                    "SURNAME" => $_POST['surname'],
                    "PARTONYMIC" => $_POST['patronymic'],
                    "POLICY" => $_POST['policy'],
                )
            );
            $el_id = $el->Add($arFields_el);
            if ($el_id > 0) {
                $result = 'success';
            } else {
                $result = 'error';
            }

        }
    } else {
        $el = new CIBlockElement();
        $arFields_el = array(
            "ACTIVE" => "Y",
            "IBLOCK_ID" => 21,
            "IBLOCK_SECTION_ID" => $rsSections['ID'],
            "NAME" => $_POST['name'],
            "PROPERTY_VALUES" => array(
                "COMPANY" => $_POST['hospital'],
                "SURNAME" => $_POST['surname'],
                "PARTONYMIC" => $_POST['patronymic'],
                "POLICY" => $_POST['policy'],
            )
        );
        $el_id = $el->Add($arFields_el);
        if ($el_id > 0) {
            $result = 'success';
        } else {
            $result = 'error';
        }
    }
}
echo $result;

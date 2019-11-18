<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if ((!empty($_POST['APPEAL_VALUE']) and !empty($_POST['HOSPITAL'])) and json_decode($_POST['DIAGNOZ']) != null) {

    if (!empty($_POST['YEARS'])) {
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
            session_start();
            $_SESSION['YEARS'] = $_POST['YEARS'];
            $_SESSION['APPEAL'] = $_POST['APPEAL_VALUE'];
            $_SESSION['REGION'] = $_POST['REGION'];
            $_SESSION['HOSPITAL'] = $_POST['HOSPITAL'];
            $_SESSION['CLASS'] = $_POST['CLASS'];
            $_SESSION['GROUP'] = $_POST['GROUP'];
            $_SESSION['SUBGROUP'] = $_POST['SUBGROUP'];
            $_SESSION['DIAGNOZ'] = $_POST['DIAGNOZ'];
//        }

    } else {
        $result = 'erroriche';
    }
} else {
    $result = 'error';
}

exit(json_encode($result));

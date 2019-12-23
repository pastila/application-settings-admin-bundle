<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if ((!empty($_POST['APPEAL_VALUE']) and !empty($_POST['HOSPITAL'])) and json_decode($_POST['DIAGNOZ']) != null) {

    if (!empty($_POST['YEARS'])) {

        $rsUser = CUser::GetByID($USER->GetID());
        $person = $rsUser->Fetch();
        CModule::IncludeModule("iblock");
        $new_str = "";
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
            $result['DIAGNOZ'] = $arFields['PREVIEW_TEXT'];

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


        $id_company = $person["UF_INSURANCE_COMPANY"];
        $prop=CIBlockElement::GetByID($id_company)->GetNextElement()->GetProperties();
        $file_comment = CFile::ResizeImageGet($prop["LOGO_IMG"]["VALUE"], array('width'=>300, 'height'=>600), BX_RESIZE_IMAGE_PROPORTIONAL, true);
        $url_logo_company = $file_comment["src"];



        $html_block = "<div class='block__four_r'> <img src='$url_logo_company' alt=''> </div>";


        $text = 'Если вы ещё не платили рекомендуем позвонить страховому 
        представителю" '.$prop["MOBILE_NUMBER"]["VALUE"].'"';

        $new_str =   str_replace("#MOBAIL_CONTACTS#",$text,$result['DIAGNOZ']);

        $new_str =   str_replace("#URL_LOGO_COMPANY#",$html_block, $new_str);
        $result['DIAGNOZ'] = $new_str;

    } else {
        $result = 'error';
    }
} else {
    $result = 'error';
}

exit(json_encode($result));
//URL_LOGO_COMPANY MOBAIL_CONTACTS
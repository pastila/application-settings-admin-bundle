<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if ((!empty($_POST['APPEAL_VALUE']) and !empty($_POST['HOSPITAL'])) and json_decode($_POST['DIAGNOZ']) != null) {

    if (!empty($_POST['YEARS'])) {
      /*
       * https://jira.accurateweb.ru/browse/BEZBAHIL-39
       * От предложения звонить в страховую и вывода горячей линии компании отказываемся, так как пользователь ещё
       * не указал свою смо. То есть предложение звонить страховому представителю нужно убирать вообще.
       */
//        $rsUser = CUser::GetByID($USER->GetID());
//        $person = $rsUser->Fetch();
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
            // $result['FULL_NAME'] = "Ваш диагноз " . $USER->GetFullName();
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


      /*
       * https://jira.accurateweb.ru/browse/BEZBAHIL-39
       * От предложения звонить в страховую и вывода горячей линии компании отказываемся, так как пользователь ещё
       * не указал свою смо. То есть предложение звонить страховому представителю нужно убирать вообще.
       */
//        $id_company = $person["UF_INSURANCE_COMPANY"];
//        $prop=CIBlockElement::GetByID($id_company)->GetNextElement()->GetProperties();
//        $file_comment = CFile::ResizeImageGet($prop["LOGO_IMG"]["VALUE"], array('width'=>200, 'height'=>400), BX_RESIZE_IMAGE_PROPORTIONAL, true);
//        $url_logo_company = $file_comment["src"];



//        $html_block = "<div class='block__four_r_items'> <img src='$url_logo_company' alt=''> </div>";


//        $text = 'Горячая линия: <div> '.$prop["MOBILE_NUMBER"]["VALUE"]. '</div><div> ' .$prop["MOBILE_NUMBER2"]["VALUE"]. '</div><div>  ' .$prop["MOBILE_NUMBER3"]["VALUE"].'</div>';

//        $new_str =   str_replace("#MOBAIL_CONTACTS#",$text,$result['DIAGNOZ']);

//        $new_str =   str_replace("#URL_LOGO_COMPANY#",$html_block, $new_str);

      $new_str =   str_replace("#MOBAIL_CONTACTS#", '', $result['DIAGNOZ']);
      $new_str =   str_replace("#URL_LOGO_COMPANY#", '', $new_str);

      $result['DIAGNOZ'] = $new_str;

    } else {
        $result = 'error';
    }
} else {
    $result = 'error';
}

exit(json_encode($result));
//URL_LOGO_COMPANY MOBAIL_CONTACTS

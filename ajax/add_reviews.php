<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
global $USER;
$section = new CIBlockSection();
$el = new CIBlockElement();
if ($_POST) {

    $id_user = $USER->GetID();
    $email_user = $USER->GetEmail();
    $arParams = array("replace_space" => "-", "replace_other" => "-");
    $trans = Cutil::translit($_POST["head"], "ru", $arParams);

    $data = date("d.m.Y h:i:s");
    $trans .= $data;

    $name = $_POST["head"];
//    $name .= "   " . $data;


    $arFilter = Array("IBLOCK_ID" => 13, "NAME" => $email_user);
    $Section = CIBlockSection::GetList(false, $arFilter, false);
    if ($ob_section = $Section->GetNext()) {
            $arFields = Array(
                "IBLOCK_ID" => 13,
                "ACTIVE" => "Y",
                "IBLOCK_SECTION_ID" => $ob_section["ID"],
                "PROPERTY_VALUES" => array(
                    "REGION" => $_POST["id_city"],
                    "NAME_COMPANY" => $_POST["id_compani"],
                    "TEXT_MASSEGE" => $_POST["text"],
                    "EVALUATION" => $_POST["star"],
                    "NAME_USER" => $id_user,
                ),
                "NAME" => $name,
                "CODE" => $trans,

            );


            if ($PRODUCT_ID = $el->Add($arFields)) {
                $prop=CIBlockElement::GetByID($_POST["id_compani"])->GetNextElement()->GetProperties();
                $date = date("Y:m:d H:i");
                $url = $_SERVER["SERVER_NAME"] . "/feedback/comment-".$PRODUCT_ID."/";
                $arSend =array(
                    "EMAIL"=>   $prop["EMAIL_FIRST"]["VALUE"],
                    "DATE" => $date,
                    "URL"=> $url,
                );
                CEvent::Send("SEND_FEEDBACK_BOSS",s1,$arSend);
                echo 1;
            } else {
                echo "Error: " . $el->LAST_ERROR;
            }

    }else {

        $arFieldssection = array(
            "ACTIVE" => "Y",
            "IBLOCK_ID" => 13,
            "NAME" => $email_user,
            "CODE" => $email_user,
        );

        $ID = $section->Add($arFieldssection);
        if ($ID) {
            $arFields = Array(
                "IBLOCK_ID" => 13,
                "ACTIVE" => "Y",
                "IBLOCK_SECTION_ID" => $ID,
                "PROPERTY_VALUES" => array(
                    "REGION" => $_POST["id_city"],
                    "NAME_COMPANY" => $_POST["id_compani"],
                    "TEXT_MASSEGE" => $_POST["text"],
                    "EVALUATION" => $_POST["star"],
                    "NAME_USER" => $id_user,
                ),
                "NAME" => $name,
                "CODE" => $trans,

            );

            if ($PRODUCT_ID = $el->Add($arFields)) {

                $prop=CIBlockElement::GetByID($_POST["id_compani"])->GetNextElement()->GetProperties();
                $date = date("Y:m:d H:i");
                $url = $_SERVER["NAME"] . "/feedback/comment-".$PRODUCT_ID."/";
                $arSend =array(
                    "EMAIL"=>   $prop["SERVER_NAME"]["VALUE"],
                    "DATE" => $date,
                    "URL"=> $url,
                );
                CEvent::Send("SEND_FEEDBACK_BOSS",s1,$arSend);

                echo 1;
            } else {
                echo "Error: " . $el->LAST_ERROR;
            }
        }
    }


}

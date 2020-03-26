<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
global $USER;
$section = new CIBlockSection();
$el = new CIBlockElement();
if ($_POST) {


    if ($_POST["name_user_no_Authorized"] == "") {
        if ($_POST["letter"] == 1) {
            $REVIEW_LETTER = 1;
        } else {
            $REVIEW_LETTER = 0;
        }

        $id_user = $USER->GetID();
        $email_user = $USER->GetEmail();


        $data = date("d-m-Y-h-i-s");
        $code = "user_authorized_". $data;


        $name = $_POST["head"];


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
                    "REVIEW_LETTER" => $REVIEW_LETTER,
                    "KPP" => $_POST["kpp"],
                ),
                "NAME" => $name,
                "CODE" => $code,
            );


            if ($PRODUCT_ID = $el->Add($arFields)) {
                $prop = CIBlockElement::GetByID($_POST["id_compani"])->GetNextElement()->GetProperties();
                $date = date("Y:m:d H:i");
                $url = $_SERVER["SERVER_NAME"] . "/feedback/comment-" . $PRODUCT_ID . "/";
                $arMail = array();

                if(strlen($prop["EMAIL_FIRST"]["VALUE"]) > 5 ){
                     array_push($arMail,$prop["EMAIL_FIRST"]["VALUE"]);
                }
                if(strlen($prop["EMAIL_SECOND"]["VALUE"]) > 5 ){
                    array_push($arMail,$prop["EMAIL_SECOND"]["VALUE"]);
                }
                if(strlen($prop["EMAIL_THIRD"]["VALUE"]) > 5 ){
                    array_push($arMail,$prop["EMAIL_THIRD"]["VALUE"]);
                }

                $arSend = array(
                    "EMAIL" => $arMail,
                    "DATE" => $date,
                    "URL" => $url,
                );
                CEvent::Send("SEND_FEEDBACK_BOSS", s1, $arSend);
                echo 1;
            } else {
                echo "Error: " . $el->LAST_ERROR;
            }

        } else {

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
                        "REVIEW_LETTER" => $REVIEW_LETTER,
                        "KPP" => $_POST["kpp"],
                    ),
                    "NAME" => $name,
                    "CODE" => $code,

                );

                if ($PRODUCT_ID = $el->Add($arFields)) {
                    $arMail =array();
                    $prop = CIBlockElement::GetByID($_POST["id_compani"])->GetNextElement()->GetProperties();
                    $date = date("Y:m:d H:i");
                    $url = $_SERVER["SERVER_NAME"] . "/feedback/comment-" . $PRODUCT_ID . "/";
                    if(strlen($prop["EMAIL_FIRST"]["VALUE"]) > 5 ){
                        array_push($arMail,$prop["EMAIL_FIRST"]["VALUE"]);
                    }
                    if(strlen($prop["EMAIL_SECOND"]["VALUE"]) > 5 ){
                        array_push($arMail,$prop["EMAIL_SECOND"]["VALUE"]);
                    }
                    if(strlen($prop["EMAIL_THIRD"]["VALUE"]) > 5 ){
                        array_push($arMail,$prop["EMAIL_THIRD"]["VALUE"]);
                    }

                    $arSend = array(
                        "EMAIL" => $arMail,
                        "DATE" => $date,
                        "URL" => $url,
                    );
                    CEvent::Send("SEND_FEEDBACK_BOSS", s1, $arSend);

                    echo 1;
                } else {
                    echo "Error: " . $el->LAST_ERROR;
                }
            }
        }
    } else {
        $arParams = array("replace_space" => "-", "replace_other" => "-");
        $trans = Cutil::translit($_POST["head"], "ru", $arParams);

        $data = date("d-m-Y-h-i-s");
        $code = "user_no_authorized_". $data;
        $name = $_POST["head"];

        $arFilter = Array("IBLOCK_ID" => 13, "CODE" => "no_authorized");
        $Section = CIBlockSection::GetList(false, $arFilter, false);
        if ($ob_section = $Section->GetNext()) {

            $arFields = Array(
                "IBLOCK_ID" => 13,
                "ACTIVE" => "Y",
                "IBLOCK_SECTION_ID" => $ob_section['ID'],
                "PROPERTY_VALUES" => array(
                    "REGION" => $_POST["id_city"],
                    "NAME_COMPANY" => $_POST["id_compani"],
                    "TEXT_MASSEGE" => $_POST["text"],
                    "EVALUATION" => $_POST["star"],
                    "USER_NO_AUTH" => $_POST["name_user_no_Authorized"],
                    "KPP" => $_POST["kpp"],
                ),
                "NAME" => $name,
                "CODE" => $code,
            );

            if ($PRODUCT_ID = $el->Add($arFields)) {

                $arMail =array();
                $prop = CIBlockElement::GetByID($_POST["id_compani"])->GetNextElement()->GetProperties();
                $date = date("Y:m:d H:i");
                $url = $_SERVER["SERVER_NAME"] . "/feedback/comment-" . $PRODUCT_ID . "/";

                if(strlen($prop["EMAIL_FIRST"]["VALUE"]) > 5 ){
                    array_push($arMail,$prop["EMAIL_FIRST"]["VALUE"]);
                }
                if(strlen($prop["EMAIL_SECOND"]["VALUE"]) > 5 ){
                    array_push($arMail,$prop["EMAIL_SECOND"]["VALUE"]);
                }
                if(strlen($prop["EMAIL_THIRD"]["VALUE"]) > 5 ){
                    array_push($arMail,$prop["EMAIL_THIRD"]["VALUE"]);
                }

                $arSend = array(
                    "EMAIL" => $arMail,
                    "DATE" => $date,
                    "URL" => $url,
                );
                CEvent::Send("SEND_FEEDBACK_BOSS", s1, $arSend);

                echo 1;
            }
        } else {
            $arFieldssection = array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => 13,
                "NAME" => "Отзывы не авторезированных людей",
                "CODE" => "no_authorized",
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
                        "USER_NO_AUTH" => $_POST["name_user_no_Authorized"],
                        "KPP" => $_POST["kpp"],
                    ),
                    "NAME" => $name,
                    "CODE" => $code,
                );
                if ($PRODUCT_ID = $el->Add($arFields)) {
                    $arMail =array();
                    $prop = CIBlockElement::GetByID($_POST["id_compani"])->GetNextElement()->GetProperties();
                    $date = date("Y:m:d H:i");
                    $url = $_SERVER["SERVER_NAME"] . "/feedback/comment-" . $PRODUCT_ID . "/";
                    if(strlen($prop["EMAIL_FIRST"]["VALUE"]) > 5 ){
                        array_push($arMail,$prop["EMAIL_FIRST"]["VALUE"]);
                    }
                    if(strlen($prop["EMAIL_SECOND"]["VALUE"]) > 5 ){
                        array_push($arMail,$prop["EMAIL_SECOND"]["VALUE"]);
                    }
                    if(strlen($prop["EMAIL_THIRD"]["VALUE"]) > 5 ){
                        array_push($arMail,$prop["EMAIL_THIRD"]["VALUE"]);
                    }

                    $arSend = array(
                        "EMAIL" => $arMail,
                        "DATE" => $date,
                        "URL" => $url,
                    );
                    CEvent::Send("SEND_FEEDBACK_BOSS", s1, $arSend);

                    echo 1;
                }
            }
        }
      }
    }

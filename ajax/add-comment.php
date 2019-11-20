<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
global $USER;
$section = new CIBlockSection();
$el = new CIBlockElement();
if ($_POST) {
    $id_user = $USER->GetID();
    $email_user = $USER->GetEmail();
    $id_review = $_POST["id_review"];
    $message = $_POST["message"];
    $name = date("d.m.Y h:i:s");
    $name .= "__" . $email_user;
    $arFields = Array(
        "ACTIVE" => "Y",
        "IBLOCK_ID" => 14,
        "NAME" => $name,
        "CODE" => $name,
        "PROPERTY_VALUES" => array(
            "COMMENTS" => $message,
            "REWIEW" => $id_review,
            "AVTOR_COMMENTS" => $id_user,
        ),
    );

    if ($COMMENT = $el->Add($arFields)) {

     echo 1;
    } else {
        echo "Error: " . $el->LAST_ERROR;
    }

    $new_comments = Array(
        0 => $COMMENT,
    );

    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");
    $arFilter = Array("IBLOCK_ID"=>13,"ID"=> $id_review);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement()){
        $arProps = $ob->GetProperties();

        $list_comments = $arProps["COMMENTS_TO_REWIEW"]["VALUE"];

        $all_comments = array_merge($list_comments,$new_comments);
    }

    $arProperty = Array(
        "COMMENTS_TO_REWIEW" =>$all_comments,
    );

  CIBlockElement::SetPropertyValuesEx($id_review, 13, $arProperty);



}
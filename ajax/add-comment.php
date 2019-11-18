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
    $arProperty = Array(
        "COMMENTS_TO_REWIEW" =>$COMMENT,
    );
    CIBlockElement::SetPropertyValuesEx($id_review, 13, $arProperty);



}
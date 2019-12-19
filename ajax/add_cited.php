<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
global $USER;
$section = new CIBlockSection();
$el = new CIBlockElement();

if ($_POST) {
    $id_user = $USER->GetID();
    $email_user = $USER->GetEmail();
    $rsUser = CUser::GetByID($id_user);
    $person = $rsUser->Fetch();
    if($person["UF_REPRESENTATIVE"] == "1"){
        $representative = 1;
    }else{
        $representative = 0;
    }
    $id_comment = $_POST["id_comment"];
    $message = $_POST["message"];
    $name = date("d.m.Y h:i:s");
    $name .= "__" . $email_user;
    $arFields = Array(
        "ACTIVE" => "Y",
        "IBLOCK_ID" => 15,
        "NAME" => $name,
        "CODE" => $name,
        "PROPERTY_VALUES" => array(
            "CIATION" => $message,
            "CIATION_TO_COMMENTS" => $id_comment,
            "AVTOR_CIATION" => $id_user,
            "REPRESENTATIVE" => $representative,
        ),
    );
    if ($CITED = $el->Add($arFields)) {
        echo 1;
    } else {
        echo "Error: " . $el->LAST_ERROR;
    }

    $arProperty = Array(
        "CITED" =>$CITED,
    );
    CIBlockElement::SetPropertyValuesEx($id_comment, 14, $arProperty);



}
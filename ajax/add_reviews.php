<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
global $USER;

if($_POST) {

    $email_user = $USER->GetID();
    $arParams = array("replace_space"=>"-","replace_other"=>"-");
    $trans = Cutil::translit($_POST["head"],"ru",$arParams);

    $data = date("d.m.Y h:i:s");
    $trans.= $data;

    $name = $_POST["head"] ;
    $name .= "   " .$data;


    $arFields = Array(
        "IBLOCK_ID" => 13,
        "ACTIVE" => "Y",
        "PROPERTY_VALUES"=> array(
            "REGION" => $_POST["id_city"],
            "NAME_COMPANY" => $_POST["id_compani"],
            "TEXT_MASSEGE" => $_POST["text"],
            "EVALUATION" => $_POST["star"],
            "NAME_USER" => $email_user,
        ),
        "NAME" => $name,
        "CODE"=> $trans,

    );



    $el = new CIBlockElement();
    if ($PRODUCT_ID = $el->Add($arFields)) {
        echo 1;
    } else {
        echo "Error: " . $el->LAST_ERROR;
    }
}

<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
global $USER;
$section = new CIBlockSection();
$el = new CIBlockElement();
if ($_POST) {
    $id_user = $USER->GetID();
    $rsUser = CUser::GetByID($id_user);
    $person = $rsUser->Fetch();
    if($person["UF_REPRESENTATIVE"] == "1"){
        $representative = 1;
    }else{
        $representative = 0;
    }


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
            "REPRESENTATIVE" => $representative,
        ),
    );

    if ($COMMENT = $el->Add($arFields)) {

    echo 1;
    } else {
        echo "Error: " . $el->LAST_ERROR;
    }



    $list_comments = [];
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");
    $arFilter = Array("IBLOCK_ID"=>13,"ID"=> $id_review);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement()){
        $arProps = $ob->GetProperties();

    if(is_array($arProps["COMMENTS_TO_REWIEW"]["VALUE"])){
        $list_comments += $arProps["COMMENTS_TO_REWIEW"]["VALUE"];
    }
    }

    array_push($list_comments, $COMMENT);



    $arProperty = Array(
        "COMMENTS_TO_REWIEW" =>$list_comments,
    );


  CIBlockElement::SetPropertyValuesEx($id_review, 13, $arProperty);



}
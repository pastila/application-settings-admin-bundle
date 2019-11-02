<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
//if($_POST){
    $name = "влад";//
    $familia = "volkov";//
    $otchestvo = "oleksandrovich";//
    $email = "dvvwvert@gmail.com";//
    $tel = "+3809567017568";//
    $nomer_polica = "43434343dsds";
    $name_compani = "владOOG";
    $parol = "123456";

    global $USER;
    global $DB;
    $arFields = Array(
        "NAME" => $name,
        "LAST_NAME" => $familia,
        "SECOND_NAME" => $otchestvo,
        "EMAIL" => $email,
        "LOGIN" => $email,
        "ACTIVE" => "Y",
        "GROUP_ID" => array(3, 4, 5),
        "PASSWORD" => $parol,
        "CONFIRM_PASSWORD" => $parol,
        "PERSONAL_MOBILE" => $tel,
        "UF_INSURANCE_POLICY" =>$nomer_polica,
        "UF_INSURANCE_COMPANY" => $name_compani
    );
    $ID = $USER->Add($arFields);
    if($ID){
        $Send = Array(
            "NAME" => $name,
            "LAST_NAME" => $familia,
            "SECOND_NAME" => $otchestvo,
            "LOGIN" => $email,
            "UF_INSURANCE_POLICY" =>$nomer_polica,
            "UF_INSURANCE_COMPANY" => $name_compani
        );
        CEvent::SendImmediate("NEW_USER", "s1", $Send);
    }
    echo $ID;
//}
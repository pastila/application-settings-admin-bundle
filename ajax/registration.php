<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
if($_POST) {
    $name = $_POST["name"];
    $familia = $_POST["famaly-name"];
    $otchestvo = $_POST["last-name"];
    $email = $_POST["email"];
    $tel = $_POST["phone"];
    $nomer_polica = $_POST["number_polic"];
    $name_compani = $_POST["company"];
    $parol = $_POST["password"];

    global $USER;
    global $DB;

    $filter = Array(
        "EMAIL" => $email,
    );
    $order = array('sort' => 'asc');
    $tmp = 'sort';
    $rsUser = CUser::GetList($order, $tmp, $filter);

    if ($rsUser->SelectedRowsCount() > 0) {
                echo "Уже существует";
    } else {
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
            "UF_INSURANCE_POLICY" => $nomer_polica,
            "UF_INSURANCE_COMPANY" => $name_compani
        );
        $ID = $USER->Add($arFields);
        $USER->Authorize($ID);
        if ($ID) {
            $Send = Array(
                "NAME" => $name,
                "LAST_NAME" => $familia,
                "SECOND_NAME" => $otchestvo,
                "LOGIN" => $email,
                "UF_INSURANCE_POLICY" => $nomer_polica,
                "UF_INSURANCE_COMPANY" => $name_compani
            );
            CEvent::SendImmediate("NEW_USER", "s1", $Send);
        }
        echo $ID;
    }
}
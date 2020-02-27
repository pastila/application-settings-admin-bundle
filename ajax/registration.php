<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
$result = array();

if ($_POST) {
    global $USER;
    $filter = array("EMAIL" => $_POST['email']);
    $rsUsers = CUser::GetList(($by = "name"), ($order = "ASC"), $filter);
    while ($arUser = $rsUsers->Fetch()) {
        $arSpecUser = $arUser;
    }
    if ($arSpecUser) {
        $result['error'] = 'mail';
    } else {
        $filter_polic = array("UF_INSURANCE_POLICY" => $_POST['number_polic']);
        $rsUsers_polic = CUser::GetList(($by = "name"), ($order = "ASC"), $filter_polic);

        while ($arUser_polic = $rsUsers_polic->Fetch()) {
            $arSpecUser_polic = $arUser_polic;
        }
        if ($arSpecUser_polic) {
            $result['error'] = 'polic';
        } else {
            session_start();
            $name = $_POST["name"];
            $familia = $_POST["famaly-name"];
            $otchestvo = $_POST["last-name"];
            $email = $_POST["email"];
            $tel = $_POST["phone"];
            $nomer_polica = $_POST["number_polic"];
            $id_company = $_POST["company"];
            $parol = $_POST["password"];
            $id_region = $_POST["id_region"];
            global $USER;
            global $DB;

            $filter = Array(
                "EMAIL" => $email,
            );
            $order = array('sort' => 'asc');
            $tmp = 'sort';
            $rsUser = CUser::GetList($order, $tmp, $filter);


            $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM");
            $arFilter = Array("IBLOCK_ID" => 16, "ID" => $id_company);
            $res_company = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
            if ($res_company->SelectedRowsCount() == 0) {
                $msg = array("company" => "Нет компании");
                $result = array_merge($result, $msg);

            } elseif ($rsUser->SelectedRowsCount() > 0) {
                $msg = array("user_already" => "Уже существует");
                $result = array_merge($result, $msg);
            } else {
                if ($_POST['sms-code'] != "") {
                    if ($_POST['sms-code'] !== $_SESSION['SMS_CODE']) {
                        $result['error_sms'] = 'Неправильный код подтверждения';
                        echo json_encode($result);
                        return;
                    } else {
                        $arFile = CFile::MakeFileArray("/images/user.png");
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
                            "PERSONAL_PHONE" => $tel,
                            "UF_INSURANCE_POLICY" => $nomer_polica,
                            "UF_INSURANCE_COMPANY" => $id_company,
                            "UF_REGION" => $id_region,
                            "PERSONAL_BIRTHDAY" => $_POST["time"],
                            "PERSONAL_PHOTO" => $arFile,
                        );
                        $ID = $USER->Add($arFields);
                        $ID_user = array("user_success" => "success");
                        $result = array_merge($result, $ID_user);
                        $USER->Authorize($ID);
                    }
                } else {
                    $result['error_phone'] = 'Подтвердите номер телефона';
                    echo json_encode($result);
                    return;
                }
            }

        }
    }

echo json_encode($result,true);
}
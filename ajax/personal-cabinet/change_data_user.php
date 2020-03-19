<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
$bs = new CIBlockSection;
global $USER;
$user = new CUser;
$data_user_old = "";
$rsUser = CUser::GetByID($USER->GetID());
$person = $rsUser->Fetch();
$array_field = Array();

$clear_email = str_replace(" ","",$_POST["email"]);
//echo '<pre>';
//print_r($clear_email);
//echo '</pre>';
//echo '<pre>';
//print_r($person["EMAIL"]);
//echo '</pre>';
if ($person["EMAIL"] == $clear_email) {
        $array_field += ["EMAIL" => $clear_email];
} else {

    $filter = Array(
        "EMAIL" => $_POST["email"],
    );
    $order = array('sort' => 'asc');
    $tmp = 'sort';
    $rsUser = CUser::GetList($order, $tmp, $filter);

    if ($rsUser->SelectedRowsCount() > 0) {
        $result["user_email_already"] = "Пользователь с таким эмейлом уже есть";
    }

}
//if($person["UF_INSURANCE_POLICY"] == $_POST["uf_insurance_policy"]){
//
//}else{
//
//    $filter = Array(
//        "UF_INSURANCE_POLICY" => $_POST["uf_insurance_policy"],
//    );
//    $order = array('sort' => 'asc');
//    $tmp = 'sort';
//    $rsUser = CUser::GetList($order, $tmp, $filter);
//
//    if ($rsUser->SelectedRowsCount() > 0) {
//
//        $result["user_policy_already"] = "Пользователь с таким полисом уже есть";
//    }
//
//}

if(isset($result["user_email_already"]) || isset($result["user_policy_already"])){
    exit(json_encode($result));
}else {
    if ($_POST["name"] != "") {
        $clear_name = str_replace(" ", "", $_POST["name"]);
        $array_field += ["NAME" => $clear_name];
    }
    if ($_POST["email"] != "") {
        $clear_email = str_replace(" ", "", $_POST["email"]);
        $array_field += ["EMAIL" => $clear_email];
    }
    if ($_POST["last_name"] != "") {
        $clear_last_name = str_replace(" ", "", $_POST["last_name"]);
        $array_field += ["LAST_NAME" => $clear_last_name];
    }
    if ($_POST["second_name"] != "") {
        $clear_second_name = str_replace(" ", "", $_POST["second_name"]);
        $array_field += ["SECOND_NAME" => $clear_second_name];
    }
//if ($_POST["personal_phone"] != "") {
//
//    $array_field += ["PERSONAL_PHONE" => $_POST["personal_phone"]];
//}


    if ($_POST["uf_insurance_policy"] != "") {

        $array_field += ["UF_INSURANCE_POLICY" => $_POST["uf_insurance_policy"]];
    }

    if ($_POST["id_company"] != "" && $_POST["id_company"] != "0") {
        $array_field += ["UF_REGION" => $_POST["town"]];
        $array_field += ["UF_INSURANCE_COMPANY" => $_POST["id_company"]];
    }
    if ($_FILES['import_file']) {
        $uploads_dir = '/var/www/upload/logo_users/';
        $name = basename($_FILES["import_file"]["name"]);
        $data = date('Y-m-d-h:i:s');
        $data .= $name;
        $uploads_dir .= $data;
        move_uploaded_file($_FILES["import_file"]["tmp_name"], $uploads_dir);

        $arFile = CFile::MakeFileArray($uploads_dir);
        $arFile['del'] = "Y";
        $arFile['old_file'] = $person["PERSONAL_PHOTO"];
        $arFile["MODULE_ID"] = "main";
        $array_field += ["PERSONAL_PHOTO" => $arFile];
    }

    $result["true"] = $user->Update($USER->GetID(), $array_field);


    $arFields_user = array(
        "IBLOCK_ID" => 13,
        "NAME" => $person["EMAIL"]
    );
    $rsSections = CIBlockSection::GetList(array('LEFT_MARGIN' => 'ASC'), $arFields_user);
    $rsSections_res = $rsSections->GetNext();
    $arFields = array(
        "NAME" => $_POST["email"]
    );
    $bs->Update($rsSections_res["ID"], $arFields, true, true, false);

    $arFields_user = array(
        "IBLOCK_ID" => 11,
        "NAME" => $person["EMAIL"]
    );
    $rsSections = CIBlockSection::GetList(array('LEFT_MARGIN' => 'ASC'), $arFields_user);
    $rsSections_res = $rsSections->GetNext();
    $arFields = array(
        "NAME" => $_POST["email"]
    );
    $bs->Update($rsSections_res["ID"], $arFields, true, true, false);


    if ($user->LAST_ERROR != "") {
        echo $strError .= $user->LAST_ERROR;
    }

    echo json_encode($result);
}

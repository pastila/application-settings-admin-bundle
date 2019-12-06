<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
global $USER;
$user = new CUser;
$ID_USER = $USER->GetID();
$rsUser = CUser::GetByID($ID_USER);
$person = $rsUser->Fetch();
$array_field = Array();
$filter = Array(
    "EMAIL" => $_POST["email"],
);
$order = array('sort' => 'asc');
$tmp = 'sort';
$rsUser = CUser::GetList($order, $tmp, $filter);
if($rsUser->SelectedRowsCount() > 0){
    echo "Пользователь с таким эмейлом уже есть";
    die();
}

if($_POST["name"] != ""){
    $array_field +=["NAME"=>$_POST["name"]];
}
if($_POST["last_name"] != ""){
    $array_field +=["LAST_NAME"=>$_POST["last_name"]];
}
if($_POST["second_name"] != ""){
     $array_field +=["SECOND_MANE"=>$_POST["second_name"]];
}
if($_POST["personal_phone"] != ""){
     $array_field +=["PERSONAL_PHONE"=>$_POST["personal_phone"]];
}
if($_POST["email"] != ""){
     $array_field +=["EMAIL"=>$_POST["email"]];
}
if($_POST["uf_insurance_policy"] != ""){
     $array_field +=["UF_INSURANCE_POLICY"=>$_POST["uf_insurance_policy"]];
}
//if($_POST["town"] != ""){
//    $array_field +=["UF_INSURANCE_COMPANY"=>$_POST["town"]];
//}
if($_POST["id_company"] != ""){
     $array_field +=["UF_INSURANCE_COMPANY"=>$_POST["id_company"]];
}
if($_FILES['import_file']){
    $uploads_dir = '/var/www/upload/logo_users/';
    $name = basename($_FILES["import_file"]["name"]);
    $data= date('Y-m-d-h:i:s');
    $data.=$name;
    $uploads_dir.=$data;
    move_uploaded_file($_FILES["import_file"]["tmp_name"],$uploads_dir);

    $arFile = CFile::MakeFileArray($uploads_dir);
    $arFile['del'] = "Y";
    $arFile['old_file'] = $person["PERSONAL_PHOTO"];
    $arFile["MODULE_ID"] = "main";
    $array_field +=["PERSONAL_PHOTO" =>$arFile];
}

$true = $user->Update($ID_USER, $array_field);

if($user->LAST_ERROR != ""){
    echo $strError .= $user->LAST_ERROR;
}
echo $true;

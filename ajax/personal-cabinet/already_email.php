<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
if($_POST){
    $filter = Array("EMAIL" => $_POST["email"]);
    $sql = CUser::GetList(($by="id"), ($order="desc"), $filter);
    echo $sql->SelectedRowsCount();

}
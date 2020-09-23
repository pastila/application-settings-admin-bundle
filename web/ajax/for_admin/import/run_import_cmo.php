<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");


//print_r(exec("id -Gn"));

exec("php  start_import_cmo.php > log_cmo.txt");





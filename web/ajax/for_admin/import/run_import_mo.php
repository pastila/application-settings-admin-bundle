<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");


//print_r(exec("id -Gn"));

 exec("php  start_import_mo.php > log_mo.txt");





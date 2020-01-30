<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");


//print_r(exec("id -Gn"));

 exec("php -q  star_import_mo.php > log_mo.txt");





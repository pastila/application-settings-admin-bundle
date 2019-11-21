<?php

$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . "/..");

$start = microtime(true);

if (empty($_SERVER['DOCUMENT_ROOT'])) {
    $dir = explode(DIRECTORY_SEPARATOR, __DIR__);
    array_pop($dir);
    $_SERVER["DOCUMENT_ROOT"] = implode(DIRECTORY_SEPARATOR, $dir);
}

ini_set('error_reporting', E_ALL);

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define('CHK_EVENT', true);
define('SITE_ID', 's1');

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use \Bitrix\Main\Loader as Loader;
use \Bitrix\Iblock as Iblock;
use Bitrix\Catalog;

CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");
CModule::IncludeModule("iblock");

global $DB;
@set_time_limit(0);
@ignore_user_abort(true);

// код сброса буфферов битрикса
ob_end_clean();
ob_end_clean();
ob_end_clean();
ob_end_clean();
ob_end_clean();
ob_end_clean();


$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM");
$arFilter = Array("IBLOCK_ID"=>16 );
$resComp = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while($ob = $resComp->GetNextElement()){
    $all_amount_star = 0;
    $count = 0; // просто счетчик
    $arProps = $ob->GetFields();
   $ID_company  = $arProps["ID"];
   $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");
   $arFilter = Array("IBLOCK_ID"=>13, "PROPERTY_NAME_COMPANY"=> $ID_company );
   $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    $all_otzev= $res->SelectedRowsCount();

    while($ob = $res->GetNextElement()){

       $arPropsElement = $ob->GetProperties();
       ++$count;// просто счетчик
       $all_amount_star = (int)$all_amount_star + (int)$arPropsElement["EVALUATION"]["VALUE"];

       if($count == $all_otzev){
           $total_star = $all_amount_star /$all_otzev ;
           $total_star = round($total_star); // средняя оценка из всех отызвов по этой компании

           $arProperty = Array(
               "AMOUNT_STAR" =>$total_star,
           );
           CIBlockElement::SetPropertyValuesEx($arProps["ID"], 16, $arProperty);
       }
   }
}
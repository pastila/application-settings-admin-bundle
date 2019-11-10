<?php
//$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . "/..");
//
//$start = microtime(true);
//
//if (empty($_SERVER['DOCUMENT_ROOT'])) {
//    $dir = explode(DIRECTORY_SEPARATOR, __DIR__);
//    array_pop($dir);
//    $_SERVER["DOCUMENT_ROOT"] = implode(DIRECTORY_SEPARATOR, $dir);
//}
//
//ini_set('error_reporting', E_ALL);
//
//define("NO_KEEP_STATISTIC", true);
//define("NOT_CHECK_PERMISSIONS", true);
//define('CHK_EVENT', true);
//define('SITE_ID', 's1');
//
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
//
//use \Bitrix\Main\Loader as Loader;
//use \Bitrix\Iblock as Iblock;
//use Bitrix\Catalog;
//
//CModule::IncludeModule("catalog");
//CModule::IncludeModule("sale");
//CModule::IncludeModule("iblock");
//
//global $DB;
global $USER;
//@set_time_limit(0);
//@ignore_user_abort(true);
//
//// код сброса буфферов битрикса
//ob_end_clean();
//ob_end_clean();
//ob_end_clean();
//ob_end_clean();
//ob_end_clean();
//ob_end_clean();
//


$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM" ,"UF_USER_ID","UF_CHEKED");
$arFilter = Array("IBLOCK_ID" =>11);
$section = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect, false);  // получили секцию юзера
while ($Section = $section->GetNext()) {

    if($Section["UF_CHEKED"] == ""){
        $rsUser = CUser::GetByID($Section["UF_USER_ID"]);
        $arUser = $rsUser->Fetch();


        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "CREATED_DATE","IBLOCK_SECTION_ID", "PROPERTY_*");
        $arFilter = Array("IBLOCK_ID" => 11 , "SECTION_ID" => $Section["ID"]);
        $Element = CIBlockElement::GetList(Array("created" => "desc"), $arFilter, false, Array("nPageSize"=>1), $arSelect); //получили обращения юзера
        if($obElement = $Element->GetNextElement()) {
            $arFields = $obElement->GetFields();


            $newDate = FormatDate("d.m.Y", MakeTimeStamp($arFields["CREATED_DATE"]));
            $time = strtotime($newDate);
            $Difference_time = (int)$_SERVER["REQUEST_TIME"] - (int)$time;

            if($Difference_time > 2592000){    //больше чем 30 дней

              $url = "devdoc1.kdteam.su/feedback/index.php";
              $arEventFields = array(
                "MAIL" => $arUser["EMAIL"],
                "DATE_APPEAL" => $newDate,
                "URL_COMMENTS" => $url ,
            );
                CEvent::Send("APPEAL_REMINDER", "s1", $arEventFields);
                
            }
        }



    }
}





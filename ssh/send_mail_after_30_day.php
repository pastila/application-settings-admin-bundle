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
CModule::IncludeModule("iblock");

use \Bitrix\Main\Loader as Loader;
use \Bitrix\Iblock as Iblock;
use Bitrix\Catalog;

CModule::IncludeModule("catalog");
CModule::IncludeModule("sale");


global $DB;
global $USER;
@set_time_limit(0);
@ignore_user_abort(true);

// код сброса буфферов битрикса
ob_end_clean();
ob_end_clean();
ob_end_clean();
ob_end_clean();
ob_end_clean();
ob_end_clean();

$oElement = new CIBlockSection();

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM" ,"UF_USER_ID","UF_CHEKED_SEND");
$arFilter = Array("IBLOCK_ID" =>11);
$section = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect, false);  // получили секцию юзера
while ($Section = $section->GetNext()) {

    if($Section["UF_CHEKED_SEND"] == "" || $Section["UF_CHEKED_SEND"] == 0){

        $rsUser = CUser::GetByID($Section["UF_USER_ID"]);
        $arUser = $rsUser->Fetch();

        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "CREATED_DATE","IBLOCK_SECTION_ID", "PROPERTY_SEND_MESSAGE");
        $arFilter = Array("IBLOCK_ID" => 11 , "SECTION_ID" => $Section["ID"]);
        $Element = CIBlockElement::GetList(Array("PROPERTY_SEND_MESSAGE" => "desc"), $arFilter, false, false, $arSelect); //получили обращения юзера

        if($obElement = $Element->GetNextElement()) {

            $arFields = $obElement->GetFields();

            if ($arFields["PROPERTY_SEND_MESSAGE_VALUE"]) {

                $newDate = FormatDate("d.m.Y", MakeTimeStamp($arFields["PROPERTY_SEND_MESSAGE_VALUE"]));
                $time = strtotime($newDate);
                $Difference_time = (int)$_SERVER["REQUEST_TIME"] - (int)$time;

                if ($Difference_time > 2592000) {    //больше чем 30 дней 2592000
                echo '<pre>';
                print_r($arFields["ID"]);
                echo '</pre>';
                    $url = "devdoc1.kdteam.su/add-feedback/?letter";
                    $arEventFields = array(
                        "MAIL" => $arUser["EMAIL"],

                        "URL_COMMENTS" => $url ,
                    );

                    CEvent::Send("APPEAL_REMINDER", "s1", $arEventFields);
                    $res =  $oElement->Update($Section["ID"],array("UF_CHEKED_SEND" => 1),false,true);

                    if(!$res)
                        echo $oElement->LAST_ERROR;

                }
            }

        }
    }
}





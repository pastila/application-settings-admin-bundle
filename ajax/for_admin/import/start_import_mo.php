<?php

$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . "/../../..");


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

require( $_SERVER["DOCUMENT_ROOT"]. "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

use \Bitrix\Main\Loader as Loader;
use \Bitrix\Iblock as Iblock;



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

require '/var/www/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$dir = '/var/www/upload/import/sparrower_mo.xls';




$all_hospital = array();
$arSelect = Array("ID", "IBLOCK_ID", "NAME");
$arFilter = Array("IBLOCK_ID" => 9);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while ($ob = $res->GetNextElement()) {
    $arProps = $ob->GetFields();
    $all_hospital[] = $arProps["ID"];
}
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
$spreadsheet = $reader->load($dir);
$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, false, true);


$arMediaFields = [
    'Наименование субъекта Российской Федерации' => '',
    'Код медицинской организации' => '',
    'Полное наименование медицинской организации' => '',
    'Краткое наименование медицинской организации' => '',
    'Адрес МО' => '',
    'Фамилия руководителя' => '',
    'Имя руководителя' => '',
    'Отчество руководителя' => '',
];

$flag = false;

unset($sheetData[1]);
$arCode = array();
foreach ($sheetData as $k => $p) {

    if ($p["A"] == "") {
        continue;
    } else {


        $arSelect = Array("ID", "IBLOCK_ID", "NAME");
        $arFilter = Array("IBLOCK_ID" => 9, "PROPERTY_MEDICAL_CODE" => $p["B"]);
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        if ($res->SelectedRowsCount() == 0) {
            $arCode[] = $p;
        }
        if ($ob = $res->GetNextElement()) {
            $el = new CIBlockElement;
            $arProps = $ob->GetFields();
            if (($key = array_search($arProps["ID"], $all_hospital)) !== false) {

                unset($all_hospital[$key]);
            }
            $arProperties = $ob->GetProperties();
            $arYEAR = array();

            $result_search_year = array_search(date("Y"), $arProperties["YEAR"]['VALUE']);
            if ($result_search_year === false) {
                $arYEAR = $arProperties["YEAR"]['VALUE'];
                $arYEAR[] = date("Y");
            } else {
                $arYEAR = $arProperties["YEAR"]['VALUE'];
            }

            $arProp = array(
                "SECOND_NAME" => $p["F"],
                "PERSON_NAME" => $p["G"],
                "MIDDLE_NAME" => $p["H"],
                "FULL_NAME" => $p["C"],
                "LOCATION" => $p["E"],
                "YEAR" => $arYEAR,
                "MEDICAL_CODE" => $p["B"]
            );
            $Array = array(
                "NAME" => $p["D"],
                "PROPERTY_VALUES" => $arProp,
            );


                if ($res = $el->Update($arProps["ID"], $Array)) {
                    echo "update ID: " . $arProps["ID"] . PHP_EOL;
                } else {
                    echo "Error: " . $el->LAST_ERROR;
                }
        }


    }



}

if (isset($all_hospital)) {
    foreach ($all_hospital as $key_id) {

        $el = new \CIBlockElement;
        if ($el->Update($key_id, ["ACTIVE" => "N"])) {
            echo "activity removed: " . $key_id . PHP_EOL;


        }
    }
}
    foreach ($arCode as $key) {


        $id_section = "";
        preg_match_all("/([0-9]+)\s+(.+)/", $key["A"], $new_region);
        $arFilter = Array('IBLOCK_ID' => 9, '%NAME' => $new_region[2][0]);
        $db_list = CIBlockSection::GetList(Array(), $arFilter, false);
        if ($ar_result = $db_list->GetNext()) {

            $id_section = $ar_result['ID'];

        }


        if ($id_section > 0) {

            $arFields = array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => 9,
                "IBLOCK_SECTION_ID" => $id_section,
                "NAME" =>$key["D"],
                "PROPERTY_VALUES" => array(
                    "SECOND_NAME" => $key["F"],
                    "PERSON_NAME" => $key["G"],
                    "MIDDLE_NAME" => $key["H"],
                    "FULL_NAME" => $key["C"],
                    "LOCATION" => $key["E"],
                    "YEAR" => array(date("Y")),
                    "MEDICAL_CODE" => $key["B"]

                )
            );


                $oElement = new CIBlockElement();
                $idElement = $oElement->Add($arFields, false, false, true);
                echo "element new ID: " . $idElement . PHP_EOL;



        }
    }

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

$dir = '/var/www/upload/import/sparrower_cmo.xls';


$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
$spreadsheet = $reader->load($dir);
$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, false, true);


function translit($str)
{
    $rus = array(
        'А',
        'Б',
        'В',
        'Г',
        'Д',
        'Е',
        'Ё',
        'Ж',
        'З',
        'И',
        'Й',
        'К',
        'Л',
        'М',
        'Н',
        'О',
        'П',
        'Р',
        'С',
        'Т',
        'У',
        'Ф',
        'Х',
        'Ц',
        'Ч',
        'Ш',
        'Щ',
        'Ъ',
        'Ы',
        'Ь',
        'Э',
        'Ю',
        'Я',
        'а',
        'б',
        'в',
        'г',
        'д',
        'е',
        'ё',
        'ж',
        'з',
        'и',
        'й',
        'к',
        'л',
        'м',
        'н',
        'о',
        'п',
        'р',
        'с',
        'т',
        'у',
        'ф',
        'х',
        'ц',
        'ч',
        'ш',
        'щ',
        'ъ',
        'ы',
        'ь',
        'э',
        'ю',
        'я'
    );
    $lat = array(
        'A',
        'B',
        'V',
        'G',
        'D',
        'E',
        'E',
        'Gh',
        'Z',
        'I',
        'Y',
        'K',
        'L',
        'M',
        'N',
        'O',
        'P',
        'R',
        'S',
        'T',
        'U',
        'F',
        'H',
        'C',
        'Ch',
        'Sh',
        'Sch',
        'Y',
        'Y',
        'Y',
        'E',
        'Yu',
        'Ya',
        'a',
        'b',
        'v',
        'g',
        'd',
        'e',
        'e',
        'gh',
        'z',
        'i',
        'y',
        'k',
        'l',
        'm',
        'n',
        'o',
        'p',
        'r',
        's',
        't',
        'u',
        'f',
        'h',
        'c',
        'ch',
        'sh',
        'sch',
        'y',
        'y',
        'y',
        'e',
        'yu',
        'ya'
    );
    return str_replace($rus, $lat, $str);
}

$i = 0;
unset($sheetData[1]);
$mew_company = array();
$all_company = array();
$arSelect = Array("ID", "IBLOCK_ID", "NAME");
$arFilter = Array("IBLOCK_ID" => 16);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while ($ob = $res->GetNextElement()) {
    $arProps = $ob->GetFields();
    $all_company[] = $arProps["ID"];
}


foreach ($sheetData as $region => $company) {

    if ($company["A"] != "") {
        $lower_name = mb_strtolower($company["C"]);
        $trans = translit($lower_name);


        $arSelect = Array("ID", "IBLOCK_ID", "NAME");
        $arFilter = Array("IBLOCK_ID" => 16, "PROPERTY_UNIQUE_CODE" => $company["L"], "PROPERTY_KPP" => $company["M"]);
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        if ($res->SelectedRowsCount() == "0") {
            $mew_company[] = $company;
        } else {
            while ($ob = $res->GetNextElement()) {
                $arProps = $ob->GetFields();

                if (($key = array_search($arProps["ID"], $all_company)) !== false) {

                    unset($all_company[$key]);
                }
                $arProp = array(
                    "NAME_BOSS" => $company["D"],
                    "MOBILE_NUMBER" => $company["E"],
                    "MOBILE_NUMBER2" => $company["F"],
                    "MOBILE_NUMBER3" => $company["G"],
                    "EMAIL_FIRST" => $company["H"],
                    "EMAIL_SECOND" => $company["I"],
                    "EMAIL_THIRD" => $company["J"],
                    "UNIQUE_CODE" => $company["L"],
                    "KPP" => $company["M"],
                    "GOROD" => $company["B"],
                );
                $el = new \CIBlockElement;
                if ($el->Update($arProps["ID"], ["NAME" => $company['C'], "CODE" => $trans])) {
                    echo "update ID: " . $arProps["ID"] . PHP_EOL;
                }

                CIBlockElement::SetPropertyValuesEx($arProps["ID"], 16, $arProp);

            }
        }
    }

}

if (isset($all_company)) {
    foreach ($all_company as $key_id) {

        $el = new \CIBlockElement;
        if ($el->Update($key_id, ["ACTIVE" => "N"])) {
            echo "activity removed: " . $key_id . PHP_EOL;


        }
    }
}


foreach ($mew_company as $key => $item) {
    $id_section = "";
    preg_match("/(\d+)\s+(.*)/",$item["A"],$arRegion);

    $arFilter = array('IBLOCK_ID' => 16, "UF_CODE_REGION" => $arRegion["1"] );
    $rsSections = CIBlockSection::GetList(array(), $arFilter);
    if ($arSection = $rsSections->Fetch()) {
        $id_section =   $arSection["ID"];
    }
}

if($id_section){
    $lower_name = mb_strtolower($item["C"]);
    $trans = translit($lower_name);

    $arFields = array(
        "ACTIVE" => "Y",
        "IBLOCK_ID" => 16,
        "IBLOCK_SECTION_ID" => $id_section,
        "NAME" => $item['C'],
        "CODE" => $trans,
        "PROPERTY_VALUES" => array(
            "NAME_BOSS" => $item["D"],
            "MOBILE_NUMBER" => $item["E"],
            "MOBILE_NUMBER2" => $item["F"],
            "MOBILE_NUMBER3" => $item["G"],
            "EMAIL_FIRST" => $item["H"],
            "EMAIL_SECOND" => $item["I"],
            "EMAIL_THIRD" => $item["J"],
            "UNIQUE_CODE" => $item["L"],
            "KPP" => $item["M"],
            "GOROD" => $item["B"],

        )
    );

    $oElement = new CIBlockElement();
    $idElement = $oElement->Add($arFields, false, false, true);
    if (!$idElement) {
        echo $oElement->LAST_ERROR;
    }else{
        echo "update ID: " . $idElement . PHP_EOL;

    }

}

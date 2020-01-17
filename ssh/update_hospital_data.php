<?php $_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__) . "/..");

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
echo '<pre>';
print_r($_SERVER["DOCUMENT_ROOT"]);
echo '</pre>';
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("iblock");
//
//
//global $DB;
//
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
//$csv = array();
//$file = fopen($_SERVER['DOCUMENT_ROOT'] . '/ssh/2020_hospital.csv', 'r');
//
//while (($result = fgetcsv($file, 1500, ';')) !== false) {
//    if ($result[0] !== 'Наименование субъекта Российской Федерации') {
//        $csv[$result[0]][] = $result;
//    }
//}
//
//fclose($file);
//echo '<pre>';
//print_r($csv);
//echo '</pre>';
//
//$arCode = array();
//foreach ($csv as $region => $hospitals) {
//
//    foreach ($hospitals as $key) {
//
//        $arSelect = Array("ID", "IBLOCK_ID", "NAME");
//        $arFilter = Array("IBLOCK_ID" => 9, "PROPERTY_MEDICAL_CODE" => $key[1]);
//        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
//        if ($res->SelectedRowsCount() == 0) {
//            $arCode[] = [$region => $key];
//        }
//        if ($ob = $res->GetNextElement()) {
//            $el = new CIBlockElement;
//            $arProps = $ob->GetFields();
//            $arProperties = $ob->GetProperties();
//            $arYEAR = array();
//
//
//            $arYEAR = $arProperties["YEAR"]['VALUE'];
//            $arYEAR[] = date("Y");
//
//            $arProp = array(
//                "SECOND_NAME" => $key[5],
//                "PERSON_NAME" => $key[6],
//                "MIDDLE_NAME" => $key[7],
//                "FULL_NAME" => $key[2],
//                "LOCATION" => $key[4],
//                "YEAR" => $arYEAR,
//                "MEDICAL_CODE" => $key[1]
//            );
//            $Array = array(
//                "NAME" => $key[3],
//                "PROPERTY_VALUES" => $arProp,
//            );
//
////            if ($res = $el->Update($arProps["ID"], $Array)) {
////                echo "update ID: " . $arProps["ID"] . PHP_EOL;
////            } else {
////                echo "Error: " . $el->LAST_ERROR;
////            }
//
//
//        }
//    }
//
//
//}
//
//
//foreach ($arCode as $key) {
//
//    foreach ($key as $region => $hospital) {
//
//
//        $id_section = "";
//        preg_match_all("/([0-9]+)\s+(.+)/", $region, $new_region);
//        $arFilter = Array('IBLOCK_ID' => 9, '%NAME' => $new_region[2][0]);
//        $db_list = CIBlockSection::GetList(Array(), $arFilter, false);
//        if ($ar_result = $db_list->GetNext()) {
//            $id_section = "";
//            $id_section = $ar_result['ID'];
//
//        }
//
//
//        if ($id_section > 0) {
//
//            $arFields = array(
//                "ACTIVE" => "Y",
//                "IBLOCK_ID" => 9,
//                "IBLOCK_SECTION_ID" => $ID,
//                "NAME" => $hospital[3],
//                "PROPERTY_VALUES" => array(
//                    "MEDICAL_CODE" => $hospital[1],
//                    "FULL_NAME" => $hospital[2],
//                    "LOCATION" => $hospital[4],
//                    "SECOND_NAME" => $hospital[5],
//                    "PERSON_NAME" => $hospital[6],
//                    "MIDDLE_NAME" => $hospital[7],
//                )
//            );
//
//            $oElement = new CIBlockElement();
////            $idElement = $oElement->Add($arFields, false, false, true);
////            echo "element new ID: " . $idElement . PHP_EOL;
//
//        }
//
//    }
//}

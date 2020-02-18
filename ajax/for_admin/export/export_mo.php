<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
require '/var/www/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
$arResult = array();
$arSelect = Array("ID","NAME", "IBLOCK_ID","IBLOCK_SECTION_ID", "NAME","PROPERTY_*");
$arFilter = Array("IBLOCK_ID"=>9 );
$i = 0;
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while ($ob = $res->GetNextElement()) {
    $arProps = $ob->GetProperties();
    $arFields = $ob->GetFields();
    $arSection = CIBlockSection::GetByID($arFields['IBLOCK_SECTION_ID'])->GetNext();
    $arResult[$i]["ID"] = $arFields["ID"];
    $arResult[$i]["NAME"] = $arFields["~NAME"];
    $arResult[$i]["MEDICAL_CODE"] = $arProps["MEDICAL_CODE"]["VALUE"];
    $arResult[$i]["FULL_NAME"] = $arProps["FULL_NAME"]["~VALUE"];
    $arResult[$i]["LOCATION"] = $arProps["LOCATION"]["VALUE"];
    $arResult[$i]["SECOND_NAME"] = $arProps["SECOND_NAME"]["VALUE"];
    $arResult[$i]["PERSON_NAME"] = $arProps["PERSON_NAME"]["VALUE"];
    $arResult[$i]["MIDDLE_NAME"] = $arProps["MIDDLE_NAME"]["VALUE"];
    foreach ($arProps["YEAR"]["VALUE"] as $key) {
        $arResult[$i]["YEAR"]  .= $key . ",";
    }
    $arResult[$i]["REGION"] = $arSection["NAME"];
    ++$i;
}

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$intCounter_for_name_row = 1;

$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

$sheet->setCellValue('A' . $intCounter_for_name_row, "Айди медицинской организации");
$sheet->setCellValue('B' . $intCounter_for_name_row, "Наименование организации");
$sheet->setCellValue('C' . $intCounter_for_name_row, "Код медицинской организации");
$sheet->setCellValue('D' . $intCounter_for_name_row, "Полное нименование организации");
$sheet->setCellValue('E' . $intCounter_for_name_row, "Адрес организации");
$sheet->setCellValue('F' . $intCounter_for_name_row, "Фамилия");
$sheet->setCellValue('G' . $intCounter_for_name_row, "Имя");
$sheet->setCellValue('H' . $intCounter_for_name_row, "Отчество");
$sheet->setCellValue('I' . $intCounter_for_name_row, "Годы");
$sheet->setCellValue('J' . $intCounter_for_name_row, "Субъект РФ");

$intCounter_for_item = 2;
foreach ($arResult as $item) {
    $sheet->setCellValue('A' . $intCounter_for_item, $item["ID"]);
    $sheet->setCellValue('B' . $intCounter_for_item, $item["NAME"]);
    $sheet->setCellValue('C' . $intCounter_for_item, $item["MEDICAL_CODE"]);
    $sheet->setCellValue('D' . $intCounter_for_item, $item["FULL_NAME"]);
    $sheet->setCellValue('E' . $intCounter_for_item, $item["LOCATION"]);
    $sheet->setCellValue('F' . $intCounter_for_item, $item["SECOND_NAME"]);
    $sheet->setCellValue('G' . $intCounter_for_item, $item["PERSON_NAME"]);
    $sheet->setCellValue('H' . $intCounter_for_item, $item["MIDDLE_NAME"]);
    $sheet->setCellValue('I' . $intCounter_for_item, $item["YEAR"]);
    $sheet->setCellValue('J' . $intCounter_for_item, $item["REGION"]);

    ++$intCounter_for_item;
}


$filename = time() . 'xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition:attachment;filename="' . $filename . '"');
header('Cache-control: max-age=0');

$writer = new Xlsx($spreadsheet);
$date = date("Y-m-d-h:i:s");
$writer->save('/var/www/upload/export/Выгрузка MO '.$date.'.xlsx');

$path = 'upload/export/Выгрузка MO '.$date.'.xlsx';


echo $path;
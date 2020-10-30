<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
require '/var/www/web/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use \PhpOffice\PhpSpreadsheet\Cell\DataType;
$arResult = array();
$arSelect = Array("ID","NAME", "ACTIVE","IBLOCK_ID","IBLOCK_SECTION_ID", "NAME","PROPERTY_*");
$arFilter = Array("IBLOCK_ID"=>16 );
$i = 0;
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);

while($ob = $res->GetNextElement()){
    $arProps = $ob->GetProperties();
    $arFields = $ob->GetFields();
    $arResult[$i]["ID"] = $arFields["ID"];
    $arResult[$i]["ACTIVE"] = $arFields["ACTIVE"];
    $arResult[$i]["NAME"] = $arFields["~NAME"];
    $arResult[$i]["EMAIL_FIRST"] = $arProps["EMAIL_FIRST"]["VALUE"];
    $arResult[$i]["EMAIL_SECOND"] = $arProps["EMAIL_SECOND"]["~VALUE"];
    $arResult[$i]["EMAIL_THIRD"] = $arProps["EMAIL_THIRD"]["VALUE"];
    $arResult[$i]["MOBILE_NUMBER"] = $arProps["MOBILE_NUMBER"]["VALUE"];
    $arResult[$i]["MOBILE_NUMBER2"] = $arProps["MOBILE_NUMBER2"]["VALUE"];
    $arResult[$i]["MOBILE_NUMBER3"] = $arProps["MOBILE_NUMBER3"]["VALUE"];
    $arResult[$i]["KPP"] = $arProps["KPP"]["VALUE"];
    $arResult[$i]["UNIQUE_CODE"] = $arProps["UNIQUE_CODE"]["VALUE"];
    $arResult[$i]["NAME_BOSS"] = $arProps["NAME_BOSS"]["VALUE"];
    $arResult[$i]["STREET"] = $arProps["STREET"]["VALUE"];
      if($arProps["ALL_AMOUNT_STAR"]["VALUE"] == ""){
          $arResult[$i]["ALL_AMOUNT_STAR"] = "0";
      }else{
          $arResult[$i]["ALL_AMOUNT_STAR"] = $arProps["ALL_AMOUNT_STAR"]["VALUE"];
      }

      if($arProps["AMOUNT_STAR"]["VALUE"] == ""){
          $arResult[$i]["AMOUNT_STAR"] = "0";
      }else{
          $arResult[$i]["AMOUNT_STAR"] = $arProps["AMOUNT_STAR"]["VALUE"];
      }


    $arFilter = Array('IBLOCK_ID'=>16, 'ID'=>$arFields["IBLOCK_SECTION_ID"] );
    $db_list = CIBlockSection::GetList(Array(), $arFilter, true);
    while($ar_result = $db_list->GetNext())
    {
        $arResult[$i]["NAME_REGION"] = $ar_result['NAME'];
    }
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
$spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getStyle('L')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);
$spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getStyle('M')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

$spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
$spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);



$sheet->setCellValue('A' . $intCounter_for_name_row, "Айди СМО");
$sheet->setCellValue('B' . $intCounter_for_name_row, "Навименование региона");
$sheet->setCellValue('C' . $intCounter_for_name_row, "Наименование компании");
$sheet->setCellValue('D' . $intCounter_for_name_row, "Имя управляющего");
$sheet->setCellValue('E' . $intCounter_for_name_row, "Адрес компании");
$sheet->setCellValue('F' . $intCounter_for_name_row, "Электронная почта 1");
$sheet->setCellValue('G' . $intCounter_for_name_row, "Электронная почта 2");
$sheet->setCellValue('H' . $intCounter_for_name_row, "Электронная почта 3");
$sheet->setCellValue('I' . $intCounter_for_name_row, "Горячая линия 1");
$sheet->setCellValue('J' . $intCounter_for_name_row, "Горячая линия 2");
$sheet->setCellValue('K' . $intCounter_for_name_row, "Горячая линия 3");
$sheet->setCellValue('L' . $intCounter_for_name_row, "ККП код");
$sheet->setCellValue('M' . $intCounter_for_name_row, "Уникальный код компании");
$sheet->setCellValue('N' . $intCounter_for_name_row, "Рейтинг по регоину");
$sheet->setCellValue('O' . $intCounter_for_name_row, "Рейтинг по стране");
$sheet->setCellValue('P' . $intCounter_for_name_row, "Активность");


$intCounter_for_item = 2;
foreach ($arResult as $item) {
    $sheet->setCellValue('A' . $intCounter_for_item, $item["ID"]);
    $sheet->setCellValue('B' . $intCounter_for_item, $item["NAME_REGION"]);
    $sheet->setCellValue('C' . $intCounter_for_item, $item["NAME"]);
    $sheet->setCellValue('D' . $intCounter_for_item, $item["NAME_BOSS"]);
    $sheet->setCellValue('E' . $intCounter_for_item, $item["STREET"]);
    $sheet->setCellValue('F' . $intCounter_for_item, $item["EMAIL_FIRST"]);
    $sheet->setCellValue('G' . $intCounter_for_item, $item["EMAIL_SECOND"]);
    $sheet->setCellValue('H' . $intCounter_for_item, $item["EMAIL_THIRD"]);
    $sheet->setCellValue('I' . $intCounter_for_item, $item["MOBILE_NUMBER"]);
    $sheet->setCellValue('J' . $intCounter_for_item, $item["MOBILE_NUMBER2"]);
    $sheet->setCellValue('K' . $intCounter_for_item, $item["MOBILE_NUMBER3"]);
    $sheet->setCellValue('L' . $intCounter_for_item, $item["KPP"]);
    $sheet->setCellValue('M' . $intCounter_for_item, $item["UNIQUE_CODE"]);
    $sheet->setCellValue('N' . $intCounter_for_item, $item["ALL_AMOUNT_STAR"]);
    $sheet->setCellValue('O' . $intCounter_for_item, $item["AMOUNT_STAR"]);
    $sheet->setCellValue('P' . $intCounter_for_item, $item["ACTIVE"]);



    ++$intCounter_for_item;
}


$filename = time() . 'xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition:attachment;filename="' . $filename . '"');
header('Cache-control: max-age=0');

$writer = new Xlsx($spreadsheet);
$date = date("Y-m-d-h:i:s");
$writer->save('/var/www/upload/export/Выгрузка CMO '.$date.'.xlsx');

$path = 'upload/export/Выгрузка CMO '.$date.'.xlsx';


echo $path;

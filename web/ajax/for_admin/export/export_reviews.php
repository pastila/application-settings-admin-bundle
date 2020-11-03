<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
require '/var/www/web/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;


if ($_POST["data_2"] != "") {


    $arResult = array();
    $i = 0;
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_CREATE", "PROPERTY_*");
    $arFilter = Array(
        "IBLOCK_ID" => 13,
        array(
            "LOGIC" => "AND",
            array(">DATE_CREATE" => $_POST["data_1"]),
            array("=<DATE_CREATE" => $_POST["data_2"]),
        ),
    );
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();


        $arResult[$i]['ID'] = $arFields["ID"];
        $arResult[$i]['DATE_CREATE'] = $arFields["DATE_CREATE"];
        $arResult[$i]['TEXT_MASSEGE'] = $arProps["TEXT_MASSEGE"]["VALUE"];
        $arResult[$i]['EVALUATION'] = $arProps["EVALUATION"]["VALUE"];

       $company=CIBlockElement::GetByID($arProps["NAME_COMPANY"]["VALUE"])->GetNextElement()->GetProperties();

       $arResult[$i]['UNIQUE_CODE'] = $company["UNIQUE_CODE"]["VALUE"];


        if ($ID_COMMENTS = $arProps["COMMENTS_TO_REWIEW"]["VALUE"]) { /// комментарии
            $order = Array("date_active_from" => "desc");
            $arSelectComments = Array("ID", "IBLOCK_ID", "NAME", "DATE_CREATE", "PROPERTY_*");
            $arFilterComments = Array("IBLOCK_ID" => 14, "ACTIVE" => "Y", "ID" => $ID_COMMENTS);
            $resComments = CIBlockElement::GetList($order, $arFilterComments, false, false, $arSelectComments);
            while ($obComments = $resComments->GetNextElement()) {
                $arFieldsComments = $obComments->GetFields();
                $arPropsComments = $obComments->GetProperties();


                $arResult[$i]['COMMENT_ID'] = $arFieldsComments["ID"];
                $arResult[$i]['COMMENT_DATE_CREATE'] = $arFieldsComments["DATE_CREATE"];
                $arResult[$i]['COMMENT_TEXT_MASSEGE'] = $arPropsComments["COMMENTS"]["VALUE"];


                if ($arPropsComments["CITED"]["VALUE"]) {  // цитаты к коментариям
                    $ID_Quote = $arPropsComments["CITED"]["VALUE"];
                    $arSelectQuote = Array("ID", "IBLOCK_ID", "NAME", "DATE_CREATE", "PROPERTY_*");
                    $arFilterQuote = Array("IBLOCK_ID" => 15, "ACTIVE" => "Y", "ID" => $ID_Quote);
                    $resQuote = CIBlockElement::GetList(false, $arFilterQuote, false, false,
                        $arSelectQuote);
                    while ($obQuote = $resQuote->GetNextElement()) {
                        $arFieldsQuote = $obQuote->GetFields();
                        $arPropsQuote = $obQuote->GetProperties();

                        $arResult[$i]['QUOTE_ID'] = $arFieldsQuote["ID"];
                        $arResult[$i]['QUOTE_DATE_CREATE'] = $arFieldsQuote["DATE_CREATE"];
                        $arResult[$i]['QUOTE_TEXT_MASSEGE'] = $arPropsQuote["CIATION"]["VALUE"];

                    }
                } else {

                    $arResult[$i]['QUOTE_ID'] = " ";
                    $arResult[$i]['QUOTE_DATE_CREATE'] = " ";
                    $arResult[$i]['QUOTE_TEXT_MASSEGE'] = " ";
                }
            }
        } else {

            $arResult[$i]['COMMENT_ID'] = " ";
            $arResult[$i]['COMMENT_DATE_CREATE'] = " ";
            $arResult[$i]['COMMENT_TEXT_MASSEGE'] = " ";
            $arResult[$i]['QUOTE_ID'] = " ";
            $arResult[$i]['QUOTE_DATE_CREATE'] = " ";
            $arResult[$i]['QUOTE_TEXT_MASSEGE'] = " ";
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
    $sheet->setCellValue('A' . $intCounter_for_name_row, "Айди отзыва");
    $sheet->setCellValue('B' . $intCounter_for_name_row, "Дата создания");
    $sheet->setCellValue('C' . $intCounter_for_name_row, "Содержимое отзыва");
    $sheet->setCellValue('D' . $intCounter_for_name_row, "Оценка");
    $sheet->setCellValue('E' . $intCounter_for_name_row, "Код смо");
    $sheet->setCellValue('F' . $intCounter_for_name_row, "Айди комментария");
    $sheet->setCellValue('G' . $intCounter_for_name_row, "Дата создания");
    $sheet->setCellValue('H' . $intCounter_for_name_row, "Содержимое комментария");
    $sheet->setCellValue('I' . $intCounter_for_name_row, "Айди цитаты");
    $sheet->setCellValue('J' . $intCounter_for_name_row, "Дата создания");
    $sheet->setCellValue('K' . $intCounter_for_name_row, "Содержимое цитаты");
    $intCounter_for_item = 2;
    foreach ($arResult as $item) {
        $sheet->setCellValue('A' . $intCounter_for_item, $item["ID"]);
        $sheet->setCellValue('B' . $intCounter_for_item, $item["DATE_CREATE"]);
        $sheet->setCellValue('D' . $intCounter_for_item, $item["EVALUATION"]);
        $sheet->setCellValue('C' . $intCounter_for_item, $item["TEXT_MASSEGE"]);
        $sheet->setCellValue('E' . $intCounter_for_item, $item["UNIQUE_CODE"]);
        $sheet->setCellValue('F' . $intCounter_for_item, $item["COMMENT_ID"]);
        $sheet->setCellValue('G' . $intCounter_for_item, $item["COMMENT_DATE_CREATE"]);
        $sheet->setCellValue('H' . $intCounter_for_item, $item["COMMENT_TEXT_MASSEGE"]);
        $sheet->setCellValue('I' . $intCounter_for_item, $item["QUOTE_ID"]);
        $sheet->setCellValue('J' . $intCounter_for_item, $item["QUOTE_DATE_CREATE"]);
        $sheet->setCellValue('K' . $intCounter_for_item, $item["QUOTE_TEXT_MASSEGE"]);
        ++$intCounter_for_item;
    }


    $filename = time() . 'xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition:attachment;filename="' . $filename . '"');
    header('Cache-control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('/var/www/web/upload/export/Выгрузка с ' . $_POST["data_1"] . ' по ' . $_POST["data_2"] . '.xlsx');

    $path = 'upload/export/Выгрузка с ' . $_POST["data_1"] . ' по ' . $_POST["data_2"] . '.xlsx';


    echo $path;
}

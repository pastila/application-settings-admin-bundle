<?php  require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
$csv = array();
$file = fopen($_SERVER['DOCUMENT_ROOT'] . '/ssh/spravochnic2.csv', 'r');

while (($result = fgetcsv($file, 1500, ';')) !== false)
{
    if (!empty($result[3]) and $result[0] !== 'класс') {
        $csv[$result[0]][$result[1]][$result[2]][] = $result[3];
    }
}

fclose($file);
//Скрипт нужно дописать если нужно использовать больше 1 раза.
die();
foreach ($csv as $block => $rubric) {
    $bs = new CIBlockSection;

    $arFields = Array(
        "ACTIVE" => "Y",
        "IBLOCK_ID" => 8,
        "NAME" => $block,
    );

    $ID = $bs->Add($arFields);
    if ($ID > 0) {
        foreach ($rubric as $rubricName => $rubrica) {
            $br = new CIBlockSection;

            $arFields = Array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => 8,
                "IBLOCK_SECTION_ID" => $ID,
                "NAME" => $rubricName,
            );

            $IDrubrica = $br->Add($arFields);

            if ($IDrubrica > 0) {
                foreach ($rubrica as $typename => $diagnoz) {
                    $bm = new CIBlockSection;

                    $arFields = Array(
                        "ACTIVE" => "Y",
                        "IBLOCK_ID" => 8,
                        "IBLOCK_SECTION_ID" => $IDrubrica,
                        "NAME" => $typename,
                    );

                    $ID2 = $bm->Add($arFields);
                    if ($ID2 > 0) {
                        foreach ($diagnoz as $arElem) {
                            $arFields = array(
                                "ACTIVE" => "Y",
                                "IBLOCK_ID" => 8,
                                "IBLOCK_SECTION_ID" => $ID2,
                                "NAME" => $arElem,
                            );
                            $oElement = new CIBlockElement();
                            $idElement = $oElement->Add($arFields, false, false, true);
                        }
                    }
                }
            }
        }
    }
}

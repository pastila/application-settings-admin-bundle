<?php  require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
$csv = array();
$file = fopen($_SERVER['DOCUMENT_ROOT'] . '/ssh/hospital_spravochnick.csv', 'r');

while (($result = fgetcsv($file, 1500, ';')) !== false)
{
    if ($result[0] !== 'Наименование субъекта Российской Федерации') {
        $csv[$result[0]][] = $result;
    }
}

fclose($file);

foreach ($csv as $region => $hospitals) {
    die();
    $bs = new CIBlockSection;

    $arFields = array(
        "ACTIVE" => "Y",
        "IBLOCK_ID" => 9,
        "NAME" => $region,
    );

    $ID = $bs->Add($arFields);
    if ($ID > 0) {
        foreach ($hospitals as $hospital) {
            $arFields = array(
                "ACTIVE" => "Y",
                "IBLOCK_ID" => 9,
                "IBLOCK_SECTION_ID" => $ID,
                "NAME" => $hospital[3],
                "PROPERTY_VALUES" => array(
                    "MEDICAL_CODE" => $hospital[1],
                    "FULL_NAME" => $hospital[2],
                    "LOCATION" => $hospital[4]
                )
            );
            $oElement = new CIBlockElement();
            $idElement = $oElement->Add($arFields, false, false, true);
        }
    }
}
<?php  require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
$csv = array();
$file = fopen($_SERVER['DOCUMENT_ROOT'] . '/ssh/hospital_full_infa.csv', 'r');

while (($result = fgetcsv($file, 1500, ',')) !== false)
{
    if ($result[0] !== 'Наименование субъекта Российской Федерации') {
        $csv[$result[0]][] = $result;
    }
}

fclose($file);
die();
foreach ($csv as $region => $hospitals) {

    $bs = new CIBlockSection;
    preg_match_all("/([0-9]+)\s+(.+)/", $region, $new_region);
    $lower_name =   mb_strtolower($new_region[2][0]);
    $arFields = array(
        "ACTIVE" => "Y",
        "IBLOCK_ID" => 9,
        "NAME" => $region,
        "UF_CODE_REGION"=> (int)$new_region[1][0],
    );

    $ID = $bs->Add($arFields);
    echo '<pre>';
    print_r($ID);
    echo '</pre>';
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
                    "LOCATION" => $hospital[4],
                    "SECOND_NAME" => $hospital[5],
                    "PERSON_NAME" => $hospital[6],
                    "MIDDLE_NAME" => $hospital[7],
                )
            );
            $oElement = new CIBlockElement();
            $idElement = $oElement->Add($arFields, false, false, true);
            echo '<pre>';
            echo "element";
            print_r($idElement);
            echo '</pre>';
        }
    }
}
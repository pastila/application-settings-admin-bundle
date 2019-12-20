<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");


$csv = array();
$file = fopen($_SERVER['DOCUMENT_ROOT'] . '/ssh/234.csv', 'r');

while (($result = fgetcsv($file, 1500, ';')) !== false) {
    if ($result[0] !== 'субъект РФ') {
        $csv[$result[0]][] = $result;
    }
}

fclose($file);
CModule::IncludeModule("iblock");
$img_in_folder = scandir("/var/www/images/logo_company/", SCANDIR_SORT_DESCENDING);
$fid = Array();
$arFile = Array();
$array_ready_img_id = Array();
foreach ($img_in_folder as $item) {

    $arFile[] = CFile::MakeFileArray("/var/www/images/logo_company/" . $item);

}

foreach ($arFile as $item_file) {
    if (!isset($item_file)) {
        continue;
    }
    preg_match("/(\/var\/www\/images\/logo\_company\/)([0-9]+)/", $item_file["tmp_name"], $result_search_img);

    $fid[] = [$result_search_img[2] => CFile::SaveFile($item_file, "logo_company")];


}
function translit($str) {
    $rus = array('А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я', 'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я');
    $lat = array('A', 'B', 'V', 'G', 'D', 'E', 'E', 'Gh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F', 'H', 'C', 'Ch', 'Sh', 'Sch', 'Y', 'Y', 'Y', 'E', 'Yu', 'Ya', 'a', 'b', 'v', 'g', 'd', 'e', 'e', 'gh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh', 'sch', 'y', 'y', 'y', 'e', 'yu', 'ya');
    return str_replace($rus, $lat, $str);
}
$i = 0;
foreach ($csv as $region => $hospitals) {

    $bs = new CIBlockSection;
    preg_match_all("/([0-9]+)\s+(.+)/", $region, $new_region);

  $lower_name =   mb_strtolower($new_region[2][0]);


    $trans =  translit($lower_name);

    $arFields = array(
        "ACTIVE" => "Y",
        "IBLOCK_ID" => 16,
        "NAME" => $new_region[2][0],
        "CODE"=>$trans,
        "UF_CODE_REGION" => (int)$new_region[1][0],
    );


    $ID = $bs->Add($arFields);
echo '<pre>';
print_r($ID);
echo '</pre>';
if($ID) {

    foreach ($hospitals as $hospital) {
        ++$i;
        $id_img = "";

        foreach ($fid as $item) {
            foreach ($item as $key => $id) {
                if ($key == $hospital[10]) {
                    $id_img = $id;

                }
            }
        }
        $lower_element_name =   mb_strtolower( $hospital[2]);

        $element_name = translit($lower_element_name);
        $element_name.= $i;
     $new_code =  str_ireplace('"',"",$element_name);
     $new_code2 =  str_ireplace(" ","",$new_code);
     $new_name =  str_ireplace(" ","",$hospital[2]);

        $arFields = array(
            "ACTIVE" => "Y",
            "IBLOCK_ID" => 16,
            "IBLOCK_SECTION_ID" => $ID,
            "NAME" => $new_name,
            "CODE" => $new_code2,
            "PROPERTY_VALUES" => array(
                "NAME_BOSS" => $hospital[3],
                "MOBILE_NUMBER" => $hospital[4],
                "MOBILE_NUMBER2" => $hospital[5],
                "MOBILE_NUMBER3" => $hospital[6],
                "EMAIL_FIRST" => $hospital[7],
                "EMAIL_SECOND" => $hospital[8],
                "EMAIL_THIRD" => $hospital[9],
                "LOGO_SMO" => $hospital[10],
                "UNIQUE_CODE" => $hospital[11],
                "KPP" => $hospital[12],
                "GOROD" => $hospital[1],
                "LOGO_IMG" => $id_img,

            )
        );
        echo '<pre>';
        print_r($arFields);
        echo '</pre>';
        $oElement = new CIBlockElement();
        $idElement = $oElement->Add($arFields, false, false, true);
        if (!$idElement) {
            echo $oElement->LAST_ERROR;
        }

    }
}
}


//$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");
//$arFilter = Array("IBLOCK_ID"=>16, "NAME"=>$hospital[1]);
//$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
//while($ob = $res->GetNextElement()){
//    $arFields = $ob->GetFields();
//
//    $upd = CIBlockElement::SetPropertyValuesEx($arFields["ID"], 16, array('NAME_BOSS' => $hospital[2]));
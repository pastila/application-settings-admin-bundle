<?php
/**
 * Created by PhpStorm.
 * User: v.volkov
 * Date: 04.11.2019
 * Time: 11:43
 */
use Bitrix\Main\Application;
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
$arSelect = Array("ID", "IBLOCK_ID", "NAME","PROPERTY_KPP");
$arFilter = Array("IBLOCK_ID"=> 16, "SECTION_ID" => $_POST['region_id']);
$res = CIBlockElement::GetList(Array("NAME"=>"ASC"), $arFilter, false, false, $arSelect);
?>

<?php
while($ob = $res->GetNextElement()){
    $arFields = $ob->GetFields();

    echo '<li value="' . $arFields['ID'] . '" data-kpp="' . $arFields['PROPERTY_KPP_VALUE'] . '" class="custom-serach__items_item hospital "  >'.$arFields["NAME"].'</li>';
}

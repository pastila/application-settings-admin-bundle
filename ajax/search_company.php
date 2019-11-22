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
$arSelect = Array("ID", "IBLOCK_ID", "NAME");
$arFilter = Array("IBLOCK_ID"=> 16, "SECTION_ID" => $_POST['id']);
$res = CIBlockElement::GetList(false, $arFilter, false, false, $arSelect);
?>
<option>Выберите страховую компанию</option>
<?php
while($ob = $res->GetNextElement()){
    $arFields = $ob->GetFields();
    echo '<option value="' . $arFields['ID'] .'" class="primer_company">' . $arFields["NAME"] . '</option>';
}

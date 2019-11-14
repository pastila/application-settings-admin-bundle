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
$arFilter = Array("IBLOCK_ID"=> 16, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y" ,"%NAME"=>$_POST["name"]);
$res = CIBlockElement::GetList(false, $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement()){
    $arFields = $ob->GetFields();
    echo " <div class='primer_company'>".$arFields["NAME"]."</div>";
}

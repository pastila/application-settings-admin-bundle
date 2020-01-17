<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/otpravlennyye/otpravlennyye.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/otpravlennyye/otpravlennyye.min.js");
CModule::IncludeModule("iblock");
?>

<?$APPLICATION->SetTitle("Политика по обработке персональных данных");?>
<?php $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT");
$arFilter = Array("IBLOCK_ID"=>23, "CODE"=>"about");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
if($ob = $res->GetNextElement()){
    $arProps = $ob->GetFields();

}

?>
<?$APPLICATION->SetTitle("О нас");?>

    <!-- Breadcrumbs -->
    <ul class="breadcrumbs">
        <? if ($detect->isTablet() || $detect->isMobile()) { ?>
            <li><a href="/" class=""><?= $arProps["NAME"]; ?></a></li>
        <? } else { ?>
            <li><a href="/">Главная</a></li>
            <li><?= $arProps["NAME"]; ?></li>
        <? } ?>

    </ul>

    <!-- Pages Title -->
<?= $arProps["PREVIEW_TEXT"]; ?>
    <style>
        p{
            margin-bottom: 1rem;
        }
    </style>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
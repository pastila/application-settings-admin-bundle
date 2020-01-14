<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/otpravlennyye/otpravlennyye.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/otpravlennyye/otpravlennyye.min.js");
CModule::IncludeModule("iblock");

?>

<?$APPLICATION->SetTitle("Пользовательское соглашение");?>
<?php $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT");
$arFilter = Array("IBLOCK_ID"=>23, "CODE"=>"insurance-companies");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
if($ob = $res->GetNextElement()){
    $arProps = $ob->GetFields();

} ?>
<!-- Breadcrumbs -->
<ul class="breadcrumbs">
    <li><a href="/">Главная</a></li>
    <li><?= $arProps["NAME"]; ?></li>
</ul>

<!-- Pages Title -->
<?= $arProps["PREVIEW_TEXT"]; ?>

<style>
    .decoration-link{
        text-decoration: underline;
    }
    p{
        margin-bottom: 1rem;
    }
    .list_block li{
        padding-left: 1.5rem;
        margin-bottom: .5rem;
    }
</style>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
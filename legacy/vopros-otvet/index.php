<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/otpravlennyye/otpravlennyye.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/otpravlennyye/otpravlennyye.min.js");
CModule::IncludeModule("iblock");
?>


<?php $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT");
$arFilter = Array("IBLOCK_ID"=>23, "CODE"=>"vopros-otvet");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
if($ob = $res->GetNextElement()){
    $arProps = $ob->GetFields();

}

?>
<?$APPLICATION->SetTitle("Вопрос ответ");?>

    <!-- Breadcrumbs -->
    <ul class="breadcrumbs">
        <? if ($detect->isTablet() || $detect->isMobile()) { ?>
            <li><a href="/" class="active-breadcrumbs"><?= $arProps["NAME"]; ?></a></li>
        <? } else { ?>
            <li><a href="/">Главная</a></li>
            <li class="active-breadcrumbs"><?= $arProps["NAME"]; ?></li>
        <? } ?>

    </ul>

    <!-- Pages Title -->
    <div class="white_block">
        <?= $arProps["PREVIEW_TEXT"]; ?>
    </div>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
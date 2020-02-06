<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/otpravlennyye/otpravlennyye.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/otpravlennyye/otpravlennyye.min.js");
CModule::IncludeModule("iblock");
?>


<!-- Breadcrumbs -->
<ul class="breadcrumbs">
    <? if ($detect->isTablet() || $detect->isMobile()) { ?>
    <li><a href="/" class="">Отправленные обращения</a></li>
    <? } else { ?>
    <li><a href="/">Главная</a></li>
    <li>Отправленные обращения</li>
    <? } ?>

</ul>

<!-- Pages Title -->
<h2 class="page-title">Отправленные обращения</h2>


<?
global $USER;
$ID_USER = $USER->GetID();
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM");
$arFilter = Array("IBLOCK_ID" => 11, "UF_USER_ID" => $ID_USER);
$section = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect, false);  // получили секцию по айди юзера
if ($Section = $section->GetNext()) {
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "CREATED_DATE", "PROPERTY_SEND_MESSAGE");
    $arFilter = Array("IBLOCK_ID" => 11, "SECTION_ID" => $Section["ID"], "PROPERTY_SEND_REVIEW_VALUE"=> 1);
    $Element = CIBlockElement::GetList(Array("created" => "desc"), $arFilter, false, false, $arSelect); //получили обращения юзера
    while ($obElement = $Element->GetNextElement()) {
        $arFields = $obElement->GetFields();
        $newDate = FormatDate("d.m.Y", MakeTimeStamp($arFields["CREATED_DATE"]));
        ?>
<!-- Обращения item -->
<div class="otpravlennyye">
    <div class="white_block">
        <div class="otpravlennyye__item">
            <h3 class="otpravlennyye__item_title">
                <?= $arFields["NAME"] . ' № ' . $arFields["ID"]?>
            </h3>

            <h4 class="success">Направлено в страховую компанию</h4>

            <div class="otpravlennyye__item_data">
                дата: <?= $arFields['PROPERTY_SEND_MESSAGE_VALUE'] ?>
            </div>

            <p class="otpravlennyye__item_text">
                В соответствии с действующим законодательством
                в течение 30 дней вам должны предоставить ответ на обращение либо проинформировать о
                продлении срока рассмотрения обращения, если для решения поставленных вопросов нужно
                проведение экспертизы
            </p>
        </div>
    </div>
</div>
<?
    }
} ?>




<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
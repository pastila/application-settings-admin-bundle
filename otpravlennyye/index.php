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
    <li><a href="/" class="active-breadcrumbs">Отправленные обращения</a></li>
    <? } else { ?>
    <li><a href="/">Главная</a></li>
    <li class="active-breadcrumbs">Отправленные обращения</li>
    <? } ?>

</ul>

<!-- Pages Title -->
<h1 class="page-title">Отправленные обращения</h1>


<?
global $USER;
$ID_USER = $USER->GetID();
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM");
$arFilter = Array("IBLOCK_ID" => 11, "UF_USER_ID" => $ID_USER);
$section = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect, false);  // получили секцию по айди юзера
if ($Section = $section->GetNext()) {
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "CREATED_DATE", "PREVIEW_PICTURE", "PROPERTY_SEND_MESSAGE",
        "PROPERTY_PDF", "PROPERTY_IMG_2", "PROPERTY_IMG_3", "PROPERTY_IMG_4", "PROPERTY_IMG_5");
    $arFilter = Array("IBLOCK_ID" => 11, "SECTION_ID" => $Section["ID"], "PROPERTY_SEND_REVIEW_VALUE"=> 1);
    $Element = CIBlockElement::GetList(Array("created" => "desc"), $arFilter, false, false, $arSelect); //получили обращения юзера
    while ($obElement = $Element->GetNextElement()) {
        $arFields = $obElement->GetFields();
        $PDF = CFile::GetPath($arFields['PROPERTY_PDF_VALUE']);
        $img_first = CFile::GetPath($arFields['PREVIEW_PICTURE']);
        $img_second = CFile::GetPath($arFields['PROPERTY_IMG_2_VALUE']);
        $img_third = CFile::GetPath($arFields['PROPERTY_IMG_3_VALUE']);
        $img_fourth = CFile::GetPath($arFields['PROPERTY_IMG_4_VALUE']);
        $img_fifth = CFile::GetPath($arFields['PROPERTY_IMG_5_VALUE']);
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
    <div class="obrashcheniya__content_sidebar">
        <div class="otpravlennyye__item_title">
            Прикрепленные файлы
        </div>
        <!-- Item Sidebar -->
        <div class="block__items_flex">
            <?php if ($img_first) { ?>
                <div class="obrashcheniya__content_sidebar_blocks">
                    <div class="obrashcheniya__content_sidebar_blocks_img">
                        <img src="<?=$img_first?>">
                    </div>
                    <div class="obrashcheniya__content_sidebar_blocks_text">
                        <div class="obrashcheniya__content_sidebar_blocks_text_title">Загруженный документ</div>
                        <a href="<?=$img_first?>" class="obrashcheniya__content_sidebar_blocks_text_link">скачать</a>
                    </div>
                </div>
            <?php } ?>
            <?php if ($img_second) { ?>
                <div class="obrashcheniya__content_sidebar_blocks">
                    <div class="obrashcheniya__content_sidebar_blocks_img">
                        <img src="<?=$img_second?>">
                    </div>
                    <div class="obrashcheniya__content_sidebar_blocks_text">
                        <div class="obrashcheniya__content_sidebar_blocks_text_title">Загруженный документ</div>
                        <a href="<?=$img_second?>" class="obrashcheniya__content_sidebar_blocks_text_link">скачать</a>
                    </div>
                </div>
            <?php } ?>
            <?php if ($img_third) { ?>
                <div class="obrashcheniya__content_sidebar_blocks">
                    <div class="obrashcheniya__content_sidebar_blocks_img">
                        <img src="<?=$img_third?>">
                    </div>
                    <div class="obrashcheniya__content_sidebar_blocks_text">
                        <div class="obrashcheniya__content_sidebar_blocks_text_title">Загруженный документ</div>
                        <a href="<?=$img_third?>" class="obrashcheniya__content_sidebar_blocks_text_link">скачать</a>
                    </div>
                </div>
            <?php } ?>
            <?php if ($img_fourth) { ?>
                <div class="obrashcheniya__content_sidebar_blocks">
                    <div class="obrashcheniya__content_sidebar_blocks_img">
                        <img src="<?=$img_third?>">
                    </div>
                    <div class="obrashcheniya__content_sidebar_blocks_text">
                        <div class="obrashcheniya__content_sidebar_blocks_text_title">Загруженный документ</div>
                        <a href="<?=$img_third?>" class="obrashcheniya__content_sidebar_blocks_text_link">скачать</a>
                    </div>
                </div>
            <?php } ?>
            <?php if ($img_fifth) { ?>
                <div class="obrashcheniya__content_sidebar_blocks">
                    <div class="obrashcheniya__content_sidebar_blocks_img">
                        <img src="<?=$img_fifth?>">
                    </div>
                    <div class="obrashcheniya__content_sidebar_blocks_text">
                        <div class="obrashcheniya__content_sidebar_blocks_text_title">Загруженный документ</div>
                        <a href="<?=$img_fifth?>" class="obrashcheniya__content_sidebar_blocks_text_link">скачать</a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="obrashcheniya__content_sidebar_blocks">
            <div class="obrashcheniya__content_sidebar_blocks_img">
                <img src="/local/templates/kdteam/images/svg/pdf_icon.svg" alt="">
            </div>
            <div class="obrashcheniya__content_sidebar_blocks_text">
                <div class="obrashcheniya__content_sidebar_blocks_text_title">
                    Заявление на возврат
                </div>
                <a target="_blank" class="obrashcheniya__content_sidebar_blocks_text_link " href="<?=$PDF?>">
                    просмотреть
                </a>
            </div>
        </div>
    </div>
</div>
<?
    }
} ?>




<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
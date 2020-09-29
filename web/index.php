<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("");

use Bitrix\Main\Page\Asset;
$asset = Asset::getInstance();
//Home Page Style Mobile
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/home/home.min.css");
$APPLICATION->SetPageProperty("title", "OMS");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
CModule::IncludeModule("iblock");
?><?

$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "DETAIL_TEXT", "PREVIEW_TEXT", "PROPERTY_*");
$arFilter = Array("IBLOCK_ID"=>18,"ID" => 37549);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement()){
    $arFields = $ob->GetFields();
    $arProps = $ob->GetProperties();

    ?> <!-- Slider -->
    <div class="slider">
        <div class="slider__l">
            <div class="slider__l_item">
                <h1 class="home-title"><?=$arFields["NAME"]?></h1>
                <div class="home-title-sub">
                    <span class="home-title-sub_line">—</span><?=$arFields["PREVIEW_TEXT"]?>
                </div>
            </div>
            <div class="slider__l_descr">
                <?=$arFields["DETAIL_TEXT"]?>
            </div>
        </div>
    </div>
<?}?> <!-- Block buttons links to pages -->

<?php $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_TITLE_1", "PROPERTY_TITLE_2", "PROPERTY_TITLE_3", "PROPERTY_TEXT_1", "PROPERTY_TEXT_2", "PROPERTY_TEXT_3");
$arFilter = Array("IBLOCK_CODE"=>"block_on_main_page","CODE"=>"3-blocks" );
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement()){
    $arProps_block = $ob->GetFields();
   $title_text_1 = $arProps_block["PROPERTY_TITLE_1_VALUE"];
   $title_text_2 = $arProps_block["PROPERTY_TITLE_2_VALUE"];
   $title_text_3 = $arProps_block["PROPERTY_TITLE_3_VALUE"];
   $text_1 = $arProps_block["~PROPERTY_TEXT_1_VALUE"];
   $text_2 = $arProps_block["~PROPERTY_TEXT_2_VALUE"];
   $text_3 = $arProps_block["~PROPERTY_TEXT_3_VALUE"];
} ?>
<div class="buttons__items">
    <!-- Item -->
    <div class="buttons__items_item">
        <div class="buttons__items_item_image-block">
            <a class="buttons__items_item_img" href="/forma-obrashenija/"> <img src="/local/templates/kdteam/images/pages/home/Illustration2.svg" alt=""> </a>
        </div>
        <div class="buttons__items_item_title-block">
            <a href="/forma-obrashenija/">
                <h2 class="buttons__items_item_title">
                   <?php echo $title_text_1; ?> </h2>
            </a>
            <p>
                <?php echo $text_1; ?>
            </p>
            <a class="buttons__items_item_link" href="/forma-obrashenija/">
                <p>
                    Перейти в раздел <img src="/local/templates/kdteam/images/pages/home/arrow-right.svg">
                </p>
            </a>
        </div>
    </div>
    <!-- Item -->
    <div class="buttons__items_item">
        <div class="buttons__items_item_image-block">
            <a class="buttons__items_item_img" href="/reviews"> <img src="/local/templates/kdteam/images/pages/home/Illustration3.svg" alt=""> </a>
        </div>
        <div class="buttons__items_item_title-block">
            <a href="/reviews">
                <h2 class="buttons__items_item_title">
                    <?php echo $title_text_2; ?> </h2>
            </a>
            <p>
                <?php echo $text_2; ?>
            </p>
            <a class="buttons__items_item_link" href="/reviews">
                <p>
                    Перейти в раздел <img src="/local/templates/kdteam/images/pages/home/arrow-right.svg">
                </p>
            </a>
        </div>
    </div>
    <!-- Item -->
    <div class="buttons__items_item">
        <div class="buttons__items_item_image-block">
            <a class="buttons__items_item_img" href="/news/"> <img src="/local/templates/kdteam/images/pages/home/Illustration.svg" alt=""> </a>
        </div>
        <div class="buttons__items_item_title-block">
            <a href="/news/">
                <h2 class="buttons__items_item_title">
                    <?php echo $title_text_3; ?> </h2>
            </a>
            <p>
                <?php echo $text_3; ?>            </p>
            <a class="buttons__items_item_link" href="/news/">
                <p>
                    Перейти в раздел <img src="/local/templates/kdteam/images/pages/home/arrow-right.svg">
                </p>
            </a>
        </div>
    </div>
</div>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");

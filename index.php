<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;
$asset = Asset::getInstance();
//Home Page Style Mobile
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/home/home.min.css");
$APPLICATION->SetPageProperty("title", "OMS");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
CModule::IncludeModule("iblock");
?>



<?

   $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "DETAIL_TEXT", "PREVIEW_TEXT", "PROPERTY_*");
   $arFilter = Array("IBLOCK_ID"=>18,"ID" => 37549);
   $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
   while($ob = $res->GetNextElement()){
       $arFields = $ob->GetFields();
       $arProps = $ob->GetProperties();

?>
<!-- Slider -->
<div class="slider">
    <div class="slider__l">
        <h1 class="home-title"><?=$arFields["NAME"]?></h1>
        <div class="home-title-sub">
            <?=$arFields["PREVIEW_TEXT"]?>
        </div>

        <div class="slider__l_descr">
            <?=$arFields["DETAIL_TEXT"]?>
        </div>

        <a href="/news/" class="slider__details mainBtn">Подробнее</a>
    </div>
</div>
<?}?>
<!-- Block buttons links to pages -->
<div class="buttons__items">
    <!-- Item -->
    <a href="/forma-obrashenija/" class="buttons__items_item">
        <div class="buttons__items_item_image-block">
            <img class="buttons__items_item_img"
                src="./local/templates/kdteam/images/jpg/home/buttons_menu/item1/img.jpg" alt="">
        </div>

        <div class="buttons__items_item_title-block">
            <h3 class="buttons__items_item_title">
                Страховой случай в ОМС
            </h3>
        </div>
    </a>

    <!-- Item -->
    <a href="/feedback/" class="buttons__items_item">
        <div class="buttons__items_item_image-block">
            <img class="buttons__items_item_img"
                src="./local/templates/kdteam/images/jpg/home/buttons_menu/item2/img.jpg" alt="">
        </div>

        <div class="buttons__items_item_title-block">
            <h3 class="buttons__items_item_title">
                Отзывы о представителях
            </h3>
        </div>
    </a>

    <!-- Item -->
    <a href="/news/" class="buttons__items_item">
        <div class="buttons__items_item_image-block">
            <img class="buttons__items_item_img"
                src="./local/templates/kdteam/images/jpg/home/buttons_menu/item3/img.jpg" alt="">
        </div>

        <div class="buttons__items_item_title-block">
            <h3 class="buttons__items_item_title">
                Читать про ОМС
            </h3>
        </div>
    </a>
</div>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
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
<?}?>

<!-- Block buttons links to pages -->
<div class="buttons__items">
    <!-- Item -->
    <div class="buttons__items_item">
        <div class="buttons__items_item_image-block">
            <a class="buttons__items_item_img" href="/forma-obrashenija/">
                <img src="<?= SITE_TEMPLATE_PATH ?>/images/pages/home/Illustration2.svg"
                    alt="">
            </a>
        </div>

        <div class="buttons__items_item_title-block">
            <a href="/forma-obrashenija/">
                <h2 class="buttons__items_item_title">
                    Не платите
                </h2>
            </a>

            <p>за то, что уже оплачено. Нужно всего несколько минут, чтобы соотнести свое лечение с программой ОМС и
                запустить процесс возврата денег, если оплата была незаконной.</p>
            <a class="buttons__items_item_link" href="/forma-obrashenija/"><p>Перейти в раздел <img src="<?= SITE_TEMPLATE_PATH ?>/images/pages/home/arrow-right.svg"></p></a>
        </div>
    </div>

    <!-- Item -->
    <div class="buttons__items_item">
        <div class="buttons__items_item_image-block">
            <a class="buttons__items_item_img" href="/feedback/">
                <img src="<?= SITE_TEMPLATE_PATH ?>/images/pages/home/Illustration3.svg"
                    alt="">
            </a>
        </div>

        <div class="buttons__items_item_title-block">
            <a href="/feedback/">
                <h2 class="buttons__items_item_title">
                    Выбирайте лучшую
                </h2>
            </a>
            <p>страховую компанию и участвуйте в создании народного рейтинга компаний. Ваши отзывы помогают выявить
                компании, которым действительно дорого здоровье своих застрахованных.</p>
            <a class="buttons__items_item_link" href="/feedback/"><p>Перейти в раздел <img src="<?= SITE_TEMPLATE_PATH ?>/images/pages/home/arrow-right.svg"></p></a>
        </div>
    </div>

    <!-- Item -->
    <div class="buttons__items_item">
        <div class="buttons__items_item_image-block">
            <a class="buttons__items_item_img" href="/news/">
                <img src="<?= SITE_TEMPLATE_PATH ?>/images/pages/home/Illustration.svg"
                    alt="">
            </a>
        </div>

        <div class="buttons__items_item_title-block">
            <a href="/news/">
                <h2 class="buttons__items_item_title">
                    Узнавайте больше
                </h2>
            </a>

            <p>о возможностях полиса ОМС чтобы получать максимум пользы для себя и своих близких.</p>
            <a class="buttons__items_item_link" href="/news/"><p>Перейти в раздел <img src="<?= SITE_TEMPLATE_PATH ?>/images/pages/home/arrow-right.svg"></p></a>
        </div>
    </div>
</div>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
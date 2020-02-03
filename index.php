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
    </div>
</div>
<?}?>

<!-- Block buttons links to pages -->
<div class="buttons__items">
    <!-- Item -->
    <a href="/forma-obrashenija/" class="buttons__items_item">
        <div class="buttons__items_item_image-block">
            <img class="buttons__items_item_img" src="./local/templates/kdteam/images/pages/home/Illustration2.svg" alt="">
        </div>

        <div class="buttons__items_item_title-block">
            <h2 class="buttons__items_item_title">
                Не платите
            </h2>
            <p>за то, что уже оплачено. Нужно всего несколько минут, чтобы соотнести свое лечение с программой ОМС и запустить процесс возврата денег, если оплата была незаконной.</p>
        </div>
    </a>

    <!-- Item -->
    <a href="/feedback/" class="buttons__items_item">
        <div class="buttons__items_item_image-block">
            <img class="buttons__items_item_img" src="./local/templates/kdteam/images/pages/home/Illustration3.svg" alt="">
        </div>

        <div class="buttons__items_item_title-block">
            <h2 class="buttons__items_item_title">
            Выбирайте лучшую
            </h2>
            <p>страховую компанию и участвуйте в создании народного рейтинга компаний. Ваши отзывы помогают выявить компании, которым действительно дорого здоровье своих застрахованных.</p>
        </div>
    </a>

    <!-- Item -->
    <a href="/news/" class="buttons__items_item">
        <div class="buttons__items_item_image-block">
            <img class="buttons__items_item_img" src="./local/templates/kdteam/images/pages/home/Illustration.svg" alt="">
        </div>

        <div class="buttons__items_item_title-block">
            <h2 class="buttons__items_item_title">
            Узнавайте больше
            </h2>
            <p>о возможностях полиса ОМС чтобы получать максимум пользы для себя и своих близких.</p>
        </div>
    </a>
</div>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
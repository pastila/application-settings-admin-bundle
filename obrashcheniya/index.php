<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;
$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/obrashcheniya/main.min.css");
//$asset->addJs("/gulp/pages/obrashcheniya/main.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/obrashcheniya/main.min.js");
?>
<?php
global $USER;

if ($USER->IsAuthorized()) { ?>
    <!-- Breadcrumbs -->
    <ul class="breadcrumbs">
        <? if ($detect->isTablet() || $detect->isMobile()) { ?>
            <li><a href="/"  class="active-breadcrumbs">Обращения</a></li>
        <? } else { ?>
            <li><a href="/">Главная</a></li>
            <li class="active-breadcrumbs">Обращения</li>
        <? } ?>

    </ul>

    <?php
    if (CModule::IncludeModule("iblock")) {
        $arFilter = array("IBLOCK_ID" => 11, "UF_USER_ID" => $USER->GetID());
        $arSelect = array("ID");
        $rsSections = CIBlockSection::GetList(array(), $arFilter, false, $arSelect, false);
    }
    if ($arSection = $rsSections->GetNext()) {
        global $arrFilter;
        $arrFilter = array("!PROPERTY_SEND_REVIEW" => 3);

        $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "otpravlennyye",
            array(
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "AJAX_MODE" => "Y",
                "IBLOCK_TYPE" => "",
                "IBLOCK_ID" => "11",
                "FILTER_NAME" => "arrFilter",
                "NEWS_COUNT" => "100",
                "SORT_BY1" => "SORT",
                "SORT_ORDER1" => "ASC",
                "CHECK_DATES" => "Y",
                "SET_TITLE" => "N",
                "SET_BROWSER_TITLE" => "N",
                "SET_META_KEYWORDS" => "N",
                "SET_META_DESCRIPTION" => "N",
                "SET_LAST_MODIFIED" => "N",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "PARENT_SECTION" => $arSection["ID"],
                "PARENT_SECTION_CODE" => "",
                "INCLUDE_SUBSECTIONS" => "Y",
                "CACHE_TYPE" => "N",
                "CACHE_TIME" => "3600",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "N",
                "DISPLAY_TOP_PAGER" => "Y",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "PAGER_TITLE" => "Новости",
                "PAGER_SHOW_ALWAYS" => "Y",
                "PAGER_TEMPLATE" => "",
                "PAGER_DESC_NUMBERING" => "Y",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "Y",
                "PAGER_BASE_LINK_ENABLE" => "Y",
                "SET_STATUS_404" => "Y",
                "SHOW_404" => "Y",
                "MESSAGE_404" => "",
                "PAGER_BASE_LINK" => "",
                "PAGER_PARAMS_NAME" => "arrPager",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "PROPERTY_CODE" => array("PROPERTY_*")
            )
        );
    } else {?>
        <p> У вас нет готовых обращений. Сформировать обращение на возврат средств за медицинскую помощь по программе ОМС можно  <a href="/forma-obrashenija/" class="link-underline""> здесь. </a></p>
    <?php }?>
<?php } else {



    ?>
    <!-- Breadcrumbs -->
    <ul class="breadcrumbs">
        <li><a href="/">Главная</a></li>
        <li  class="active-breadcrumbs">обращения</li>
    </ul>
    <h1 class="page-title">Ваши обращения</h1>
    <div class="obrashcheniya">
        <p>Вам необходимо авторизоваться, чтобы видеть свои обращения, либо пройдите проверку своего диагноза <a class="link-underline" href="/forma-obrashenija/">здесь</a>.</p>
    </div>
<?php } ?>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>

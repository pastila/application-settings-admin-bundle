<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
global $USER;

use Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/news/news.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/news/news.min.js");
?>


<?php $APPLICATION->SetTitle("Читать про ОМС");?>
<?php $APPLICATION->IncludeComponent(
    "bitrix:breadcrumb",
    "custom",
    array(
        "START_FROM" => "0",
        "PATH" => "",
        "SITE_ID" => "s1"
    )
);?>
    <h1 class="page-title">Читать про ОМС</h1>

    <div class="container">
        <?php
        $APPLICATION->IncludeComponent(
            "bitrix:news",
            "news",
            array(
                "DISPLAY_DATE" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "SEF_MODE" => "Y",
                "AJAX_MODE" => "Y",
                "IBLOCK_TYPE" => "news",
                "IBLOCK_ID" => "20",
                "NEWS_COUNT" => "20",
                "USE_SEARCH" => "Y",
                "USE_RSS" => "Y",
                "USE_RATING" => "Y",
                "USE_CATEGORIES" => "Y",
                "USE_REVIEW" => "Y",
                "USE_FILTER" => "Y",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_ORDER1" => "ASC",
                "SORT_BY2" => "SORT",
                "SORT_ORDER2" => "ASC",
                "CHECK_DATES" => "Y",
                "PREVIEW_TRUNCATE_LEN" => "",
                "LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
                "LIST_FIELD_CODE" => array(),
                "LIST_PROPERTY_CODE" => array("PROPERTY_*"),
                "HIDE_LINK_WHEN_NO_DETAIL" => "Y",
                "DISPLAY_NAME" => "Y",
                "META_KEYWORDS" => "-",
                "META_DESCRIPTION" => "-",
                "BROWSER_TITLE" => "-",
                "DETAIL_SET_CANONICAL_URL" => "Y",
                "DETAIL_ACTIVE_DATE_FORMAT" => "d.m.Y",
                "DETAIL_FIELD_CODE" => array(),
                "DETAIL_PROPERTY_CODE" => array(),
                "DETAIL_DISPLAY_TOP_PAGER" => "Y",
                "DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
                "DETAIL_PAGER_TITLE" => "Страница",
                "DETAIL_PAGER_TEMPLATE" => "",
                "DETAIL_PAGER_SHOW_ALL" => "Y",
                "STRICT_SECTION_CHECK" => "Y",
                "SET_TITLE" => "Y",
                "ADD_SECTIONS_CHAIN" => "Y",
                "ADD_ELEMENT_CHAIN" => "N",
                "SET_LAST_MODIFIED" => "Y",
                "PAGER_BASE_LINK_ENABLE" => "Y",
                "SET_STATUS_404" => "Y",
                "SHOW_404" => "Y",
                "MESSAGE_404" => "",
                "PAGER_BASE_LINK" => "",
                "PAGER_PARAMS_NAME" => "arrPager",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
                "USE_PERMISSIONS" => "N",
                "GROUP_PERMISSIONS" => "",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "CACHE_FILTER" => "Y",
                "CACHE_GROUPS" => "Y",
                "DISPLAY_TOP_PAGER" => "Y",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "PAGER_TITLE" => "Новости",
                "PAGER_SHOW_ALWAYS" => "Y",
                "PAGER_TEMPLATE" => "",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "Y",
                "FILTER_NAME" => "",
                "FILTER_FIELD_CODE" => array(),
                "FILTER_PROPERTY_CODE" => array(),
                "NUM_NEWS" => "20",
                "NUM_DAYS" => "30",
                "YANDEX" => "Y",
                "MAX_VOTE" => "5",
                "VOTE_NAMES" => array("0", "1", "2", "3", "4"),
                "CATEGORY_IBLOCK" => array(),
                "CATEGORY_CODE" => "CATEGORY",
                "CATEGORY_ITEMS_COUNT" => "5",
                "MESSAGES_PER_PAGE" => "10",
                "USE_CAPTCHA" => "Y",
                "REVIEW_AJAX_POST" => "Y",
                "PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
                "FORUM_ID" => "1",
                "URL_TEMPLATES_READ" => "",
                "SHOW_LINK_TO_FORUM" => "Y",
                "POST_FIRST_MESSAGE" => "Y",
                "SEF_FOLDER" => "/news/",
                "SEF_URL_TEMPLATES" => array(
                    "detail" => "#ELEMENT_CODE#/",
                ),
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "VARIABLE_ALIASES" => array(
                    "detail" => array(),
                    "news" => array(),
                    "section" => array(),
                ),
                "USE_SHARE" => "Y",
                "SHARE_HIDE" => "Y",
                "SHARE_TEMPLATE" => "",
                "SHARE_HANDLERS" => "",
                "SHARE_SHORTEN_URL_LOGIN" => "",
                "SHARE_SHORTEN_URL_KEY" => "",
            )
        );?>

    </div>


<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
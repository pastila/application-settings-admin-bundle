<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("");

use Bitrix\Main\Page\Asset;
$asset = Asset::getInstance();

$asset->addCss(SITE_TEMPLATE_PATH . "/pages/stax-sluchay/main.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/stax-sluchay/main.min.js");

$asset->addCss(SITE_TEMPLATE_PATH . "/pages/forma-obrashenija/main.min.css");
if ($detect->isTablet() || $detect->isMobile()) {
    $asset->addJs(SITE_TEMPLATE_PATH . "/pages/forma-obrashenija/scroll.min.js");
}
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/forma-obrashenija/main.min.js");

CModule::IncludeModule("iblock");
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");
$arFilter = Array("IBLOCK_ID"=>19);
$arResult_block= array();
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement()){
    $arProps = $ob->GetProperties();
    $arResult_block[]= $arProps["TEXT_FORMA"]["VALUE"];
    $arResult_block[]= $arProps["FIRST_TEXT"]["VALUE"];

}
?><!-- Breadcrumbs -->
<ul class="breadcrumbs">
    <? if ($detect->isTablet() || $detect->isMobile()) { ?>
        <li><a href="/" class="active-breadcrumbs">Страховой случай в ОМС</a></li>
    <? } else { ?>
        <li><a href="/">Главная</a></li>
        <li class="active-breadcrumbs">Страховой случай в ОМС</li>
    <? } ?>
</ul>
<!-- Pages Title --> <section class="white_block">
    <h1 id="page-title" class="page-title">Страховой случай в системе ОМС</h1>
    <p>
        Оплачивали медицинскую помощь? Давайте проверим, можно ли вернуть деньги.
    </p>
</section>
<div class="steps-wrap">
    <div class="steps step-1">
        <div class="steps_navigation checked">
            <label class="steps_navigation_action" for="step-1" data-tab="step-1" data-step="1">Период оказания помощи</label> <input class="step-btn active" checked="checked" id="step-1" name="step" type="radio">
        </div>
        <div class="steps_navigation">
            <label class="steps_navigation_action" for="step-2" data-tab="step-2" data-step="2">Каким было ваше обращение ?</label> <input class="step-btn" id="step-2" name="step" type="radio">
        </div>
        <div class="steps_navigation">
            <label class="steps_navigation_action" for="step-3" data-tab="step-3" data-step="3">Ваша больница</label> <input class="step-btn" id="step-3" name="step" type="radio">
        </div>
        <div class="steps_navigation">
            <label class="steps_navigation_action" for="step-4" data-tab="step-4" data-step="4">Ваше заболевание</label> <input class="step-btn" id="step-4" name="step" type="radio">
        </div>
        <div class="progressive-bar-wrap">
            <div class="progressive-bar">
            </div>
            <div class="progressive-bar-set">
            </div>
        </div>
    </div>

    <form id="appeal-form" action="">
        <div class="steps_items">
            <div class="card steps_items_item step-1 active">
                <div class="steps_items_item_content">

                    <div class="steps_items_item_footer">
                        Укажите период, в котором вы получали помощь
                    </div>

                    <?$APPLICATION->IncludeComponent(
                        "bitrix:news.list",
                        "",
                        Array(
                            "ADD_SECTIONS_CHAIN" => "N",
                            "AJAX_MODE" => "Y",
                            "AJAX_OPTION_ADDITIONAL" => "",
                            "AJAX_OPTION_HISTORY" => "N",
                            "AJAX_OPTION_JUMP" => "N",
                            "AJAX_OPTION_STYLE" => "Y",
                            "CACHE_FILTER" => "Y",
                            "CACHE_GROUPS" => "Y",
                            "CACHE_TIME" => "3600",
                            "CACHE_TYPE" => "A",
                            "CHECK_DATES" => "N",
                            "DISPLAY_BOTTOM_PAGER" => "Y",
                            "DISPLAY_DATE" => "Y",
                            "DISPLAY_NAME" => "Y",
                            "DISPLAY_PICTURE" => "Y",
                            "DISPLAY_PREVIEW_TEXT" => "Y",
                            "DISPLAY_TOP_PAGER" => "Y",
                            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                            "IBLOCK_ID" => "17",
                            "IBLOCK_TYPE" => "",
                            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                            "INCLUDE_SUBSECTIONS" => "Y",
                            "MESSAGE_404" => "",
                            "NEWS_COUNT" => "100",
                            "PAGER_BASE_LINK" => "",
                            "PAGER_BASE_LINK_ENABLE" => "Y",
                            "PAGER_DESC_NUMBERING" => "Y",
                            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                            "PAGER_PARAMS_NAME" => "arrPager",
                            "PAGER_SHOW_ALL" => "Y",
                            "PAGER_SHOW_ALWAYS" => "Y",
                            "PAGER_TEMPLATE" => "",
                            "PAGER_TITLE" => "Новости",
                            "PARENT_SECTION" => "",
                            "PARENT_SECTION_CODE" => "",
                            "SET_BROWSER_TITLE" => "N",
                            "SET_LAST_MODIFIED" => "N",
                            "SET_META_DESCRIPTION" => "N",
                            "SET_META_KEYWORDS" => "N",
                            "SET_STATUS_404" => "Y",
                            "SET_TITLE" => "N",
                            "SHOW_404" => "Y",
                            "SORT_BY1" => "SORT",
                            "SORT_ORDER1" => "ASC"
                        )
                    );?>
                </div>

                <div style="display: flex">
                    <span class="steps_items_item_button mainBtn disabled" id="">Далее</span>
                </div>
            </div>
            <div class="card steps_items_item step-2">
                <div class="steps_items_item_content">
                    <div class="wrap-chrckbox plannet">
                        <label id="planned_label" class="check-label">
                            Плановое <input id="planned" data-planned="" type="radio" value=""> <span class="check-img"></span> </label> <label class="check-label">
                            Неотложное <input id="urgent" data-planned="" type="radio" value=""> <span class="check-img"></span> </label>
                    </div>
                </div>
                <div class="steps_items_item_footer">
                    <div class="steps_items_item_text">
                        <h3 class="steps_items_item_content_title">
                            <?=$arResult_block[1]?> </h3>
                        <p>
                            <?=$arResult_block[0]?>
                        </p>
                    </div>
                    <div class="steps_items_item_text">
                        <h3 class="steps_items_item_content_title">
                            <?=$arResult_block[3]?> </h3>
                        <p>
                            <?=$arResult_block[2]?>
                        </p>
                    </div>
                </div>
                <div style="display:flex;">
                    <span class="steps_items_item_button mainBtn disabled" id="">Далее</span>
                </div>
            </div>
            <div class="card steps_items_item step-3">
                <div id="hospitals" class="error_step-card-3">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:catalog.section.list",
                        "hospitals",
                        Array(
                            "ADD_SECTIONS_CHAIN" => "N",
                            "CACHE_GROUPS" => "Y",
                            "CACHE_NOTES" => "",
                            "CACHE_TIME" => "36000000",
                            "CACHE_TYPE" => "A",
                            "COUNT_ELEMENTS" => "N",
                            "IBLOCK_ID" => "9",
                            "IBLOCK_TYPE" => "",
                            "SECTION_CODE" => "",
                            "SECTION_FIELDS" => "",
                            "SECTION_ID" => "",
                            "SECTION_URL" => "",
                            "SECTION_USER_FIELDS" => "",
                            "SHOW_PARENT_NAME" => "N",
                            "TOP_DEPTH" => "1",
                            "VIEW_MODE" => "LIST"
                        )
                    );?>
                </div>
                <div class="steps_items_item_lists">
                    <div class="steps_items_item_lists_list">
                        <div class="steps_items_item_lists_list_title">
                            Вы выбрали регион
                        </div>
                        <div id="region_name" class="steps_items_item_lists_list_name">
                            Не выбрано
                        </div>
                    </div>
                    <div class="steps_items_item_lists_list">
                        <div class="steps_items_item_lists_list_title">
                            Вы выбрали медицинскую организацию
                        </div>
                        <div id="hosptital_name" class="steps_items_item_lists_list_name">
                            Не выбрано
                        </div>
                    </div>
                    <div class="steps_items_item_lists_list">
                        <div class="steps_items_item_lists_list_title">
                            Юридический адрес
                        </div>
                        <div id="street_name" class="steps_items_item_lists_list_name">
                            Не выбрано
                        </div>
                    </div>
                    <div class="steps_items_item_lists_list">
                        <div class="steps_items_item_lists_list_title">
                            Руководитель (главный врач)
                        </div>
                        <div id="boss_name" class="steps_items_item_lists_list_name">
                            Не выбрано
                        </div>
                    </div>
                </div>
                <div style="display:flex;">
                    <span class="steps_items_item_button mainBtn disabled" id="">Далее</span>
                </div>
            </div>
            <div class="card steps_items_item step-4">
                <p class="form-obrashcheniya__step_four_text">
                    <?=$arResult_block[8]?>
                </p>
                <a class="link-underline" target="_blank" href="/news/chto-oplachivaetsya-po-polisu/">Что оплачивается по полису ОМС</a>
                <div class="smart_search-block">
                    <!--                    <div class="steps_items_item_footer">--> <!--                        <p>--> <!--                            Начните вводить наименование заболевания, с которым вы обратились в медицинскую организацию.--> <!--                        </p>--> <!--                        <p>--> <!--                            «В связи с тем, что список диагнозов очень большой, поиск может работать медленно. Начните--> <!--                            вводить--> <!--                            заболевание и дождитесь появления списка заболеваний с похожими названиями, чтобы выбрать--> <!--                            нужное»--> <!--                        </p>--> <!--                    </div>-->
                    <div class="input-with-search">
                        <h3 class="steps_items_item_content_title" for="user_pass">Выберите диагноз: </h3>
                        <div class="input__wrap" id="reload_div">
                            <div class="input__ico">
                            </div>
                            <input type="text" id="search_diagnoz_input" placeholder="Начните вводить наименование заболевания, с которым вы обратились в медицинскую организацию.">
                            <ul style="cursor: pointer;" data-value="" class="custom-serach__items" id="search_diagnoz_global">
                            </ul>
                        </div>
                    </div>
                </div>
                <h3 class="steps_items_item_content_title">Или найдите диагноз в перечне заболеваний, структурированном в соответствии с международной классификацией болезней (МКБ-10).</h3>
                <div id="grid" class="steps_items_item_inputs" action="">
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:catalog.section.list",
                        "",
                        Array(
                            "ADD_SECTIONS_CHAIN" => "N",
                            "CACHE_GROUPS" => "Y",
                            "CACHE_NOTES" => "",
                            "CACHE_TIME" => "36000000",
                            "CACHE_TYPE" => "A",
                            "COUNT_ELEMENTS" => "N",
                            "IBLOCK_ID" => "8",
                            "IBLOCK_TYPE" => "",
                            "SECTION_CODE" => "",
                            "SECTION_FIELDS" => "",
                            "SECTION_ID" => "",
                            "SECTION_URL" => "",
                            "SECTION_USER_FIELDS" => "",
                            "SHOW_PARENT_NAME" => "N",
                            "TOP_DEPTH" => "3",
                            "VIEW_MODE" => "LIST"
                        )
                    );?>
                </div>
                <div style="display:flex;">
                    <span class="steps_items_item_button mainBtn disabled" id="">Готово</span>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- ALL STEPS IN FORM -->
<!-- 1 Step -->
<!-- 2 Step -->
<!-- <section class="form-obrashcheniya__step_two">
    <div class="card form-obrashcheniya__step_two_l error_step-card-1">

    </div>

    <div class="form-obrashcheniya__step_two_r">
        <div class="form-obrashcheniya__step_two_r_wrap">

        </div>

        <div class="form-obrashcheniya__step_two_r_wrap">

        </div>
    </div>
</section> -->
<!-- 3 Step -->
<!-- <section class="form-obrashcheniya__step_three">
    <div class="form-obrashcheniya__step_three_l">

    </div>

    <div class="form-obrashcheniya__step_three_r">
        <div class="card">


        </div>
    </div>
</section> -->
<!-- 4 Step -->
<!-- <section class="form-obrashcheniya__step_four">
    <div class="card error_step-card-4">

    </div>
</section> -->
<!-- </form> --><?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>
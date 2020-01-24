<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;
$asset = Asset::getInstance();

$asset->addCss(SITE_TEMPLATE_PATH . "/pages/forma-obrashenija/main.min.css");
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/stax-sluchay/main.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/forma-obrashenija/main.min.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/stax-sluchay/main.min.js");
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
?>


<!-- Breadcrumbs -->
<ul class="breadcrumbs">
    <? if ($detect->isTablet() || $detect->isMobile()) { ?>
    <li><a href="/" class="">Страховой случай в ОМС</a></li>
    <? } else { ?>
    <li><a href="/">Главная</a></li>
    <li>Страховой случай в ОМС</li>
    <? } ?>

</ul>

<!-- Pages Title -->
<section>
    <h2 id="page-title" class="page-title">Страховой случай в системе ОМС</h2>
    <p>Страховым случаем в системе ОМС является случай оказания медицинской помощи, который должен быть оплачен
        из средств ОМС страховой компанией, а не личными средствами пациента. Страховыми являются случаи, которые
        соответствуют условиям, прописанным в программе государственных гарантий. Ниже приведены вопросы, по вашим
        ответам на которые мы сможем определить, является ли ваш случай лечения страховым. Если вы когда-либо
        оплачивали медицинскую помощь или в данный момент получаете медицинскую помощь на платной основе, пройдите
        ниже и ответьте на вопросы. Возможно, это поможет вам избежать оплаты тех медицинских услуг, которые должны
        выполняться бесплатно, или даже вернуть средства, которые вы уже заплатили.</p>
</section>

<!-- ALL STEPS IN FORM -->
<form id="appeal-form" action="">
    <!-- 1 Step -->
    <section class="form-obrashcheniya__step_one">
        <h3 class="form-obrashcheniya__step_one_title">
            Укажите период, в котором вы получали помощь
        </h3>
        <?php
            $APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "",
                array(
                    "DISPLAY_DATE" => "Y",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => "Y",
                    "DISPLAY_PREVIEW_TEXT" => "Y",
                    "AJAX_MODE" => "Y",
                    "IBLOCK_TYPE" => "",
                    "IBLOCK_ID" => "17",
                    "NEWS_COUNT" => "100",
                    "SORT_BY1" => "SORT",
                    "SORT_ORDER1" => "ASC",
                    "CHECK_DATES" => "N",
                    "SET_TITLE" => "N",
                    "SET_BROWSER_TITLE" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                    "PARENT_SECTION" => "",
                    "PARENT_SECTION_CODE" => "",
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "3600",
                    "CACHE_FILTER" => "Y",
                    "CACHE_GROUPS" => "Y",
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
                    "AJAX_OPTION_ADDITIONAL" => ""
                )
            );?>
    </section>

    <!-- 2 Step -->
    <section class="form-obrashcheniya__step_two">
        <div class="card form-obrashcheniya__step_two_l error_step-card-1">
            <div class="form-obrashcheniya__step_two_l_title">
                Каким было ваше обращение ?
            </div>

            <div class="wrap-chrckbox plannet">
                <label id="planned_label" class="check-label">
                    Плановое
                    <input id="planned" type="checkbox" value="" />
                    <span class="check-img"></span>
                </label>

                <label class="check-label">
                    Неотложное
                    <input id="urgent" type="checkbox" value="" />
                    <span class="check-img"></span>
                </label>
            </div>
        </div>

        <div class="form-obrashcheniya__step_two_r">
            <div class="form-obrashcheniya__step_two_r_wrap">
                <h3 class="form-obrashcheniya__step_two_r_wrap_title">
                    <?=$arResult_block[1]?>
                </h3>
                <p><?=$arResult_block[0]?></p>
            </div>

            <div class="form-obrashcheniya__step_two_r_wrap">
                <h3 class="form-obrashcheniya__step_two_r_wrap_title">
                    <?=$arResult_block[3]?>
                </h3>
                <p><?=$arResult_block[2]?></p>
            </div>
        </div>
    </section>

    <!-- 3 Step -->
    <section class="form-obrashcheniya__step_three">
        <div class="form-obrashcheniya__step_three_l">
            <div id="hospitals" class="card error_step-card-3">
                <?php
                    $APPLICATION->IncludeComponent(
                        "bitrix:catalog.section.list",
                        "hospitals",
                        array(
                            "VIEW_MODE" => "LIST",
                            "SHOW_PARENT_NAME" => "N",
                            "IBLOCK_TYPE" => "",
                            "IBLOCK_ID" => "9",
                            "SECTION_ID" => "",
                            "SECTION_CODE" => "",
                            "SECTION_URL" => "",
                            "COUNT_ELEMENTS" => "N",
                            "TOP_DEPTH" => "1",
                            "SECTION_FIELDS" => "",
                            "SECTION_USER_FIELDS" => "",
                            "ADD_SECTIONS_CHAIN" => "N",
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "36000000",
                            "CACHE_NOTES" => "",
                            "CACHE_GROUPS" => "Y"
                        )
                    );?>
            </div>
        </div>

        <div class="form-obrashcheniya__step_three_r">
            <div class="card">
                <div class="form-obrashcheniya__step_three_r_wrap">
                    <h3 class="form-obrashcheniya__step_three_r_wrap_title">
                        Вы выбрали регион:
                    </h3>

                    <div id="region_name" class="form-obrashcheniya__step_three_r_wrap_name">
                        Не выбрано
                    </div>
                </div>

                <div class="form-obrashcheniya__step_three_r_wrap">
                    <h3 class="form-obrashcheniya__step_three_r_wrap_title">
                        Вы выбрали медицинскую организацию:
                    </h3>

                    <div id="hosptital_name" class="form-obrashcheniya__step_three_r_wrap_name">
                        Не выбрано
                    </div>
                </div>

                <div class="form-obrashcheniya__step_three_r_wrap">
                    <h3 class="form-obrashcheniya__step_three_r_wrap_title">
                        Расположение по адресу
                    </h3>

                    <div id="street_name" class="form-obrashcheniya__step_three_r_wrap_name">
                        Не выбрано
                    </div>

                </div>

                <div class="form-obrashcheniya__step_three_r_wrap">
                    <h3 class="form-obrashcheniya__step_three_r_wrap_title">
                        Руководитель (главный врач)
                    </h3>

                    <div id="boss_name" class="form-obrashcheniya__step_three_r_wrap_name">
                        Не выбрано
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- 4 Step -->
    <section class="form-obrashcheniya__step_four">
        <div class="card error_step-card-4">
            <p class="form-obrashcheniya__step_four_text"><?=$arResult_block[8]?></p>

            <a class="link-underline" target="_blank" href="/news/chto-oplachivaetsya-po-polisu/">Что оплачивается по полису ОМС</a>

            <div class="smart_search-block">
                <p class="title-select bold">
                    Начните вводить наименование заболевания, с которым вы обратились в медицинскую организацию.
                </p>
                <p class="title-select bold">
                    «В связи с тем, что список диагнозов очень большой, поиск может работать медленно. Начните вводить
                    заболевание и дождитесь появления списка заболеваний с похожими названиями, чтобы выбрать нужное»
                </p>
                <form action="">
                    <div class="input-with-search">
                        <label class="title-select" for="user_pass">Выберите диагноз: </label>
                        <div class="input__wrap" id="reload_div">
                            <div class="input__ico">
                                <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255">
                                    <path d="M0 63.75l127.5 127.5L255 63.75z" /></svg>
                            </div>
                            <input type="text" id="search_diagnoz_input"
                                placeholder="Начните вводить наименование заболевания, с которым вы обратились в медицинскую организацию." />
                            <ul style="cursor: pointer;" data-value="" class="custom-serach__items"
                                id="search_diagnoz_global">
                            </ul>
                        </div>
                    </div>
                </form>
            </div>

            <p class="title-select bold">Или</p>
            <p class="d-block mb--2rem">Найдите диагноз в перечне заболеваний, структурированном в соответствии с
                международной классификацией болезней (МКБ-10).</p>
            <div id="grid" class="grid" action="">
                <?php
                    $APPLICATION->IncludeComponent(
                        "bitrix:catalog.section.list",
                        "",
                        array(
                            "VIEW_MODE" => "LIST",
                            "SHOW_PARENT_NAME" => "N",
                            "IBLOCK_TYPE" => "",
                            "IBLOCK_ID" => "8",
                            "SECTION_ID" => "",
                            "SECTION_CODE" => "",
                            "SECTION_URL" => "",
                            "COUNT_ELEMENTS" => "N",
                            "TOP_DEPTH" => "3",
                            "SECTION_FIELDS" => "",
                            "SECTION_USER_FIELDS" => "",
                            "ADD_SECTIONS_CHAIN" => "N",
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "36000000",
                            "CACHE_NOTES" => "",
                            "CACHE_GROUPS" => "Y"
                        )
                    );?>
            </div>
        </div>
    </section>

    <div style="display:flex;">
        <button style="margin: 0 auto;" id="strax-sluchay" class="mainBtn">Готово</button>
    </div>
</form>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>
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
        <li>Главная</li>
        <li>Диагноз</li>
    </ul>

    <!-- Pages Title -->
    <h2 id="page-title" class="page-title">Проверить свой дигноз</h2>

    <!-- ALL STEPS IN FORM -->
    <form id="appeal-form" action="">
        <!-- 1 Step -->
        <section class="form-obrashcheniya__step_one">
            <div class="form-obrashcheniya__step_one_title">
                Укажите период, в котором вы получали помощь
            </div>
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

                    <label  class="check-label">
                        Неотложное
                        <input id="urgent" type="checkbox" value="" />
                        <span class="check-img"></span>
                    </label>
                </div>
            </div>

            <div class="form-obrashcheniya__step_two_r">
                <div class="form-obrashcheniya__step_two_r_wrap">
                    <div class="form-obrashcheniya__step_two_r_wrap_title">
                       <?=$arResult_block[1]?>
                    </div>
                    <p><?=$arResult_block[0]?></p>
                </div>

                <div class="form-obrashcheniya__step_two_r_wrap">
                    <div class="form-obrashcheniya__step_two_r_wrap_title">
                        <?=$arResult_block[3]?>
                    </div>
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
                        <div class="form-obrashcheniya__step_three_r_wrap_title">
                            Вы выбрали регион:
                        </div>

                        <div id="region_name" class="form-obrashcheniya__step_three_r_wrap_name">
                            Не выбрано
                        </div>
                    </div>

                    <div class="form-obrashcheniya__step_three_r_wrap">
                        <div class="form-obrashcheniya__step_three_r_wrap_title">
                            Вы выбрали медицинскую организацию:
                        </div>

                        <div id="hosptital_name" class="form-obrashcheniya__step_three_r_wrap_name">
                            Не выбрано
                        </div>
                    </div>

                    <div class="form-obrashcheniya__step_three_r_wrap">
                        <div class="form-obrashcheniya__step_three_r_wrap_title">
                            Расположение по адресу
                        </div>

                        <div id="street_name" class="form-obrashcheniya__step_three_r_wrap_name">
                            Не выбрано
                        </div>

                   </div>

                    <div class="form-obrashcheniya__step_three_r_wrap">
                        <div class="form-obrashcheniya__step_three_r_wrap_title">
                            Руководитель (главный врач)
                        </div>

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

                <a class="link-underline" href="#">Ссылка на статью в блоге</a>

                <div class="smart_search-block">
                    <p class="title-select bold">Начните вводить наименование заболевания, с которым вы обратились в медицинскую организацию.</p>
                    <form action="">
                        <div class="input-with-search">
                            <label class="title-select" for="user_pass">Выберите диагноз: </label>
                            <div class="input__wrap">
                                <div class="input__ico">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                                </div>
                                <input />
                            </div>
                        </div>
                        <div class="wrap-chrckbox checkbox_registration">
                            <label class="check-label">
                                Я не знаю своего диагноза
                                <input type="checkbox" value="">
                                <span class="check-img"></span>
                            </label></div>
                    </form>
                </div>

                <p class="title-select bold">Или найдите диагноз в перечне заболеваний, структурированном в соответствии с международной классификацией болезней (МКБ-10).</p>
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

        <span id="strax-sluchay" class="mainBtn">проверить диагноз</span>
    </form>
    <div class="info_block forma_block">
        <div class="block_image">
            <img src="/local/templates/kdteam/images/png/forma-obrasheniya/word.webp" alt="Шаг 1">
        </div>
        <p>1. Страховым случаем в системе ОМС является случай оказания медицинской помощи, который должен быть оплачен
            из средств ОМС страховой компанией, а не личными средствами пациента. Страховыми являются случаи, которые
            соответствуют условиям, прописанным в программе государственных гарантий. Ниже приведены вопросы, по вашим
            ответам на которые мы сможем определить, является ли ваш случай лечения страховым. Если вы когда-либо
            оплачивали медицинскую помощь или в данный момент получаете медицинскую помощь на платной основе, пройдите
            ниже и ответьте на вопросы. Возможно, это поможет вам избежать оплаты тех медицинских услуг, которые должны
            выполняться бесплатно, или даже вернуть средства, которые вы уже заплатили.</p>
        <p>2. <span class="bold">Плановое</span> – обращение в медицинскую организацию, не связанное с ухудшением здоровья. Это обращение «без
            жалоб», когда вы приходите для того, чтобы пройти очередное обследование по поводу имеющегося хронического
            заболевания для контроля состояния и лечения. Например, явка к врачу раз в полгода для прохождения УЗИ и
            сдачи анализов в связи с имеющимся хроническим заболеванием – это плановое обращение. При этом важно, что
            ухудшения состояния нет.</p>
        <p>Также к плановым обращениям относятся визиты для прохождения профилактических осмотров, диспансеризации.</p>
        <p><span class="bold">Неотложное</span> – любое обращение, вызванное ухудшением состояния здоровья. Ухудшение может быть связано как с
            хроническим заболеванием (обострение) так и с острым (например, ОРВИ). Не важно, когда произошло ухудшение -
            день или неделю назад. Если причиной обращения к врачу стало усиление симптомов хронического заболевания,
            или появление новых симптомов, обращение считается неотложным.</p>
        <div class="block_image">
            <img src="/local/templates/kdteam/images/png/forma-obrasheniya/word-2.webp" alt="Шаг 2">
        </div>
        <p>3. Ниже приведен список всех медицинских организаций, <a href="/news/kakie-byvayut-bolnitsy/">участвующих в реализации программы ОМС.</a> Ситуации, в которых данные медицинские организации имеют право брать деньги с
            пациентов, имеющих полис ОМС, регламентированы документом - Правилами предоставления платных медицинских
            услуг. Если вам пришлось платить деньги больнице, включенной в список, велика вероятность того, что оплата
            была незаконной, а помощь должна была оплачиваться средствами страховой компании. </p>
        <p>Если вы забыли название медицинской организации, посмотреть его можно в договоре, который должен выдаваться
            всегда при оказании платных медицинских услуг. Также для того, чтобы вы не ошиблись при поиске медицинской
            организации, справа указан адрес и ФИО руководителя выбранной вами больницы. Убедитесь, что все верно,
            прежде чем переходить к следующему шагу.</p>
        <p>Обратите внимание, если вы посещали подразделение медицинской организации (например, филиал поликлиники),
            адрес, указанный справа, будет отличаться, так как это фактический адрес медицинской организации, а не
            филиала. В договоре на оказание платных услуг обязательно указывается «основной» адрес больницы (адрес
            юридического лица), даже если лечение выполнялось в филиале. Сверьте адрес выбранной больницы с адресом в
            договоре – они должны совпадать.</p>
        <p>4. И последнее, что осталось сделать – найти в размещенном ниже списке заболевание, по поводу которого вы
            получали медицинские услуги. Наличие диагноза в перечне заболеваний программы государственных гарантий –
            одно из обязательных условий для признания случая лечения страховым, то есть подлежащим оплате из средств
            ОМС. Так как список очень и очень большой, мы предлагаем вам два варианта поиска: начните вводить
            заболевание в первой строке и выберите из предложенных вариантов свой, или выберите последовательно класс,
            группу, подгруппу и сам диагноз в соответствии со структурой международной классификацией болезней. Если
            точного диагноза вы не знаете, укажите наиболее близкий к нему. Напоминаем, что <a
                    href="/news/chto-oplachivaetsya-po-polisu/">по ОМС оплачиваются все
                заболевания</a>, кроме социально значимых (туберкулез;
            инфекции, передаваемые преимущественно половым путем; ВИЧ-инфекция). Все остальные заболевания точно есть в
            списке!</p>
        <div class="block_image">
            <img src="/local/templates/kdteam/images/png/forma-obrasheniya/word-3.webp" alt="Шаг 3">
        </div>
    </div>


<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>
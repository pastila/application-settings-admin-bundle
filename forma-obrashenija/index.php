<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;
$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/forma-obrashenija/main.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/forma-obrashenija/main.min.js");
//$asset->addJs(SITE_TEMPLATE_PATH . "/pages/forma-obrashenija/forma-obrashenija.min.js");
?>


    <!-- Breadcrumbs -->
    <ul class="breadcrumbs">
        <li>Главная</li>
        <li>Диагноз</li>
    </ul>

    <!-- Pages Title -->
    <h2 class="page-title">Проверить свой дигноз</h2>

    <!-- ALL STEPS IN FORM -->
    <form action="">
        <!-- 1 Step -->
        <section class="form-obrashcheniya__step_one">
            <div class="form-obrashcheniya__step_one_title">
                Укажите период, в котором вы получали помощь
            </div>

            <!-- Checkbox -->
            <div class="wrap-chrckbox">
                <label class="check-label">
                    2019 год
                    <input type="checkbox" value="" />
                    <span class="check-img"></span>
                </label>

                <label class="check-label">
                    2018 год
                    <input type="checkbox" value="" />
                    <span class="check-img"></span>
                </label>

                <label class="check-label">
                    2017 год
                    <input type="checkbox" value="" />
                    <span class="check-img"></span>
                </label>

                <label class="check-label">
                    2016 год
                    <input type="checkbox" value="" />
                    <span class="check-img"></span>
                </label>

                <label class="check-label">
                    2015 год
                    <input type="checkbox" value="" />
                    <span class="check-img"></span>
                </label>

                <label class="check-label">
                    2014 год
                    <input type="checkbox" value="" />
                    <span class="check-img"></span>
                </label>

                <label class="check-label">
                    2013 год
                    <input type="checkbox" value="" />
                    <span class="check-img"></span>
                </label>
            </div>
        </section>

        <!-- 2 Step -->
        <section class="form-obrashcheniya__step_two">
            <div class="card form-obrashcheniya__step_two_l">
                <div class="form-obrashcheniya__step_two_l_title">
                    Каким было ваше обращение ?
                </div>

                <div class="wrap-chrckbox">
                    <label class="check-label">
                        Плановое
                        <input type="checkbox" value="" />
                        <span class="check-img"></span>
                    </label>

                    <label class="check-label">
                        Неотложное
                        <input type="checkbox" value="" />
                        <span class="check-img"></span>
                    </label>
                </div>
            </div>

            <div class="form-obrashcheniya__step_two_r">
                <div class="form-obrashcheniya__step_two_r_wrap">
                    <div class="form-obrashcheniya__step_two_r_wrap_title">
                        Плановое
                    </div>
                    <p>при проведении профилактических мероприятий, при заболеваниях и состояниях, не
                        сопровождающихся угрозой жизни пациента, не требующих экстренной и неотложной
                        медицинской
                        помощи, отсрочка оказания которой на определенное время не повлечет за собой ухудшение
                        состояния пациента, угрозу его жизни и здоровью</p>
                </div>

                <div class="form-obrashcheniya__step_two_r_wrap">
                    <div class="form-obrashcheniya__step_two_r_wrap_title">
                        Плановое
                    </div>
                    <p>при проведении профилактических мероприятий, при заболеваниях и состояниях, не
                        сопровождающихся угрозой жизни пациента, не требующих экстренной и неотложной
                        медицинской
                        помощи, отсрочка оказания которой на определенное время не повлечет за собой ухудшение
                        состояния пациента, угрозу его жизни и здоровью</p>
                </div>
            </div>
        </section>

        <!-- 3 Step -->
        <section class="form-obrashcheniya__step_three">
            <div class="form-obrashcheniya__step_three_l">
                <div id="hospitals" class="card">
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
                            Вы выбрали поликлинику:
                        </div>

                        <div id="hosptital_name" class="form-obrashcheniya__step_three_r_wrap_name">
                            Не выбрано
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4 Step -->
        <section class="form-obrashcheniya__step_four">
            <div class="card">
                <p class="form-obrashcheniya__step_four_text">Мы собрали для вас информацию обо всех
                    медорганизациях, оказывающих помощь по полису ОМС……..
                    есть ли в данном списке больница, в которую вы обратились за помощью. Если больницы в списке
                    нет, денежные средства вернуть не получится…… Рекомендуем сверяться со списком при обращении
                    за помощью</p>

                <a class="link-underline" href="#">Ссылка на статью в блоге</a>

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

        <a href="/strax-sluchay.html" class="mainBtn">проверить диагноз</a>
    </form>


<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>
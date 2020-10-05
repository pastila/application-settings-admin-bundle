<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords_inner", "медицинское страхование отзывы, бесплатное лечение, бесплатная медицинская помощь, рейтинг медицинских страховых компаний омс в москве");
$APPLICATION->SetPageProperty("title", "Рейтинг страховых компаний работающих в системе ОМС");
$APPLICATION->SetPageProperty("keywords", "медицинское страхование отзывы, бесплатное лечение, бесплатная медицинская помощь, рейтинг медицинских страховых компаний омс в москве");
$APPLICATION->SetPageProperty("description", "Рейтинг страховых компаний работающих в системе ОМС");
$APPLICATION->SetTitle("Рейтинг страховых компаний работающих в системе ОМС");

use Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/feedback/main.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/feedback/main.min.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/readmore.min.js");

CModule::IncludeModule("iblock");
global $USER;


$sort_url = $_GET;

$array_all_company = array();

$order = Array("name" => "asc");
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_KPP", "CODE");
$arFilter = Array("IBLOCK_ID" => 16, "ACTIVE" => "Y");
$res = CIBlockElement::GetList($order, $arFilter, false, false, $arSelect);
while ($ob = $res->GetNextElement()) {

    $arProps = $ob->GetFields();

    $allReviews[$arProps['PROPERTY_KPP_VALUE']] = $arProps;
}
$countReviews = count($allReviews);


?>
<!-- Breadcrumbs -->
<ul class="breadcrumbs">
    <? if ($detect->isTablet() || $detect->isMobile()) { ?>
        <li class="active-breadcrumbs"><a href="/" class="">Отзывы</a></li>
    <? } else { ?>
        <li><a href="/">Главная</a></li>
        <li class="active-breadcrumbs">Отзывы</li>
    <? } ?>

</ul>

<!-- Pages Title -->
<h1 class="page-title">Отзывы о страховых медицинских организациях</h1>

<!-- ALL STEPS IN FORM -->
<form action="" class="white_block">

    <!-- Add and All feedback btns -->
    <div class="feedback__btns">
        <a href="/add-feedback/" class="whiteBtn">Добавить отзыв</a>
        <a href="?comments=all" class="accentBtnBorder">Все отзывы</a>
        <?php if ($USER->IsAdmin()) { ?>
            <a href="?admin=property_verified" class="accentBtnBorder">Модерация отзывов</a>
        <?php } ?>
    </div>

    <div class="feedback__filter">
        <div class="custom-select column-reverse_select">
            <select style="display: none">
                <option value="">Страховая компания <span>( <?= $countReviews ?> )</span></option>
                <?
                $bool_check_kpp = false;
                $name_company = "";
                foreach ($allReviews as $review) {

                    if (isset($_GET["property_evaluation"]) || isset($_GET["property_kpp"]) || isset($_GET["property_region"])) {
                        if ($_GET["property_kpp"] != $review["PROPERTY_KPP_VALUE"]) {

                            $url_for_filter = "?";

                            foreach ($sort_url as $key => $filter) {

                                if ($key != "property_kpp") {
                                    $url_for_filter .= "$key=$filter&";
                                }
                            }

                            ?>
                            <option value="<?= $url_for_filter ?>property_kpp=<?= $review["PROPERTY_KPP_VALUE"] ?>">
                                <?= $review["NAME"] ?></option>

                        <? } else {
                            $url_for_filter = "?";
                            $bool_check_kpp = true;

                            foreach ($sort_url as $key => $filter) {

                                if ($key != "property_kpp") {
                                    $url_for_filter .= "$key=$filter&";
                                }
                            }
                            if ($url_for_filter == "?") {
                                $url_for_filter = "";
                            }
                            $name_company = $review["NAME"];
                            ?>
                            <option value="<?= $url_for_filter ?>" class="activ_filter"><?= $review["NAME"] ?></option>
                        <? }

                    } else { ?>

                        <option value="?property_kpp=<?= $review["PROPERTY_KPP_VALUE"] ?>"><?= $review["NAME"] ?></option>
                    <? }

                }

                ?>
            </select>
            <?php if (isset($_GET["property_evaluation"]) || isset($_GET["property_kpp"]) || isset($_GET["property_region"])) {
                if ($bool_check_kpp === true) {
                    $url_for_filter = "?";
                    foreach ($sort_url as $key => $filter) {

                        if ($key != "property_kpp" && $key!="PAGEN_1") {
                            $url_for_filter .= "$key=$filter&";
                        }
                    }
                    if ($url_for_filter == "?") {
                        $url_for_filter = "/feedback/";
                    }
                    ?>
                    <a data-tooltip="Очистить отзывы" data-position="bottom" class="refresh_active-link bottom"
                       href="<?= $url_for_filter ?>">
                        <div class="activ_filter after-select_activ-filter">
                            <?= $name_company ?></div>
                    </a>
                <? }
            } ?>

        </div>

        <div class="custom-select column-reverse_select">
            <select style="display: none" onchange="window.open(this.value)">

                <option value="0">Оценка</option>
                <?php
                $bool_check_evalution = false;
                $check_evalution = "";
                if (isset($_GET["property_evaluation"]) || isset($_GET["property_name_company"]) || isset($_GET["property_region"]) || isset($_GET["property_kpp"])) {


                    $url_for_filter = "?";

                    foreach ($sort_url as $key => $filter) {
                        if ($key != "property_evaluation") {
                            $url_for_filter .= "$key=$filter&";
                        }
                    }

                    for ($i = 1; $i <= 5; ++$i) {
                        if ($url_for_filter == "") {
                            $url_for_filter = "?";
                        }
                        if ($_GET["property_evaluation"] != $i) {

                            ?>
                            <option value="<?= $url_for_filter ?>property_evaluation=<?= $i ?>" class="number_star">
                                Оценки <?= $i ?>
                            </option>
                        <?php } else {
                            if ($url_for_filter == "?") {
                                $url_for_filter = "";
                            }
                            $bool_check_evalution = true;
                            $check_evalution = $i;
                            ?>
                            <option value="<?= $url_for_filter ?>" class="number_star activ_filter">
                                Оценки <?= $i ?>
                            </option>

                            <?
                        }
                    }
                } else {
                    for ($i = 1; $i <= 5; ++$i) {
                        ?>

                        <option value="?property_evaluation=<?= $i ?>" class="number_star">Оценки <?= $i ?>
                        </option>
                    <? }
                } ?>


            </select>
            <?php if (isset($_GET["property_evaluation"]) || isset($_GET["property_kpp"]) || isset($_GET["property_region"])) {
                if ($bool_check_evalution === true) {

                    $url_for_filter = "?";
                    foreach ($sort_url as $key => $filter) {

                        if ($key != "property_evaluation" && $key!="PAGEN_1") {
                            $url_for_filter .= "$key=$filter&";
                        }
                    }
                    if ($url_for_filter == "?") {
                        $url_for_filter = "/feedback/";
                    }
                    ?>
                    <a data-tooltip="Очистить оценку" data-position="bottom" class="refresh_active-link bottom"
                       href="<?= $url_for_filter ?>">
                        <div class="activ_filter after-select_activ-filter">
                            Оценка <?= $check_evalution ?></div>
                    </a>
                <? }
            } ?>

        </div>

        <div class="custom-select column-reverse_select">
            <select style="display: none" onchange="window.open(this.value)">
                <option value="0">Регион</option>
                <?php
                $name_region = "";
                $order = Array("name" => "asc");
                $arFilter = Array("IBLOCK_ID" => 16);
                $Section_filter = CIBlockSection::GetList($order, $arFilter, false);
                if (isset($_GET["property_evaluation"]) || isset($_GET["property_name_company"]) || isset($_GET["property_region"]) || isset($_GET["property_kpp"])) {
                    while ($ob_section_filter = $Section_filter->GetNext()) {
                        if ($_GET["property_region"] != $ob_section_filter["ID"]) {
                            $url_for_filter = "?";
                            foreach ($sort_url as $key => $filter) {
                                if ($key != "property_region") {
                                    $url_for_filter .= "$key=$filter&";
                                }
                            }
                            ?>
                            <option class="sidebar__item_lists_list"
                                    value="<?= $url_for_filter ?>property_region=<?= $ob_section_filter["ID"] ?>">
                                <?= $ob_section_filter["NAME"] ?>
                            </option>
                        <? } else {
                            $url_for_filter = "?";
                            $bool_check_region = true;

                            $name_region = $ob_section_filter["NAME"];
                            foreach ($sort_url as $key => $filter) {
                                if ($key != "property_region") {
                                    $url_for_filter .= "$key=$filter&";
                                }
                            }
                            if ($url_for_filter == "?") {
                                $url_for_filter = "";
                            }
                            ?>
                            <option class="activ_filter" value="<?= $url_for_filter ?>">
                                <?= $ob_section_filter["NAME"] ?>
                            </option>
                        <? }

                    }
                    ?>
                <? } else {
                    while ($ob_section_filter = $Section_filter->GetNext()) {
                        ?>
                        <option class="sidebar__item_lists_list"
                                value="?property_region=<?= $ob_section_filter["ID"] ?>">
                            <?= $ob_section_filter["NAME"] ?>
                        </option>
                    <? } ?>

                <? } ?>
            </select>
            <?php if (isset($_GET["property_evaluation"]) || isset($_GET["property_kpp"]) || isset($_GET["property_region"])) {
                if ($bool_check_region === true) {

                    $url_for_filter = "?";
                    foreach ($sort_url as $key => $filter) {

                        if ($key != "property_region" && $key!="PAGEN_1") {
                            $url_for_filter .= "$key=$filter&";
                        }
                    }
                    if ($url_for_filter == "?") {
                        $url_for_filter = "/feedback/";
                    }
                    ?>
                    <a data-tooltip="Очистить город" data-position="bottom" class="refresh_active-link bottom"
                       href="<?= $url_for_filter ?>">
                        <div class="activ_filter after-select_activ-filter">
                            <?= $name_region ?></div>
                    </a>
                <? }
            } ?>

        </div>

        <a class="feedback__filter__reset" href="/feedback/">Сбросить фильтры</a>
    </div>

</form>

<!-- Wrap -->
<div class="feedback">
    <div class="feedback__wrap_white-blocks">
        <!-- FeedBack block -->

        <?php

        $arFilter = Array(
            "IBLOCK_ID" => 13,
            "ACTIVE" => "Y",
            "USER_NO_AUTH" => false,
            "!PROPERTY_VERIFIED" => false,
        );

        if (isset($sort_url["admin"])) {
            $arFilter = Array(
                "IBLOCK_ID" => 13,
                "ACTIVE" => "Y",
                $sort_url["admin"] => false,
                "!USER_NO_AUTH" => false,
            );
        } else {
            foreach ($sort_url as $key => $filter) {
                $key = mb_strtoupper($key);
                $arFilter += [$key => $filter];
            }
        }

        $order = Array("created" => "desc");
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "CREATED_DATE", "PROPERTY_*");


        $pagen = Array("nPageSize" => 10);
        if ($sort_url["comments"] == "all" && !isset($_GET["PAGEN_1"])) {
            $pagen = false;
        }else if(!isset($_GET["PAGEN_1"])){

            $pagen["iNumPage"] = 1;
        }


        $res = CIBlockElement::GetList($order, $arFilter, false, $pagen, $arSelect);
        if (!$sort_url["comments"] == "all") {
            $res->NavStart(0);
        }
        $is_elemnt = true;
        if ($res->SelectedRowsCount() == 0) {
            $is_elemnt = false;
            ?>

            <div>
                <p class="error title-medium">Отзывы не найдены</p>
            </div>


        <? }

        while ($ob = $res->GetNextElement()) {

            $arFields = $ob->GetFields();
            $arProps = $ob->GetProperties();

            $newdata = explode(".", $arFields["CREATED_DATE"]);
            $newstrDate = $newdata[2] . '.' . $newdata[1] . '.' . $newdata[0];

            $newDate = FormatDate("d F, Y", MakeTimeStamp($newstrDate));
            if($sort_url["admin"] == "") {
                if ($arProps["NAME_USER"]["VALUE"] == "" && $arProps["VERIFIED"]["VALUE"] == "") {
                    continue;
                }
            }
//print_r($arProps["NAME_USER"]["VALUE"]);
            if ($arProps["NAME_USER"]["VALUE"] == ""){
                $name_user = $arProps["USER_NO_AUTH"]["VALUE"];
            }else{
                $ID_USER = $arProps["NAME_USER"]["VALUE"];
                $rsUser = CUser::GetByID($ID_USER);
                $arUser = $rsUser->Fetch();
                $name_user = $arUser["NAME"];
if ($name_user == ""):
$name_user = $arProps["USER_NO_AUTH"]["VALUE"];
endif;
            }
            if ($arProps["DATE_CHANGE_BY_USER"]["VALUE"] != "") {
                $Date_change_user = FormatDate("d F, Y", MakeTimeStamp($arProps["DATE_CHANGE_BY_USER"]["VALUE"]));
            } else {

                $Date_change_user = "";
            }


            if (is_array($arProps["COMMENTS_TO_REWIEW"]["VALUE"])) {
                $count_comments = count($arProps["COMMENTS_TO_REWIEW"]["VALUE"]);
            } else {
                $count_comments = 0;
            }
            $city = CIBlockSection::GetByID($arProps["REGION"]["VALUE"])->GetNext();
            if ($arProps["NAME_COMPANY"]["VALUE"] == "") {
                continue;
            }
            $compani = CIBlockElement::GetByID($arProps["NAME_COMPANY"]["VALUE"])->GetNextElement()->GetProperties();
            $compani_fields = CIBlockElement::GetByID($arProps["NAME_COMPANY"]["VALUE"])->GetNextElement()->GetFields();

            $file = CFile::ResizeImageGet($compani["LOGO_IMG"]["VALUE"], array('width' => 120, 'height' => 80),
                BX_RESIZE_IMAGE_PROPORTIONAL, true);

            ?>
            <div class="white_block white_block_riview_<?= $arFields["ID"]; ?>">
                <?php if ($Date_change_user != "") { ?>
                    <span class="date_review">Дата изменения <?php echo $Date_change_user; ?></span>
                <?php } ?>
                <?php if ($arProps["REVIEW_LETTER"]["VALUE"] == "1") { ?>
                    <h3 class="feedback__title">Возврат денежных средств</h3>
                <?php } ?>

                <!-- top -->
                <div class="feedback__block_top">

                    <?php if ($arProps["EVALUATION"]["VALUE"] != "" && $arProps["EVALUATION"]["VALUE"] != "0") { ?>
                        <div class="feedback__block_top_star">

                            <? for ($i = 1; $i <= $arProps["EVALUATION"]["VALUE"]; ++$i) { ?>
                                <svg class="star star-active" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 47.94 47.94">
                                    <path
                                            d="M26.285 2.486l5.407 10.956a2.58 2.58 0 0 0 1.944 1.412l12.091 1.757c2.118.308 2.963 2.91 1.431 4.403l-8.749 8.528a2.582 2.582 0 0 0-.742 2.285l2.065 12.042c.362 2.109-1.852 3.717-3.746 2.722l-10.814-5.685a2.585 2.585 0 0 0-2.403 0l-10.814 5.685c-1.894.996-4.108-.613-3.746-2.722l2.065-12.042a2.582 2.582 0 0 0-.742-2.285L.783 21.014c-1.532-1.494-.687-4.096 1.431-4.403l12.091-1.757a2.58 2.58 0 0 0 1.944-1.412l5.407-10.956c.946-1.919 3.682-1.919 4.629 0z"
                                        <? if ($arProps["VERIFIED"]["VALUE"] == "") { ?>
                                            fill="#c5d2e0"
                                        <?php } elseif ($arProps["REJECTED"]["VALUE"] != "" && $arProps["VERIFIED"]["VALUE"] != "") { ?>
                                            fill="#3a4552"
                                        <? } elseif ($arProps["VERIFIED"]["VALUE"] != "") { ?>
                                            fill="#00abd8"
                                        <?php } ?>/>
                                </svg>
                            <? } ?>
                        </div>
                    <? } ?>
                    <div class="feedback__block_top_name">
                    <span class="feedback__block_top_name_text"><?= $name_user ?>, <?= $city["NAME"] ?>,
                        <?= $newDate ?></span>

                        <!-- Company Name -->
                        <div class="feedback__block_company-name">

                            <img src="<?= $file["src"] ?>">
                        </div>
                    </div>
                    <!--                <div class="feedback__block_top_data">-->
                    <!--                    05 сент, 2019-->
                    <!--                </div>-->
                </div>
                <!-- Title -->
                <a href="/feedback/comment-<?= $arFields["ID"] ?>/">
                    <h3 class="feedback__title"><?= $arFields["NAME"] ?></h3>
                </a>

                <!-- Text -->
                <div class="srolling--parent">
                    <p class="feedback__text readmore__parent"><?= $arProps["TEXT_MASSEGE"]["VALUE"] ?></p>
                </div>


                <!-- Bottom -->
                <div class="feedback__bottom">
                    <div class="feedback__bottom_name opacity_block"><?= $compani_fields["NAME"] ?></div>

                    <a id="show-comments" class="feedback__bottom_link opacity_block">
                        <img src="" alt="">

                        <span class="comment-count">
                        <?= $count_comments ?>
                    </span>
                        комментариев
                    </a>
                    <?
                    if ($USER->IsAuthorized()) { ?>
                        <a rel="nofollow" class="toggle_comment_dropdown opacity_block">Оставить комментарий</a>
                        <?
                    } ?>
                    <div class="block_commented_styles block__commented">
                        <form action="">
                            <div class="input__wrap">
                            <textarea minlength="10" name="comment" required
                                      data-id-comment="<?= $arFields["ID"] ?>"></textarea>
                            </div>
                            <div class="danger hidden">Заполните поле</div>
                            <button type="submit" class="smallMainBtn button-comments" id="comments"
                                    data-id-comment="<?= $arFields["ID"] ?>">Комментировать
                            </button>
                        </form>
                    </div>
                </div>
                <!-- COMMETNS -->
                <div class="hidenComments">
                    <?php
                    $ID_COMMENTS = $arProps["COMMENTS_TO_REWIEW"]["VALUE"]; /// комментарии
                    $order = Array("date_active_from" => "desc");

                    $arSelectComments = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
                    $arFilterComments = Array("IBLOCK_ID" => 14, "ACTIVE" => "Y", "ID" => $ID_COMMENTS);
                    $resComments = CIBlockElement::GetList($order, $arFilterComments, false, false, $arSelectComments);
                    while ($obComments = $resComments->GetNextElement()) {
                        $arFieldsComments = $obComments->GetFields();
                        $arPropsComments = $obComments->GetProperties();
                        $newDateComments = FormatDate("d F, Y", MakeTimeStamp($arFieldsComments["DATE_ACTIVE_FROM"]));

                        $rsUserComments = CUser::GetByID($arPropsComments["AVTOR_COMMENTS"]["VALUE"]);
                        $arUserComments = $rsUserComments->Fetch();
                        $name_userComments = $arUserComments["NAME"];

//                            $file_comment = CFile::ResizeImageGet($arUserComments["PERSONAL_PHOTO"],
//                                array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true);


                        ?>
                        <?php if ($USER->IsAdmin()) { ?>
                            <div class="block_remove">

                                <div data-id="<?php echo $arFieldsComments["ID"]; ?>"
                                     class="delet_comment remove_comment">Удалить
                                    комментарий
                                </div>
                            </div>
                        <?php } ?>
                        <div class="hidenComments__top">
                            <!--                    <img src="--><?php //echo $file_comment["src"] ?>
                            <!--" alt="OMS">-->


                            <? if ($arPropsComments["REPRESENTATIVE"]["VALUE"] == "1") { ?>
                                <div class="feedback_strah_user">
                                    <p class="text_user">Представитель страховой службы</p>
                                </div>
                            <? } ?>
                            <div class="hidenComments__top_wrap">
                                <div class="hidenComments__top_name"><?= $name_userComments ?></div>

                                <div class="hidenComments__top_data"><?= $newDateComments ?></div>
                            </div>

                        </div>

                        <p><?= $arPropsComments["COMMENTS"]["VALUE"] ?></p>
                        <? if ($USER->IsAuthorized()) { ?>
                            <? if ($arPropsComments["CITED"]["VALUE"] == "") { ?>
                                <!--                        <a id="show-comment" class="hidenComments__link" href="#">Цитировать</a>-->
                                <div class="block_commented_styles">
                                    <form action="">
                                        <div class="input__wrap">
                            <textarea minlength="10" name="cited" required
                                      data-id-cited="<?= $arFieldsComments["ID"] ?>"></textarea>
                                        </div>
                                        <div class="danger hidden">Заполните поле</div>
                                        <button type="submit" class="smallMainBtn button-cited" id="comments"
                                                data-id-cited="<?= $arFieldsComments["ID"] ?>">Цитировать
                                        </button>
                                    </form>
                                </div>
                            <? } ?>
                        <? } ?>
                        <!-- Цитаты-->
                        <?php if ($arPropsComments["CITED"]["VALUE"] != "") {  // цитаты к коментариям ?>
                            <div class="block_quotes">
                                <?php if ($USER->IsAdmin()) { ?>
                                    <div class="block_remove">
                                        <div class="delet_cation remove_comment"
                                             data-id="<?php echo $arFieldsQuote["ID"]; ?>">Удалить
                                            цитату
                                        </div>
                                    </div>
                                <?php } ?>
                                <?

                                $ID_Quote = $arPropsComments["CITED"]["VALUE"];
                                $arSelectQuote = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
                                $arFilterQuote = Array("IBLOCK_ID" => 15, "ACTIVE" => "Y", "ID" => $ID_Quote);
                                $resQuote = CIBlockElement::GetList(false, $arFilterQuote, false, false,
                                    $arSelectQuote);
                                while ($obQuote = $resQuote->GetNextElement()) {
                                    $arFieldsQuote = $obQuote->GetFields();
                                    $arPropsQuote = $obQuote->GetProperties();
                                    $newDateQuote = FormatDate("d F, Y",
                                        MakeTimeStamp($arFieldsQuote["DATE_ACTIVE_FROM"]));
                                    $ID_USERQuote = $arPropsQuote["AVTOR_CIATION"]["VALUE"];
                                    $rsUserQuote = CUser::GetByID($ID_USERQuote);
                                    $arUserQuote = $rsUserQuote->Fetch();
                                    $name_userQuote = $arUserQuote["NAME"];
//                                    $file_quote = CFile::ResizeImageGet($arUserQuote["PERSONAL_PHOTO"],
//                                        array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true);

                                    ?>
                                    <div class="hidenComments__top">

                                        <!--                        <img src="--><?php //echo $file_quote["src"]; ?>
                                        <!--" alt="OMS">-->


                                        <? if ($arPropsQuote["REPRESENTATIVE"]["VALUE"] == "1") { ?>
                                            <div class="feedback_strah_user">
                                                <p class="text_user">Представитель страховой службы</p>
                                            </div>
                                        <? } ?>
                                        <div class="hidenComments__top_wrap">
                                            <div class="hidenComments__top_name"><?= $name_userQuote ?></div>
                                            <div class="hidenComments__top_data"><?= $newDateQuote ?></div>
                                        </div>


                                    </div>

                                    <p class="quotes_italic">« <?= $arPropsQuote["CIATION"]["VALUE"] ?> »</p>


                                <? } ?>

                            </div>
                        <? } ?>
                    <? } ?>
                </div>

                <?php if ($USER->IsAdmin()) { ?>
                    <div class="right_content-reviews">

                        <?php if (isset($sort_url["admin"])) { ?>

                            <div class="feedback__radio-btns">
                                <div id="checkbox-box_<?= $arFields["ID"]; ?>" class="wrap-chrckbox">
                                    <label class="check-label years" data-chec="Y">
                                        промодерирован <input name="years" type="checkbox" value="accepted">
                                        <span class="check-img"></span>
                                    </label>
                                    <label class="check-label years" data-chec="Y">
                                        не по теме <input name="years" type="checkbox" value="reject">
                                        <span class="check-img"></span>
                                    </label>
                                </div>
                            </div>

                            <div class="feedback__actions-btns">
                                <div class="feedback__actions-btns_btn mainBtn" onclick="check_review(this)"
                                     data-check-id="<?= $arFields["ID"]; ?>">
                                    Применить
                                </div>

                                <div class="accentBtnBorder dalete_review feedback__actions-btns_btn"
                                     title="Удалить отзыв"
                                     data-id="<?php echo $arFields["ID"]; ?>">
                                    Удалить отзыв
                                </div>
                            </div>

                        <? } ?>
                    </div>
                <?php } ?>

            </div><!-- FeedBack block END -->
        <? }


        if (!$sort_url["comments"] == "all") {
            $navStr = $res->GetPageNavStringEx($navComponentObject, "Страницы:", ".default",false,null);
            echo $navStr;
        }
        ?>
    </div>
 <?php if($is_elemnt === true){ ?>
    <div class="sidebar">
        <!-- First Sidebar block -->
        <div class="white_block">
            <?php if (isset($_GET["property_region"])) { ?>

                <h3 class="sidebar__item_title">Рейтинг страховых компаний (<?= $name_region; ?>)</h3>
            <?php } else { ?>
                <h3 class="sidebar__item_title">Рейтинг страховых компаний</h3>
            <?php } ?>

            <ul class="sidebar__item_lists scrollbar">
                <?php
                $arFilter = array(
                    "IBLOCK_ID" => 16,
                    "ACTIVE" => "Y",
                    "!PROPERTY_AMOUNT_STAR" => false,
                    "!PROPERTY_ALL_AMOUNT_STAR" => false,
                );
                if (isset($_GET["property_region"])) {
                    $arFilter += ["SECTION_ID" => $_GET["property_region"]];
                }
                $order = array();
                if (!isset($_GET["property_region"])) {
                    $order += ["PROPERTY_ALL_AMOUNT_STAR" => "desc"];
                    $order += ["name" => "asc"];
                } else {
                    $order += ["PROPERTY_AMOUNT_STAR" => "desc"];
                    $order += ["name" => "asc"];
                }
                $array_all_company = array();

                $elementselect = Array(
                    "ID",
                    "IBLOCK_ID",
                    "NAME",
                    "CODE",
                    "PROPERTY_AMOUNT_STAR",
                    "PROPERTY_KPP",
                    "PROPERTY_ALL_AMOUNT_STAR"
                );
                $Element_filter = CIBlockElement::GetList($order, $arFilter, false, false, $elementselect);
                $i = 0;
                if (isset($_GET["property_evaluation"]) || isset($_GET["property_name_company"]) || isset($_GET["property_kpp"])) {

                    while ($ob_element_filter = $Element_filter->GetNextElement()) {
                        $fields = $ob_element_filter->GetFields();

                        $result_search_kpp = array_search($fields["PROPERTY_KPP_VALUE"], $array_all_company);

                        if ($result_search_kpp === false) {

                            array_push($array_all_company, $fields["PROPERTY_KPP_VALUE"]);
                            ++$i;
                            $url_for_filter = "?";

                            foreach ($sort_url as $key => $filter) {
                                if ($key != "property_kpp") {
                                    $url_for_filter .= "$key=$filter&";
                                }
                            }
                            ?>
                            <li class="sidebar__item_lists_list list_numbered-items">
                                <span class="sidebar_count number"><?= $i ?></span>
                                <a href="<?= $url_for_filter ?>property_kpp=<?= $fields["PROPERTY_KPP_VALUE"] ?>"
                                   class="sidebar__item_lists_list_link" id="company" data-amount-star="
                                   <?php if (isset($_GET["property_region"])) {
                                    echo $fields["PROPERTY_AMOUNT_STAR_VALUE"];
                                } else {
                                    echo $fields["PROPERTY_ALL_AMOUNT_STAR_VALUE"];
                                } ?>

" data-id="<?= $fields["ID"] ?>">
                                    <?= $fields["NAME"] ?>
                                </a>
                                <span class="sidebar_count rating"><?php if (isset($_GET["property_region"])) {

                                        echo $fields["PROPERTY_AMOUNT_STAR_VALUE"];
                                    } else {

                                        echo $fields["PROPERTY_ALL_AMOUNT_STAR_VALUE"];
                                    } ?></span>
                            </li>
                        <? }
                    } ?>
                <? } else {
                    while ($ob_element_filter = $Element_filter->GetNextElement()) {
                        $fields = $ob_element_filter->GetFields();

                        $result_search_kpp = array_search($fields["PROPERTY_KPP_VALUE"], $array_all_company);
                        if ($result_search_kpp === false) {
                            ++$i;
                            array_push($array_all_company, $fields["PROPERTY_KPP_VALUE"]);

                            ?>

                            <li class="sidebar__item_lists_list list_numbered-items">
                                <span class="sidebar_count number"><?= $i ?></span>
                                <a title="<?= $fields["NAME"] ?>"
                                   href="?property_kpp=<?= $fields["PROPERTY_KPP_VALUE"] ?>"
                                   class="sidebar__item_lists_list_link" id="company"
                                   data-amount-star=" <?php if (isset($_GET["property_region"])) {

                                       echo $fields["PROPERTY_AMOUNT_STAR_VALUE"];
                                   } else {

                                       echo $fields["PROPERTY_ALL_AMOUNT_STAR_VALUE"];
                                   } ?>" data-id="<?= $fields["ID"] ?>">
                                    <?= $fields["NAME"] ?>
                                </a>
                                <span class="sidebar_count rating"><?php if (isset($_GET["property_region"])) {

                                        echo $fields["PROPERTY_AMOUNT_STAR_VALUE"];
                                    } else {

                                        echo $fields["PROPERTY_ALL_AMOUNT_STAR_VALUE"];
                                    } ?></span>
                            </li>
                        <? }
                    }
                } ?>


            </ul>

            <div class="center__child">
                <a class="mainBtn" href="/feedback/">Весь рейтинг</a>
            </div>
        </div>


    </div>
    <?php } ?>
</div>
<?php $arSelect = Array("ID", "IBLOCK_ID", "NAME","PREVIEW_TEXT");
$arFilter = Array("IBLOCK_CODE"=>"feedback" );
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
if($ob = $res->GetNextElement()){
    $arProps = $ob->GetFields();?>

    <div class="info_block white_block">

     <?php echo $arProps["~PREVIEW_TEXT"]; ?>
    </div>
<?} ?>



<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>

<!-- Second Sidebar block -->
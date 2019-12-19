<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/feedback/main.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/feedback/main.min.js");

CModule::IncludeModule("iblock");
global $USER;


$sort_url = $_GET;

?>
<!-- Breadcrumbs -->
<ul class="breadcrumbs">
    <li>Главная</li>
    <li>Отзывы</li>
</ul>

<!-- Pages Title -->
<h2 class="page-title">Отзывы о страховых компаниях</h2>

<!-- ALL STEPS IN FORM -->
<form action="">

    <!-- Add and All feedback btns -->
    <div class="feedback__btns">
        <a href="/add-feedback/" class="smallMainBtn">Добавить отзыв</a>
        <a href="?comments=all" class="smallAccentBtn">Все отзывы</a>
    </div>

    <div class="feedback__filter">
        <div class="custom-select">
            <select style="display: none">

                <?
                $order = Array("name" => "asc");
                $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM");
                $arFilter = Array("IBLOCK_ID" => 16);
                $res = CIBlockElement::GetList($order, $arFilter, false, false, $arSelect);
                $i = 1;
                while ($ob = $res->GetNextElement()) {
                    $arProps = $ob->GetFields();
                    if ($i == 1) {
                        ?>
                        <option value="">Все отзывы <span><?= $res->SelectedRowsCount() ?></span></option>
                    <? } else {
                        ?>
                        <option value="?sort=company&filterby=PROPERTY_NAME_COMPANY&filterorder=<?= $arProps["ID"] ?>"><?= $arProps["NAME"] ?></option>
                    <? } ?>
                    <? ++$i;
                } ?>
            </select>
        </div>

        <div class="custom-select">
            <select style="display: none" onchange="window.open(this.value)">

                <option value="0">Оценка <?
                    if(isset($_GET["property_evaluation"])){
                        echo $_GET["property_evaluation"];
                    } ?></option>
                <?php if (isset($_GET["property_evaluation"]) || isset($_GET["property_name_company"]) || isset($_GET["property_region"])) {
                    $url_for_filter = "?";

                    foreach ($sort_url as $key => $filter) {
                        if ($key != "property_evaluation") {
                            $url_for_filter .= "$key=$filter&";
                        }
                    }

                    for ($i = 1; $i <= 5; ++$i) {
                        ?>
                        <option value="<?= $url_for_filter ?>property_evaluation=<?= $i ?>" class="number_star">
                            Оценки <?= $i ?>
                        </option>
                    <?php }
                    ?>
                <? } else {
                    for ($i = 1; $i <= 5; ++$i) {
                        ?>

                        <option value="?property_evaluation=<?= $i ?>" class="number_star">Оценки <?= $i ?>
                        </option>
                    <? }
                } ?>


            </select>
        </div>

        <div class="reset_block">
            <a class="smallAccentBtn" href="/feedback/">Сбросить</a>
        </div>
    </div>

</form>

<!-- Wrap -->
<div class="feedback">
    <div class="feedback__wrap_white-blocks">
        <!-- FeedBack block -->

        <?php
        $fiterby = "";
        $filterorder = "";
        $arFilter = Array(
            "IBLOCK_ID" => 13,
            "ACTIVE" => "Y",
        );

        foreach ($sort_url as $key => $filter) {
            $key = mb_strtoupper($key);
            $arFilter += [$key => $filter];
        }

        $order = Array("created" => "desc");
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");


        $pagen = Array("nPageSize" => 10);
        if ($sort_url["comments"] == "all") {
            $pagen = false;
        }
        $res = CIBlockElement::GetList($order, $arFilter, false, $pagen, $arSelect);
        if (!$sort_url["comments"] == "all") {
            $res->NavStart(0);
        }
        while ($ob = $res->GetNextElement()) {

            $arFields = $ob->GetFields();

            $arProps = $ob->GetProperties();

            $newDate = FormatDate("d F, Y", MakeTimeStamp($arFields["DATE_ACTIVE_FROM"]));
            $ID_USER = $arProps["NAME_USER"]["VALUE"];
            $rsUser = CUser::GetByID($ID_USER);
            $arUser = $rsUser->Fetch();


            $name_user = $arUser["NAME"];
            if (is_array($arProps["COMMENTS_TO_REWIEW"]["VALUE"])) {
                $count_comments = count($arProps["COMMENTS_TO_REWIEW"]["VALUE"]);
            } else {
                $count_comments = 0;
            }
            $city = CIBlockSection::GetByID($arProps["REGION"]["VALUE"])->GetNext();
            /* владик */
            $compani = CIBlockElement::GetByID($arProps["NAME_COMPANY"]["VALUE"])->GetNextElement()->GetProperties();

            $file = CFile::ResizeImageGet($compani["LOGO_IMG"]["VALUE"], array('width' => 100, 'height' => 100),
                BX_RESIZE_IMAGE_PROPORTIONAL, true);

            ?>
            <div class="white_block">
                <!-- Company Name -->
                
                <div class="feedback__block_company-name"><img src="<?= $file["src"] ?>"></div>
                <!-- top -->
                <div class="feedback__block_top">
                    <div class="feedback__block_top_star">

                        <? for ($i = 1; $i <= $arProps["EVALUATION"]["VALUE"]; ++$i) { ?>
                            <svg class="star star-active" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 47.94 47.94">
                                <path
                                        d="M26.285 2.486l5.407 10.956a2.58 2.58 0 0 0 1.944 1.412l12.091 1.757c2.118.308 2.963 2.91 1.431 4.403l-8.749 8.528a2.582 2.582 0 0 0-.742 2.285l2.065 12.042c.362 2.109-1.852 3.717-3.746 2.722l-10.814-5.685a2.585 2.585 0 0 0-2.403 0l-10.814 5.685c-1.894.996-4.108-.613-3.746-2.722l2.065-12.042a2.582 2.582 0 0 0-.742-2.285L.783 21.014c-1.532-1.494-.687-4.096 1.431-4.403l12.091-1.757a2.58 2.58 0 0 0 1.944-1.412l5.407-10.956c.946-1.919 3.682-1.919 4.629 0z"
                                   <?if($arProps["VERIFIED"]["VALUE"] == ""){?>
                                       fill="#c5d2e0"
                                   <?php }elseif($arProps["REJECTED"]["VALUE"] != "" && $arProps["VERIFIED"]["VALUE"] != ""){?>
                                       fill="#3a4552"
                                   <?}elseif($arProps["VERIFIED"]["VALUE"] !=""){ ?>
                                       fill="#ed8a19"
                                   <?php } ?>/>
                            </svg>



                        <? } ?>
                    </div>
                    <div class="feedback__block_top_name">
                        <?= $name_user ?>, <?= $city["NAME"] ?>, <?= $newDate ?>
                    </div>


                </div>
                <!-- Title -->
                <div class="feedback__title">
                    <a href="/feedback/comment-<?=$arFields["ID"]?>/">
                    <?= $arFields["NAME"] ?>
                    </a>
                </div>

                <!-- Text -->
                <p class="feedback__text"><?= $arProps["TEXT_MASSEGE"]["VALUE"] ?></p>

                <!-- Bottom -->
                <div class="feedback__bottom">
                    <div class="feedback__bottom_name opacity_block">OMC</div>

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
                        ?>

                        <div class="hidenComments__top">
                            <img src="./local/templates/kdteam/images/svg/image_block_three.svg" alt="OMS">
                            <?if($arPropsComments["REPRESENTATIVE"]["VALUE"] == "1"){?>
                                <div class="feedback_strah_user">
                                    <p class="text_user">Представитель страховой службы</p>
                                </div>
                            <?}?>
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
                        <div class="block_quotes">
                            <?
                            if ($arPropsComments["CITED"]["VALUE"] != "") {  // цитаты к коментариям
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
                                    $name_userQuote = $arUserQuote["NAME"]; ?>
                                    <div class="hidenComments__top">

                                        <img src="./local/templates/kdteam/images/svg/image_block_three.svg" alt="OMS">
                                        <?if($arPropsQuote["REPRESENTATIVE"]["VALUE"] == "1"){?>
                                            <div class="feedback_strah_user">
                                                <p class="text_user">Представитель страховой службы</p>
                                            </div>
                                        <?}?>
                                        <div class="hidenComments__top_wrap">
                                            <div class="hidenComments__top_name"><?= $name_userQuote ?></div>
                                            <div class="hidenComments__top_data"><?= $newDateQuote ?></div>
                                        </div>
                                    </div>

                                    <p class="quotes_italic">« <?= $arPropsQuote["CIATION"]["VALUE"] ?> »</p>


                                <? } ?>
                            <? } ?>
                        </div>
                    <? } ?>
                </div>

            </div><!-- FeedBack block END -->
        <? }
        if (!$sort_url["comments"] == "all") {
            $navStr = $res->GetPageNavStringEx($navComponentObject, "Страницы:", ".default");
            echo $navStr;
        }
        ?>

    </div>

    <div class="sidebar">
        <!-- First Sidebar block -->
        <div class="white_block">
            <div class="sidebar__item_title">Народный Рейтинг Страховых</div>

            <ul class="sidebar__item_lists scrollbar">
                <?php
                $order = Array("PROPERTY_AMOUNT_STAR" => "desc", "name" => "asc");
                $elementselect = Array("ID", "IBLOCK_ID", "NAME", "CODE", "DATE_ACTIVE_FROM", "PROPERTY_AMOUNT_STAR");
                $arFilter = Array("IBLOCK_ID" => 16);
                $Element_filter = CIBlockElement::GetList($order, $arFilter, false, false, $elementselect);

                if (isset($_GET["property_evaluation"]) || isset($_GET["property_name_company"]) || isset($_GET["property_region"])) {
                    while ($ob_element_filter = $Element_filter->GetNextElement()) {
                        $fields = $ob_element_filter->GetFields();
                        if ($fields["PROPERTY_AMOUNT_STAR_VALUE"] == "" || $fields["PROPERTY_AMOUNT_STAR_VALUE"] == 0) {
                            continue;
                        }
                        $url_for_filter = "?";

                        foreach ($sort_url as $key => $filter) {
                            if ($key != "property_name_company") {
                                $url_for_filter .= "$key=$filter&";
                            }
                        }
                        ?>
                        <li class="sidebar__item_lists_list">
                            <a href="<?= $url_for_filter ?>property_name_company=<?= $fields["ID"] ?>"
                               class="sidebar__item_lists_list_link" id="company"
                               data-amount-star="<?= $fields["PROPERTY_AMOUNT_STAR_VALUE"] ?>"
                               data-id="<?= $fields["ID"] ?>">
                                <?= $fields["NAME"] ?>
                            </a>
                        </li>
                    <? } ?>
                <? } else {
                    while ($ob_element_filter = $Element_filter->GetNextElement()) {
                        $fields = $ob_element_filter->GetFields();
                        if ($fields["PROPERTY_AMOUNT_STAR_VALUE"] == "" || $fields["PROPERTY_AMOUNT_STAR_VALUE"] == 0) {
                            continue;
                        }

                        ?>
                        <li class="sidebar__item_lists_list">
                            <a href="?property_name_company=<?= $fields["ID"] ?>" class="sidebar__item_lists_list_link"
                               id="company"
                               data-amount-star="<?= $fields["PROPERTY_AMOUNT_STAR_VALUE"] ?>"
                               data-id="<?= $fields["ID"] ?>">
                                <?= $fields["NAME"] ?>
                            </a>
                        </li>
                    <? } ?>

                <? } ?>


                <ul>

                    <button class="smallAccentBtn">Весь рейтинг</button>
        </div>

        <!-- Second Sidebar block -->
        <div class="white_block">
            <div class="sidebar__item_title">Отзывы о страховых в городах</div>

            <ul class="sidebar__item_lists scrollbar">
                <?php


                $order = Array("name" => "asc");
                $arFilter = Array("IBLOCK_ID" => 16);
                $Section_filter = CIBlockSection::GetList($order, $arFilter, false);
                if (isset($_GET["property_evaluation"]) || isset($_GET["property_name_company"]) || isset($_GET["property_region"])) {
                    while ($ob_section_filter = $Section_filter->GetNext()) {
                $url_for_filter = "?";

                foreach ($sort_url as $key => $filter) {
                    if ($key != "property_region") {
                        $url_for_filter .= "$key=$filter&";
                    }
                }
                ?>
                    <li class="sidebar__item_lists_list">
                        <a href="<?=$url_for_filter?>property_region=<?= $ob_section_filter["ID"] ?>"
                           class="sidebar__item_lists_list_link" id="city" data-id="<?= $ob_section_filter["ID"] ?>">
                            <?= $ob_section_filter["NAME"] ?>
                        </a>
                    </li>
                <? } ?>
                <? } else {
                    while ($ob_section_filter = $Section_filter->GetNext()) {
                ?>
                        <li class="sidebar__item_lists_list">
                            <a href="?property_region=<?= $ob_section_filter["ID"] ?>"
                               class="sidebar__item_lists_list_link" id="city" data-id="<?= $ob_section_filter["ID"] ?>">
                                <?= $ob_section_filter["NAME"] ?>
                            </a>
                        </li>
                <? } ?>

                <? } ?>














            </ul>

            <button class="smallAccentBtn">Все отзывы</button>
        </div>
    </div>
</div>


<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>


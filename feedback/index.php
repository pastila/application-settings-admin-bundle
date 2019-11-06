<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/feedback/main.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/feedback/main.min.js");

CModule::IncludeModule("iblock");
global $USER;

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
        <a href="/add-feedback.html" class="smallMainBtn">Добавить отзыв</a>
        <a href="#" class="smallAccentBtn">Все отзывы</a>
    </div>

    <div class="feedback__filter">
        <div class="custom-select">
            <select>
                <option value="0">Все отзывы <span>( 512 )</span></option>
                <option value="1">Амурская область Больница #1</option>
                <option value="2">Архангельская область Больница #122132</option>
                <option value="3">Астраханская область Больница #123423446546</option>
                <option value="4">Белгородская область Больница #234234456654</option>
                <option value="5">Брянская область Больница #34576587678</option>
                <option value="6">Владимирская область #34576587678</option>
            </select>
        </div>

        <div class="custom-select">
            <select>
                <option value="0">Оценка</span></option>
                <option value="1">Оценки 5</option>
                <option value="2">Оценки 4</option>
                <option value="3">Оценки 3</option>
                <option value="4">Оценки 2</option>
                <option value="5">Оценки 1</option>
            </select>
        </div>
    </div>
</form>

<!-- Wrap -->
<div class="feedback">
    <div class="feedback__wrap_white-blocks">
        <!-- FeedBack block -->

        <?php
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
        $arFilter = Array("IBLOCK_ID" => 13, "ACTIVE" => "Y");
        $res = CIBlockElement::GetList(false, $arFilter, false, false, $arSelect);
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


            ?>
            <div class="white_block">
                <!-- Company Name -->
                <div class="feedback__block_company-name"><?= $arProps["NAME_COMPANY"]["VALUE"] ?></div>
                <!-- top -->
                <div class="feedback__block_top">
                    <div class="feedback__block_top_star">
                        <? for ($i = 1; $i <= $arProps["EVALUATION"]["VALUE"]; ++$i) { ?>
                            <svg class="star star-active" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 47.94 47.94">
                                <path
                                        d="M26.285 2.486l5.407 10.956a2.58 2.58 0 0 0 1.944 1.412l12.091 1.757c2.118.308 2.963 2.91 1.431 4.403l-8.749 8.528a2.582 2.582 0 0 0-.742 2.285l2.065 12.042c.362 2.109-1.852 3.717-3.746 2.722l-10.814-5.685a2.585 2.585 0 0 0-2.403 0l-10.814 5.685c-1.894.996-4.108-.613-3.746-2.722l2.065-12.042a2.582 2.582 0 0 0-.742-2.285L.783 21.014c-1.532-1.494-.687-4.096 1.431-4.403l12.091-1.757a2.58 2.58 0 0 0 1.944-1.412l5.407-10.956c.946-1.919 3.682-1.919 4.629 0z"
                                        fill="#ed8a19"/>
                            </svg>
                        <? } ?>
                    </div>
                    <div class="feedback__block_top_name">
                        <?= $name_user ?>, <?= $arProps["REGION"]['VALUE'] ?>, <?= $newDate?>
                    </div>
                    <!--                <div class="feedback__block_top_data">-->
                    <!--                    05 сент, 2019-->
                    <!--                </div>-->
                </div>
                <!-- Title -->
                <div class="feedback__title">
                    <?= $arFields["NAME"] ?>
                </div>

                <!-- Text -->
                <p class="feedback__text"><?= $arProps["TEXT_MASSEGE"]["VALUE"] ?></p>

                <!-- Bottom -->
                <div class="feedback__bottom">
                    <div class="feedback__bottom_name">OMC</div>

                    <a id="show-comments" class="feedback__bottom_link">
                        <img src="" alt="">

                        <span class="comment-count">
                    <?= $count_comments ?>
                                </span>
                        комментариев
                    </a>
                </div>
                <!-- COMMETNS -->
                <div class="hidenComments">
                    <?php
                    $ID_COMMENTS = $arProps["COMMENTS_TO_REWIEW"]["VALUE"];
                    $order = Array("date_active_from" => "desc");
                    $arSelectComments = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
                    $arFilterComments = Array("IBLOCK_ID" => 14, "ACTIVE" => "Y", "ID" => $ID_COMMENTS);
                    $resComments = CIBlockElement::GetList($order, $arFilterComments, false, false, $arSelectComments);
                    while ($obComments = $resComments->GetNextElement()) {
                        $arFieldsComments = $obComments->GetFields();
                        $arPropsComments = $obComments->GetProperties();
                        $newDateComments = FormatDate("d F, Y", MakeTimeStamp($arFieldsComments["DATE_ACTIVE_FROM"]));
                        $ID_USERComments = $arPropsComments["AVTOR_COMMENTS"]["VALUE"];
                        $rsUserComments = CUser::GetByID($ID_USERComments);
                        $arUserComments = $rsUserComments->Fetch();
                        $name_userComments = $arUserComments["NAME"];
                        ?>

                        <div class="hidenComments__top">
                            <img src="./local/templates/kdteam/images/svg/image_block_three.svg" alt="OMS">

                            <div class="hidenComments__top_wrap">
                                <div class="hidenComments__top_name"><?= $name_userComments ?></div>

                                <div class="hidenComments__top_data"><?= $newDateComments ?></div>
                            </div>
                        </div>

                        <p><?= $arPropsComments["COMMENTS"]["VALUE"] ?></p>

                        <a id="show-comment" class="hidenComments__link" href="#">Цитировать</a>


                         <!-- Цитаты-->
                        <?
                        if ($arPropsComments["CITED"]["VALUE"] != "") {
                            $ID_Quote = $arPropsComments["CITED"]["VALUE"];
                            $arSelectQuote = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
                            $arFilterQuote = Array("IBLOCK_ID" => 15, "ACTIVE" => "Y", "ID" => $ID_Quote);
                            $resQuote = CIBlockElement::GetList(false, $arFilterQuote, false, false, $arSelectQuote);
                            while ($obQuote = $resQuote->GetNextElement()) {
                                $arFieldsQuote = $obQuote->GetFields();
                                $arPropsQuote = $obQuote->GetProperties();
                                $newDateQuote = FormatDate("d F, Y", MakeTimeStamp($arFieldsQuote["DATE_ACTIVE_FROM"]));
                                $ID_USERQuote = $arPropsQuote["AVTOR_CIATION"]["VALUE"];
                                $rsUserQuote = CUser::GetByID($ID_USERQuote);
                                $arUserQuote = $rsUserQuote->Fetch();
                                $name_userQuote = $arUserQuote["NAME"]; ?>
                                <div class="hidenComments__top">

                                    <img src="./local/templates/kdteam/images/svg/image_block_three.svg" alt="OMS">

                                    <div class="hidenComments__top_wrap">
                                        <div class="hidenComments__top_name"><?= $name_userQuote ?></div>
                                        <div class="hidenComments__top_data"><?= $newDateQuote ?></div>
                                    </div>
                                </div>

                                <p><?= $arPropsQuote["CIATION"]["VALUE"] ?></p>

                                <a id="show-comment" class="hidenComments__link" href="#">Цитата</a>
                            <? } ?>
                        <? } ?>
                    <? } ?>
                </div>

            </div><!-- FeedBack block END -->
        <? } ?>
    </div>

    <div class="sidebar">
        <!-- First Sidebar block -->
        <div class="white_block">
            <div class="sidebar__item_title">Народный Рейтинг Страховых</div>

            <ul class="sidebar__item_lists">
                <li class="sidebar__item_lists_list">
                    <a href="#" class="sidebar__item_lists_list_link">
                        ВТБ Страхование
                    </a>
                </li>

                <li class="sidebar__item_lists_list">
                    <a href="#" class="sidebar__item_lists_list_link">
                        ВТБ Страхование
                    </a>
                </li>

                <li class="sidebar__item_lists_list">
                    <a href="#" class="sidebar__item_lists_list_link">
                        ВТБ Страхование
                    </a>
                </li>

                <li class="sidebar__item_lists_list">
                    <a href="#" class="sidebar__item_lists_list_link">
                        ВТБ Страхование
                    </a>
                </li>

                <li class="sidebar__item_lists_list">
                    <a href="#" class="sidebar__item_lists_list_link">
                        ВТБ Страхование
                    </a>
                </li>
            </ul>

            <button class="smallAccentBtn">Весь рейтинг</button>
        </div>

        <!-- Second Sidebar block -->
        <div class="white_block">
            <div class="sidebar__item_title">Отзывы о страховых в городах</div>

            <ul class="sidebar__item_lists">
                <li class="sidebar__item_lists_list">
                    <a href="#" class="sidebar__item_lists_list_link">
                        Москва
                    </a>
                </li>

                <li class="sidebar__item_lists_list">
                    <a href="#" class="sidebar__item_lists_list_link">
                        Санкт-Петербург
                    </a>
                </li>

                <li class="sidebar__item_lists_list">
                    <a href="#" class="sidebar__item_lists_list_link">
                        Новосибирск
                    </a>
                </li>

                <li class="sidebar__item_lists_list">
                    <a href="#" class="sidebar__item_lists_list_link">
                        Екатеринбург
                    </a>
                </li>

                <li class="sidebar__item_lists_list">
                    <a href="#" class="sidebar__item_lists_list_link">
                        Нижний Новгород
                    </a>
                </li>

                <li class="sidebar__item_lists_list">
                    <a href="#" class="sidebar__item_lists_list_link">
                        Казань
                    </a>
                </li>

                <li class="sidebar__item_lists_list">
                    <a href="#" class="sidebar__item_lists_list_link">
                        Самара
                    </a>
                </li>

                <li class="sidebar__item_lists_list">
                    <a href="#" class="sidebar__item_lists_list_link">
                        Омск
                    </a>
                </li>

                <li class="sidebar__item_lists_list">
                    <a href="#" class="sidebar__item_lists_list_link">
                        Челябинск
                    </a>
                </li>

                <li class="sidebar__item_lists_list">
                    <a href="#" class="sidebar__item_lists_list_link">
                        Ростов-на-Дону
                    </a>
                </li>
            </ul>

            <button class="smallAccentBtn">Все отзывы</button>
        </div>
    </div>
</div>


<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>


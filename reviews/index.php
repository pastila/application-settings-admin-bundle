<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
global $USER;
if (!$USER->IsAuthorized()) {
    LocalRedirect("/", false, "301 Moved permanently");
}

use Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/reviews/reviews.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/reviews/reviews.min.js");
$asset->addJs(SITE_TEMPLATE_PATH . "/js/readmore.min.js");

$rsUser = CUser::GetByID($USER->GetID());
$arUser = $rsUser->Fetch();

?>
<ul class="breadcrumbs">
    <? if ($detect->isTablet() || $detect->isMobile()) { ?>
    <li><a href="/" class="active-breadcrumbs">Ваши отзывы</a></li>
    <? } else { ?>
    <li><a href="/">Главная</a></li>
    <li class="active-breadcrumbs">Ваши отзывы</li>
    <? } ?>

</ul>

<?php if($arUser["UF_REPRESENTATIVE"] != "1"){ ?>
    <div class="grid-reviews">
        <div class="grid-reviews__left">
            <h1 class="page-title">Ваши отзывы</h1>
        </div>
        <div class="grid-reviews__right">
            <a href="/feedback/" class="mainBtn">Все отзывы</a>
        </div>
    </div>
<div class="reviews">
    <div class="reviews__wrap_white-blocks">
        <!-- FeedBack block -->

        <?php


            $order = Array("created" => "desc");
            $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","CREATED_DATE", "PROPERTY_*");
            $arFilter = Array("IBLOCK_ID" => 13, "ACTIVE" => "Y", "PROPERTY_NAME_USER" => $USER->GetID());

            $pagen = Array("nPageSize" => 5);
            if ($sort_url["comments"] == "all") {
                $pagen = false;
            }
        if(!isset($_GET["PAGEN_1"])){
            $pagen["iNumPage"] = 1;
        }
            $res = CIBlockElement::GetList($order, $arFilter, false, $pagen, $arSelect);
            if (!$sort_url["comments"] == "all") {
                $res->NavStart(0);
            }
            while ($ob = $res->GetNextElement()) {
                $Date_change_user ="";
                $arFields = $ob->GetFields();
                $arProps = $ob->GetProperties();
                $newdata = explode(".",$arFields["CREATED_DATE"]);
                $newstrDate = $newdata[2].'.' . $newdata[1].'.' .$newdata[0];

                $newDate = FormatDate("d F, Y", MakeTimeStamp($newstrDate));

                if($arProps["DATE_CHANGE_BY_USER"]["VALUE"] != "") {
                    $Date_change_user = FormatDate("d F, Y", MakeTimeStamp($arProps["DATE_CHANGE_BY_USER"]["VALUE"]));
                } else {

                    $Date_change_user = "";
                }

                if ($arProps["NAME_USER"]["VALUE"] == ""){
                    $name_user = $arProps["USER_NO_AUTH"]["VALUE"];
                }else{
                    $ID_USER = $arProps["NAME_USER"]["VALUE"];
                    $rsUser = CUser::GetByID($ID_USER);
                    $arUser = $rsUser->Fetch();
                    $name_user = $arUser["NAME"];
                }
                if (is_array($arProps["COMMENTS_TO_REWIEW"]["VALUE"])) {
                    $count_comments = count($arProps["COMMENTS_TO_REWIEW"]["VALUE"]);
                } else {
                    $count_comments = 0;
                }
                $city = CIBlockSection::GetByID($arProps["REGION"]["VALUE"])->GetNext();
                /* владик */
                $compani = CIBlockElement::GetByID($arProps["NAME_COMPANY"]["VALUE"])->GetNextElement()->GetProperties();

                $file = CFile::ResizeImageGet($compani["LOGO_IMG"]["VALUE"], array('width' => 120, 'height' => 80),
                    BX_RESIZE_IMAGE_PROPORTIONAL, true);

                ?>
        <div class="white_block" data-id-review="<?=$arFields["ID"]?>">
            <?php if($Date_change_user != ""){ ?>
            <span class="date_review">Дата изменения <?php echo $Date_change_user; ?></span>
            <?php } ?>
            <!-- Company Name -->
            <div class="feedback__block_company-name"><img src="<?= $file["src"] ?>"></div>




            <!-- top -->
            <div class="feedback__block_top">
                <?php if($arProps["EVALUATION"]["VALUE"] != "" && $arProps["EVALUATION"]["VALUE"] != "0") {?>
                <div class="feedback__block_top_star" data-block-star="<?=$arFields["ID"]?>">
                    <? for ($i = 1; $i <= $arProps["EVALUATION"]["VALUE"]; ++$i) { ?>
                    <svg class="star star-active" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 47.94 47.94"
                        data-star="<?=$arFields["ID"]?>">
                        <path
                            d="M26.285 2.486l5.407 10.956a2.58 2.58 0 0 0 1.944 1.412l12.091 1.757c2.118.308 2.963 2.91 1.431 4.403l-8.749 8.528a2.582 2.582 0 0 0-.742 2.285l2.065 12.042c.362 2.109-1.852 3.717-3.746 2.722l-10.814-5.685a2.585 2.585 0 0 0-2.403 0l-10.814 5.685c-1.894.996-4.108-.613-3.746-2.722l2.065-12.042a2.582 2.582 0 0 0-.742-2.285L.783 21.014c-1.532-1.494-.687-4.096 1.431-4.403l12.091-1.757a2.58 2.58 0 0 0 1.944-1.412l5.407-10.956c.946-1.919 3.682-1.919 4.629 0z"
                            <?if($arProps["VERIFIED"]["VALUE"]=="" ){?>
                            fill="#c5d2e0"
                            <?php }elseif($arProps["REJECTED"]["VALUE"] != "" && $arProps["VERIFIED"]["VALUE"] != ""){?>
                            fill="#3a4552"
                            <?}elseif($arProps["VERIFIED"]["VALUE"] !=""){ ?>
                            fill="#00abd8"
                            <?php } ?>/>
                    </svg>
                    <? } ?>
                </div>
                <?php } ?>
                <div class="feedback__block_top_name">
                    <?= $name_user ?>, <?= $city["NAME"] ?>, <?= $newDate ?>
                </div>

                <!--                <div class="feedback__block_top_data">-->
                <!--                    05 сент, 2019-->
                <!--                </div>-->

            </div>
            <!-- Title -->
                <a href="/reviews/comment-<?=$arFields["ID"]?>/">
                    <h3 class="feedback__title"><?= $arFields["NAME"] ?></h3>
                </a>

            <!-- Text -->
            <div class="srolling--parent">
                <p class="feedback__text readmore__parent"><?= $arProps["TEXT_MASSEGE"]["VALUE"] ?></p>
            </div>
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
            </div>
            <div class="feedback__bottom bottom_change-block">
                <span class="feedback_change_star" data-id-rewiev="<?=$arFields["ID"]?>">Редактировать оценку</span>
            </div>
            <form action="" id="form_change_count_star" data-form-id="<?=$arFields["ID"]?>"
                class="hidden form-change_star">
                <div class='rating-stars text-center' data-select="star">
                    <ul id='stars'>
                        <li class='star' title='Плохо' data-star-id="<?=$arFields["ID"]?>" data-value='1'>
                            <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star' title='Неплохо' data-star-id="<?=$arFields["ID"]?>" data-value='2'>
                            <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star' title='Хорошо' data-star-id="<?=$arFields["ID"]?>" data-value='3'>
                            <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star' title='Прекрасно' data-star-id="<?=$arFields["ID"]?>" data-value='4'>
                            <i class='fa fa-star fa-fw'></i>
                        </li>
                        <li class='star' title='Отлично!' data-star-id="<?=$arFields["ID"]?>" data-value='5'>
                            <i class='fa fa-star fa-fw'></i>
                        </li>
                    </ul>
                </div>
                <button type="submit" class="change_count_star smallMainBtn"
                    data-id-rewiev="<?=$arFields["ID"]?>">Сохранить</button>
            </form>
            <div class="result-styles" id="result_change hidden" data-result-id="<?=$arFields["ID"]?>"></div>

            <!-- COMMETNS -->
            <div class="hidenComments">
                <?php
                        $ID_COMMENTS = $arProps["COMMENTS_TO_REWIEW"]["VALUE"]; /// комментарии
                        $order = Array("date_active_from" => "desc");
                        $arSelectComments = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
                        $arFilterComments = Array("IBLOCK_ID" => 14, "ACTIVE" => "Y", "ID" => $ID_COMMENTS);
                        $resComments = CIBlockElement::GetList($order, $arFilterComments, false, false,
                            $arSelectComments);
                        while ($obComments = $resComments->GetNextElement()) {
                            $arFieldsComments = $obComments->GetFields();
                            $arPropsComments = $obComments->GetProperties();
                            $newDateComments = FormatDate("d F, Y",
                                MakeTimeStamp($arFieldsComments["DATE_ACTIVE_FROM"]));

                            $rsUserComments = CUser::GetByID($arPropsComments["AVTOR_COMMENTS"]["VALUE"]);
                            $arUserComments = $rsUserComments->Fetch();
                            $name_userComments = $arUserComments["NAME"];

//                            $file_comment = CFile::ResizeImageGet($arUserComments["PERSONAL_PHOTO"],
//                                array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                            ?>

                <div class="hidenComments__top">
<!--                    <img src="--><?php //echo $file_comment["src"] ?><!--" alt="OMS">-->
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

                            <!-- Цитаты-->

                            <?

                            if ($arPropsComments["CITED"]["VALUE"] != "") {  // цитаты к коментариям?>
                                <div class="block_quotes">
                                    <? $ID_Quote = $arPropsComments["CITED"]["VALUE"];
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
//                                        $file_quote = CFile::ResizeImageGet($arUserQuote["PERSONAL_PHOTO"],
//                                            array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                                        ?>
                    <div class="hidenComments__top">

<!--                                            <img src="--><?php //echo $file_quote["src"]; ?><!--"-->
<!--                                                 alt="OMS">-->
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

        </div><!-- FeedBack block END -->
        <? }
            if (!$sort_url["comments"] == "all") {
                $navStr = $res->GetPageNavStringEx($navComponentObject, "Страницы:", ".default");
                echo $navStr;
            }
if($amount == 0){
                ?>

    <h3 class="mb-2">Вы еще не оставили ни одного отзыва.</h3>
    <?
}
            ?>


    </div>


</div>
<? } elseif ($arUser["UF_REPRESENTATIVE"] == "1") { ?>

<h1 class="page-title">Отзывы о вашей компании</h1>
<div class="feedback">
    <!-- FeedBack block -->

    <?php

$order = Array("created" => "desc");
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","CREATED_DATE", "PROPERTY_*");
$arFilter = Array("IBLOCK_ID" => 13, "ACTIVE" => "Y", "PROPERTY_NAME_COMPANY" => $arUser["UF_INSURANCE_COMPANY"]);

$pagen = Array("nPageSize" => 5);
if ($sort_url["comments"] == "all") {
$pagen = false;
}
    if(!isset($_GET["PAGEN_2"])){
        $pagen["iNumPage"] = 1;
    }
$res = CIBlockElement::GetList($order, $arFilter, false, $pagen, $arSelect);
if (!$sort_url["comments"] == "all") {
$res->NavStart(0);
}
while ($ob = $res->GetNextElement()) {
$Date_change_user ="";
$arFields = $ob->GetFields();
$arProps = $ob->GetProperties();
    $newdata = explode(".",$arFields["CREATED_DATE"]);
    $newstrDate = $newdata[2].'.' . $newdata[1].'.' .$newdata[0];

    $newDate = FormatDate("d F, Y", MakeTimeStamp($newstrDate));
if($arProps["DATE_CHANGE_BY_USER"]["VALUE"] != "") {
 $Date_change_user = FormatDate("d F, Y", MakeTimeStamp($arProps["DATE_CHANGE_BY_USER"]["VALUE"]));
}else{

 $Date_change_user =  "";
}
    if ($arProps["NAME_USER"]["VALUE"] == ""){
        $name_user = $arProps["USER_NO_AUTH"]["VALUE"];
    }else{
        $ID_USER = $arProps["NAME_USER"]["VALUE"];
        $rsUser = CUser::GetByID($ID_USER);
        $arUser = $rsUser->Fetch();
        $name_user = $arUser["NAME"];
    }
if (is_array($arProps["COMMENTS_TO_REWIEW"]["VALUE"])) {
 $count_comments = count($arProps["COMMENTS_TO_REWIEW"]["VALUE"]);
} else {
 $count_comments = 0;
}
$city = CIBlockSection::GetByID($arProps["REGION"]["VALUE"])->GetNext();
/* владик */
$compani = CIBlockElement::GetByID($arProps["NAME_COMPANY"]["VALUE"])->GetNextElement()->GetProperties();

$file = CFile::ResizeImageGet($compani["LOGO_IMG"]["VALUE"], array('width' => 120, 'height' => 80),
 BX_RESIZE_IMAGE_PROPORTIONAL, true);

?>
    <div class="white_block">
        <?php if($Date_change_user != ""){ ?>
        <span class="date_review">Дата изменения <?php echo $Date_change_user; ?></span>
        <?php } ?>
        <!-- Company Name -->
        <div class="feedback__block_company-name"><img src="<?= $file["src"] ?>"></div>




        <!-- top -->
        <div class="feedback__block_top">
            <?php if($arProps["EVALUATION"]["VALUE"] != "" && $arProps["EVALUATION"]["VALUE"] != "0") {?>
            <div class="feedback__block_top_star" data-block-star="<?=$arFields["ID"]?>">
                <? for ($i = 1; $i <= $arProps["EVALUATION"]["VALUE"]; ++$i) { ?>
                <svg class="star star-active" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 47.94 47.94">
                    <path
                        d="M26.285 2.486l5.407 10.956a2.58 2.58 0 0 0 1.944 1.412l12.091 1.757c2.118.308 2.963 2.91 1.431 4.403l-8.749 8.528a2.582 2.582 0 0 0-.742 2.285l2.065 12.042c.362 2.109-1.852 3.717-3.746 2.722l-10.814-5.685a2.585 2.585 0 0 0-2.403 0l-10.814 5.685c-1.894.996-4.108-.613-3.746-2.722l2.065-12.042a2.582 2.582 0 0 0-.742-2.285L.783 21.014c-1.532-1.494-.687-4.096 1.431-4.403l12.091-1.757a2.58 2.58 0 0 0 1.944-1.412l5.407-10.956c.946-1.919 3.682-1.919 4.629 0z"
                        <?if($arProps["VERIFIED"]["VALUE"]=="" ){?>
                        fill="#c5d2e0"
                        <?php }elseif($arProps["REJECTED"]["VALUE"] != "" && $arProps["VERIFIED"]["VALUE"] != ""){?>
                        fill="#3a4552"
                        <?}elseif($arProps["VERIFIED"]["VALUE"] !=""){ ?>
                        fill="#00abd8"
                        <?php } ?> />
                </svg>
                <? } ?>
            </div>
            <? } ?>
            <div class="feedback__block_top_name">
                <?= $name_user ?>, <?= $city["NAME"] ?>, <?= $newDate ?>
            </div>

            <!--                <div class="feedback__block_top_data">-->
            <!--                    05 сент, 2019-->
            <!--                </div>-->

        </div>
        <!-- Title -->
        <a href="/reviews/comment-<?=$arFields["ID"]?>/">
            <h3 class="feedback__title"><?= $arFields["NAME"] ?></h3>
        </a>

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
        </div>



        <form action="" id="form_change_count_star" data-form-id="<?=$arFields["ID"]?>" class="hidden form-change_star">
            <div class='rating-stars text-center' data-select="star">
                <ul id='stars'>
                    <li class='star' title='Плохо' data-star-id="<?=$arFields["ID"]?>" data-value='1'>
                        <i class='fa fa-star fa-fw'></i>
                    </li>
                    <li class='star' title='Неплохо' data-star-id="<?=$arFields["ID"]?>" data-value='2'>
                        <i class='fa fa-star fa-fw'></i>
                    </li>
                    <li class='star' title='Хорошо' data-star-id="<?=$arFields["ID"]?>" data-value='3'>
                        <i class='fa fa-star fa-fw'></i>
                    </li>
                    <li class='star' title='Прекрасно' data-star-id="<?=$arFields["ID"]?>" data-value='4'>
                        <i class='fa fa-star fa-fw'></i>
                    </li>
                    <li class='star' title='Отлично!' data-star-id="<?=$arFields["ID"]?>" data-value='5'>
                        <i class='fa fa-star fa-fw'></i>
                    </li>
                </ul>
            </div>
            <button type="submit" class="change_count_star smallMainBtn"
                data-id-rewiev="<?=$arFields["ID"]?>">Сохранить</button>
        </form>
        <div class="result-styles" id="result_change hidden" data-result-id="<?=$arFields["ID"]?>"></div>

        <!-- COMMETNS -->
        <div class="hidenComments">
            <?php
     $ID_COMMENTS = $arProps["COMMENTS_TO_REWIEW"]["VALUE"]; /// комментарии
     $order = Array("date_active_from" => "desc");
     $arSelectComments = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
     $arFilterComments = Array("IBLOCK_ID" => 14, "ACTIVE" => "Y", "ID" => $ID_COMMENTS);
     $resComments = CIBlockElement::GetList($order, $arFilterComments, false, false,
         $arSelectComments);
     while ($obComments = $resComments->GetNextElement()) {
         $arFieldsComments = $obComments->GetFields();
         $arPropsComments = $obComments->GetProperties();
         $newDateComments = FormatDate("d F, Y",
             MakeTimeStamp($arFieldsComments["DATE_ACTIVE_FROM"]));

         $rsUserComments = CUser::GetByID($arPropsComments["AVTOR_COMMENTS"]["VALUE"]);
         $arUserComments = $rsUserComments->Fetch();
         $name_userComments = $arUserComments["NAME"];
//         $file_comment = CFile::ResizeImageGet($arUserComments["PERSONAL_PHOTO"], array('width'=>50, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
         ?>

            <div class="hidenComments__top">
<!--                <img src="--><?php //echo $file_comment["src"] ?><!--" alt="OMS">-->
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

                            <!-- Цитаты-->

                            <?
                            if ($arPropsComments["CITED"]["VALUE"] != "") {  // цитаты к коментариям
                                ?>
                                <div class="block_quotes"><?
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
//                                        $file_quote = CFile::ResizeImageGet($arUserQuote["PERSONAL_PHOTO"],
//                                            array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                                        ?>
                                        <div class="hidenComments__top">

<!--                                            <img src="--><?php //echo $file_quote["src"]; ?><!--"-->
<!--                                                 alt="OMS">-->
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

                </div><!-- FeedBack block END -->
            <? }
            if (!$sort_url["comments"] == "all") {
                $navStr = $res->GetPageNavStringEx($navComponentObject, "Страницы:", ".default");
                echo $navStr;
            }
            ?>

        </div>


    </div>
<? } ?>

<?php

$order = Array("created" => "desc");
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM");
$arFilter = Array("IBLOCK_ID" => "15", "ACTIVE" => "Y", "PROPERTY_AVTOR_CIATIONS" => $USER->GetID());
$res = CIBlockElement::GetList($order, $arFilter, false, $pagen, $arSelect);

$ID_rewievs = array();
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();

    $ID_rewievs[] = $arFields["ID"];
}
if(isset($ID_rewievs[0])) {


    $ID_comments = array();
    $arSelect2 = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM");
    $arFilter2 = Array(
        "IBLOCK_ID" => 14,
        array("LOGIC" => "OR", "ID" => $ID_rewievs, "PROPERTY_AVTOR_COMMENTS" => $USER->GetID())
    );
    $res2 = CIBlockElement::GetList(Array(), $arFilter2, false, false, $arSelect2);
    while ($ob2 = $res2->GetNextElement()) {
        $arFields2 = $ob2->GetFields();

        $ID_comments[] = $arFields2["ID"];

    }




    $order = Array("created" => "desc");
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "CREATED_DATE", "PROPERTY_*");
    $arFilter = Array("IBLOCK_ID" => 13, "ACTIVE" => "Y", "PROPERTY_COMMENTS_TO_REWIEW" => $ID_comments);

    $pagen = Array("nPageSize" => 5);
    if ($sort_url["comments"] == "all") {
        $pagen = false;
    }
    if(!isset($_GET["PAGEN_3"])){
        $pagen["iNumPage"] = 1;
    }
    $res = CIBlockElement::GetList($order, $arFilter, false, $pagen, $arSelect);
    if (!$sort_url["comments"] == "all") {
        $res->NavStart(0);
    }
    if ($res->SelectedRowsCount() > 0) {
        ?>

        <h2 class="page-title">Ваша активность в обсуждениях</h2>
        <div class="feedback">
        <div class="feedback__wrap_white-blocks">
        <!-- FeedBack block -->

        <?
    }
    while ($ob = $res->GetNextElement()) {

        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();
        $newdata = explode(".", $arFields["CREATED_DATE"]);
        $newstrDate = $newdata[2] . '.' . $newdata[1] . '.' . $newdata[0];

        $newDate = FormatDate("d F, Y", MakeTimeStamp($newstrDate));
        if ($arProps["DATE_CHANGE_BY_USER"]["VALUE"] != "") {
            $Date_change_user = FormatDate("d F, Y", MakeTimeStamp($arProps["DATE_CHANGE_BY_USER"]["VALUE"]));
        } else {

            $Date_change_user = "";
        }
        if ($arProps["NAME_USER"]["VALUE"] == ""){
            $name_user = $arProps["USER_NO_AUTH"]["VALUE"];
        }else{
            $ID_USER = $arProps["NAME_USER"]["VALUE"];
            $rsUser = CUser::GetByID($ID_USER);
            $arUser = $rsUser->Fetch();
            $name_user = $arUser["NAME"];
        }
        if (is_array($arProps["COMMENTS_TO_REWIEW"]["VALUE"])) {
            $count_comments = count($arProps["COMMENTS_TO_REWIEW"]["VALUE"]);
        } else {
            $count_comments = 0;
        }
        $city = CIBlockSection::GetByID($arProps["REGION"]["VALUE"])->GetNext();
        /* владик */
        $compani = CIBlockElement::GetByID($arProps["NAME_COMPANY"]["VALUE"])->GetNextElement()->GetProperties();

        $file = CFile::ResizeImageGet($compani["LOGO_IMG"]["VALUE"], array('width' => 120, 'height' => 80),
            BX_RESIZE_IMAGE_PROPORTIONAL, true);

        ?>

        <div class="white_block">
            <?php if ($Date_change_user != "") { ?>
                <span class="date_review">Дата изменения <?php echo $Date_change_user; ?></span>
            <?php } ?>
            <!-- Company Name -->
            <div class="feedback__block_company-name"><img src="<?= $file["src"] ?>"></div>
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
                    <?= $name_user ?>, <?= $city["NAME"] ?>, <?= $newDate ?>
                </div>
                <!--                <div class="feedback__block_top_data">-->
                <!--                    05 сент, 2019-->
                <!--                </div>-->

            </div>
            <!-- Title -->
            <a href="/reviews/comment-<?= $arFields["ID"] ?>/">
                <h3 class="feedback__title"><?= $arFields["NAME"] ?></h3>
            </a>

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
//                    $file_comment = CFile::ResizeImageGet($arUserComments["PERSONAL_PHOTO"],
//                        array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                    ?>

                    <div class="hidenComments__top">
<!--                        <img src="--><?php //echo $file_comment["src"] ?><!--" alt="OMS">-->
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

                    <!-- Цитаты-->

                    <?
                    if ($arPropsComments["CITED"]["VALUE"] != "") {  // цитаты к коментариям
                        ?>
                        <div class="block_quotes"><?
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
//                                $file_quote = CFile::ResizeImageGet($arUserQuote["PERSONAL_PHOTO"],
//                                    array('width' => 50, 'height' => 50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                                ?>
                                <div class="hidenComments__top">

<!--                                    <img src="--><?php //echo $file_quote["src"]; ?><!--" alt="OMS">-->
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

        </div><!-- FeedBack block END -->
    <? }
}
if (!$sort_url["comments"] == "all") {
    $navStr = $res->GetPageNavStringEx($navComponentObject, "Страницы:", ".default");
    echo $navStr;
}
?>

    </div>


</div>


<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
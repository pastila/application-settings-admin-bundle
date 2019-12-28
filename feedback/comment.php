<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/feedback/main.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/feedback/main.min.js");
CModule::IncludeModule("iblock");
global $USER;
$url = $_SERVER["REQUEST_URI"];
//$url = "feedback/comment-37994/";

preg_match("/(\d+)\/$/",$url,$result_id);


?>
<div class="feedback">
    <div class="feedback__wrap_white-blocks">
        <!-- FeedBack block -->

        <?php

        $arFilter = Array(
            "IBLOCK_ID" => 13,
            "ACTIVE" => "Y",
            "ID"=>$result_id[1],
        );


        $order = Array("created" => "desc");
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");



        $res = CIBlockElement::GetList($order, $arFilter, false, $pagen, $arSelect);

        while ($ob = $res->GetNextElement()) {

            $arFields = $ob->GetFields();
            $arProps = $ob->GetProperties();
            $Date_change_user = FormatDate("d F, Y", MakeTimeStamp($arFields["DATE_CHANGE_BY_USER"]));
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
                    <?php if($arProps["EVALUATION"]["VALUE"] != ""){ ?>
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
                    <? } ?>
                    <div class="feedback__block_top_name">
                        <?= $name_user ?>, <?= $city["NAME"] ?>, <?= $newDate ?>
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
                    <div class="feedback__bottom_name opacity_block">OMC</div>

                    <a id="show-comments" class="feedback__bottom_link opacity_block">
                        <img src="" alt="">

                        <span class="comment-count">
                    <?= $count_comments ?>
                                </span>
                        комментариев
                    </a>
                    <span class="date_review">Дата изменения <?php echo $Date_change_user; ?></span>
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
                        $file_comment = CFile::ResizeImageGet($arUserComments["PERSONAL_PHOTO"], array('width'=>50, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                        ?>

                        <div class="hidenComments__top">
                            <img src="<?php echo $file_comment["src"] ?>" alt="OMS">

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
                                    $name_userQuote = $arUserQuote["NAME"];
                                    $file_quote = CFile::ResizeImageGet($arUserQuote["PERSONAL_PHOTO"], array('width'=>50, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                                    ?>
                                    <div class="hidenComments__top">

                                        <img src="<?php echo $file_quote["src"]; ?>" alt="OMS">
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

        ?>

    </div>

</div>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>

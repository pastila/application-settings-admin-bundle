<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

$ID_USER = $USER->GetID();
?>

<div class="header__r_auth">
    <a rel="nofollow" href="javascript:void(0)" id="show-mnu" class="header__r_auth_user">
                            <span class="header__r_auth_user_hello">
                                Добрый день,
                            </span>

        <span class="header__r_auth_user_name"><?php echo $USER->GetFullName();?> !</span>
    </a>

    <!-- #menu -->
    <div id="menu" class="white_block">
        <ul>
            <li>
                <a href="/personal-cabinet/">Личный кабинет</a>
            </li>

            <li>
                <!-- Если есть обращения в админке добавляем класс active -->
                <!-- И показываем блок с колличеством обращений -->
                <?php
                //Количество обращений
                if (CModule::IncludeModule("iblock")) {
                    $arFilterSect = array('IBLOCK_ID' => 11, "UF_USER_ID" => $ID_USER);
                    $rsSect = CIBlockSection::GetList(array(), $arFilterSect);
                    if ($arSect = $rsSect->GetNext()) {
                        $arSelect = array("ID", "NAME", "DATE_ACTIVE_FROM");
                        $arFilter = array("IBLOCK_ID" => 11, "!PROPERTY_SEND_REVIEW" => 3,
                            "IBLOCK_SECTION_ID" => $arSect["ID"], "ACTIVE" => "Y");
                        $res = CIBlockElement::GetList(
                            array(),
                            $arFilter,
                            false,
                            false,
                            $arSelect
                        );
                        while ($ob = $res->GetNextElement()) {
                            $arFields[] = $ob->GetFields();
                        }
                        $countAppeals = count($arFields);
                    }
                }
                ?>
                <div id="number_calls" class="menu-req">
                    <?php echo $countAppeals?>
                </div>
                <a class="active" href="/obrashcheniya/">Ваши обращения</a>

            </li>


            <li>
                <!-- Если есть обращения в админке добавляем класс active -->
                <!-- И показываем блок с колличеством обращений -->
                <?php
                //Количество обращений
                if (CModule::IncludeModule("iblock")) {

                    $arSelect = Array("ID", "IBLOCK_ID", "NAME",);
                    $arFilter = Array("IBLOCK_ID"=>13, "PROPERTY_NAME_USER"=>$ID_USER );
                    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                    $count_reviews = $res->SelectedRowsCount();
                }

                ?>
                <div id="number_calls" class="menu-req">
                    <?php echo $count_reviews?>
                </div>
                <a class="" href="/reviews/">Ваши отзывы</a>

            </li>
            <li>
                <div class="menu-req">
                    <?php


                    $arFilter = array("IBLOCK_ID" => 11, "UF_USER_ID" => $ID_USER);
                    $section = CIBlockSection::GetList(
                        array(),
                        $arFilter,
                        false,
                        false,
                        false
                    );  // получили секцию по айди юзера
                    if ($Section = $section->GetNext()) {
                        $arFilter = array("IBLOCK_ID" => 11,
                            "SECTION_ID" => $Section["ID"],"PROPERTY_SEND_REVIEW_VALUE" => 1);
                        $Element = CIBlockElement::GetList(
                            array(),
                            $arFilter,
                            false,
                            false,
                            false
                        ); //получили обращения юзера
                        $obElement = $Element->SelectedRowsCount();

                    } ?><?= $obElement ?>
                </div>

                <a href="/otpravlennyye/">Отправленные</a>
            </li>
        </ul>

        <a href="/ajax/logout.php">Выйти</a>
    </div>

    <div class="header__r_auth_user-image">
        <?php
        $rsUser = CUser::GetByID($ID_USER);
        $person = $rsUser->Fetch();
        //
        //                            $file = CFile::ResizeImageGet($person["PERSONAL_PHOTO"], array('width'=>50, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
        if($url != "/personal-cabinet/") {
            if ($person['UF_INSURANCE_COMPANY'] != '') {
            if (CIBlockElement::GetByID($person['UF_INSURANCE_COMPANY'])->SelectedRowsCount() != 0) {
                $res = CIBlockElement::GetByID($person['UF_INSURANCE_COMPANY'])->GetNextElement()->GetFields();
            if ($res["ACTIVE"] != "Y") {
                ?>

                <script> $('body').css({'overflow': 'hidden'});
                  setTimeout(function() {
                    $.magnificPopup.open({
                      items: {
                        src: '<div class="white-popup custom_styles_popup">Уважаемый пользователь. Ваша, выбранная компания при регистрации больше не сотрудничает с нами . Для ' +
                        'дальшнеего использования функционалом (обращения) нужно в <a href="/personal-cabinet/"> личном кабинете </a>выбрать страховую компанию </div>',
                        type: 'inline',
                      },
                    });
                    $('body').css({'overflow': 'initial'});
                  }, 1000);</script>
            <? }
            }else{
            ?>
                <script> $('body').css({'overflow': 'hidden'});
                  setTimeout(function() {
                    $.magnificPopup.open({
                      items: {
                        src: '<div class="white-popup custom_styles_popup">Уважаемый пользователь. Ваша, выбранная компания при регистрации больше не сотрудничает с нами . Для ' +
                        'дальшнеего использования функционалом (обращения) нужно в <a href="/personal-cabinet/"> личном кабинете </a>выбрать страховую компанию </div>',
                        type: 'inline',
                      },
                    });
                    $('body').css({'overflow': 'initial'});
                  }, 1000);</script>
            <?
            } ?>

            <? }else{
            ?>
                <script> $('body').css({'overflow': 'hidden'});
                  setTimeout(function() {
                    $.magnificPopup.open({
                      items: {
                        src: '<div class="white-popup custom_styles_popup">Уважаемый пользователь. Ваша, выбранная компания при регистрации больше не сотрудничает с нами . Для ' +
                        'дальшнеего использования функционалом (обращения) нужно в <a href="/personal-cabinet/"> личном кабинете </a>выбрать страховую компанию </div>',
                        type: 'inline',
                      },
                    });
                    $('body').css({'overflow': 'initial'});
                  }, 1000);</script>
                <?
            }
        }

        ?>

        <!--                            <img src="--><?//=$file["src"]?><!--" alt="">-->
    </div>
</div>
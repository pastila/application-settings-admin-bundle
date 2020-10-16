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
    <!-- #menu -->
    <ul id="menu" class="header__r_nav">
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
            <a class="active" href="/obrashcheniya/">
                <div id="number_calls" class="menu-req">
                    <?php echo $countAppeals?>
                </div>

                <span class="header__r_nav_text">Ваши обращения</span>
            </a>

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

            <a class="" href="/feedback">
                <div id="number_calls" class="menu-req">
                    <?php echo $count_reviews?>
                </div>

                <span class="header__r_nav_text">Ваши отзывы</span>
            </a>
        </li>

        <li>
            <a href="/otpravlennyye/">
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
                <span class="header__r_nav_text">Отправленные</span>
            </a>
        </li>

        <a class="header__r_nav_link" href="/logout">Выйти</a>
    </ul>

    <!-- <div class="header__r_auth_user-image"> -->
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

    <script>
    $('body').css({
        'overflow': 'hidden'
    });
    setTimeout(function() {
        $.magnificPopup.open({
            items: {
                src: '<div class="white-popup custom_styles_popup">Уважаемый пользователь! ' +
                'Страховая компания, указанная в вашем личном кабинете, прекратила свою деятельность. Формирование обращения в адрес страховой компании недоступно. Для выяснения своей страховой принадлежности и дальнейших действий по выбору СМО необходимо обратиться в территориальный фонд ОМС своего региона. После того, как определитесь с новой страховой компанией и сделаете отметку на полисе ОМС, укажите ее данные в <a href="/personal-cabinet/"> личном кабинете </a> . </div>',
                type: 'inline',
            },
        });
        $('body').css({
            'overflow': 'initial'
        });
    }, 1000);
    </script>
    <? }
            }else{
            ?>
    <script>
    $('body').css({
        'overflow': 'hidden'
    });
    setTimeout(function() {
        $.magnificPopup.open({
            items: {
              src: '<div class="white-popup custom_styles_popup">Уважаемый пользователь! ' +
              'Страховая компания, указанная в вашем личном кабинете, прекратила свою деятельность. Формирование обращения в адрес страховой компании недоступно. Для выяснения своей страховой принадлежности и дальнейших действий по выбору СМО необходимо обратиться в территориальный фонд ОМС своего региона. После того, как определитесь с новой страховой компанией и сделаете отметку на полисе ОМС, укажите ее данные в <a href="/personal-cabinet/"> личном кабинете </a> . </div>',
              type: 'inline',
            },
        });
        $('body').css({
            'overflow': 'initial'
        });
    }, 1000);
    </script>
    <?
            } ?>

    <? }else{
            ?>
    <script>
    $('body').css({
        'overflow': 'hidden'
    });
    setTimeout(function() {
        $.magnificPopup.open({
            items: {
              src: '<div class="white-popup custom_styles_popup">Уважаемый пользователь! ' +
              'Страховая компания, указанная в вашем личном кабинете, прекратила свою деятельность. Формирование обращения в адрес страховой компании недоступно. Для выяснения своей страховой принадлежности и дальнейших действий по выбору СМО необходимо обратиться в территориальный фонд ОМС своего региона. После того, как определитесь с новой страховой компанией и сделаете отметку на полисе ОМС, укажите ее данные в <a href="/personal-cabinet/"> личном кабинете </a> . </div>',
              type: 'inline',
            },
        });
        $('body').css({
            'overflow': 'initial'
        });
    }, 1000);
    </script>
    <?
            }
        }

        ?>

    <!--                            <img src="-->
    <?//=$file["src"]?>
    <!--" alt="">-->
    <!-- </div> -->
</div>
<?php
use Bitrix\Main\Page\Asset;
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
if($_GET){
    $get_letter= $_GET;
    if(isset($get_letter["letter"])){
        setcookie("letter","yes",0);
    }
}
$detect = new Mobile_Detect();
$asset = Asset::getInstance();

$url = $APPLICATION->GetCurDir();


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="HandheldFriendly" content="true">
    <title><?php $APPLICATION->ShowTitle(); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php $APPLICATION->ShowHead(); ?>
    <?php
    $asset->addCss(SITE_TEMPLATE_PATH . "/styles/main.min.css");
    $asset->addJs(SITE_TEMPLATE_PATH . "/js/main.min.js");

    CModule::IncludeModule("iblock");
    ?>

</head>

<body>
<?php $APPLICATION->ShowPanel();
?>

<!-- Start Wrap -->
<div class="wrap"
    <?php if ($APPLICATION->GetCurDir() === '/') {?>
    <?php } ?>>
    <!-- Header -->
    <header class="header">
        <div class="header__container">
            <a class="header__logo" href="/">
                <img class="header__logo_img"
                    src="/local/templates/kdteam/images/png/header/logo-oms.png"
                    alt="OMS">
            </a>
            <?php
            global $USER;
            $ID_USER = $USER->GetID();
            if ($USER->IsAuthorized()) { ?>
                <div class="header__r" style="display: flex;">
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


                </div>
            <?php } else { ?>
                <div class="header__r" style="display: flex;">
                    <div class="header__r_auth">
                        <a href="<?= SITE_TEMPLATE_PATH . "/includes/ajax-auth-login.php"?>" id="login-link"
                           class="header__r_auth_login">
                            <img class="header__r_auth_login_img"
                                 src="/local/templates/kdteam/images/svg/header/login/enter.svg" alt="OMS">
                            <div class="header__r_auth_login_text">
                                Вход
                            </div>
                        </a>

                        <a href="<?= SITE_TEMPLATE_PATH . "/includes/ajax-auth-reg.php"?>" id="reg-link" data-rigstration="0" class="header__r_auth_reg">
                            <img class="header__r_auth_login_img"
                                 src="/local/templates/kdteam/images/svg/header/reg/edit.svg" alt="OMS">
                            <div class="header__r_auth_login_text">
                                Регистрация
                            </div>
                        </a>
                    </div>

                    <a href="<?= SITE_TEMPLATE_PATH . "/includes/ajax-feedback.php"?>" class="header__r_mainBtn headerBtn" id="write-us_modal">
                        <svg style="fill: #ffffff; width: 2rem; height: 3rem;" class="header__r_auth_login_img hidden-desk--image" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M486.4 59.733H25.6c-14.138 0-25.6 11.461-25.6 25.6v341.333c0 14.138 11.461 25.6 25.6 25.6h460.8c14.138 0 25.6-11.461 25.6-25.6V85.333c0-14.138-11.461-25.6-25.6-25.6zm8.533 366.934a8.533 8.533 0 01-8.533 8.533H25.6a8.533 8.533 0 01-8.533-8.533V85.333A8.533 8.533 0 0125.6 76.8h460.8a8.533 8.533 0 018.533 8.533v341.334z"/><path d="M470.076 93.898a8.53 8.53 0 00-6.229 1.966L266.982 261.239a17.068 17.068 0 01-21.965 0L48.154 95.863a8.535 8.535 0 00-10.974 13.073l196.864 165.367c12.688 10.683 31.224 10.683 43.913 0L474.82 108.937a8.532 8.532 0 001.049-12.023 8.524 8.524 0 00-5.793-3.016zM164.124 273.13a8.535 8.535 0 00-8.229 2.65l-119.467 128a8.532 8.532 0 1012.476 11.639l119.467-128a8.532 8.532 0 00-4.247-14.289zM356.105 275.78a8.534 8.534 0 10-12.476 11.639l119.467 128a8.533 8.533 0 0012.476-11.64L356.105 275.78z"/></svg>

                        <div class="hidden-text-mob">
                            Написать нам
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </header>
    <!-- Content -->
    <main class="main">


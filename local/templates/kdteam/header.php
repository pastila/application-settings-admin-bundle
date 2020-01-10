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

$asset = Asset::getInstance();
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
<?php $APPLICATION->ShowPanel(); ?>

<!-- Start Wrap -->
<div class="wrap">
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
                        <a href="#menu" id="show-mnu" class="header__r_auth_user">
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

                            $file = CFile::ResizeImageGet($person["PERSONAL_PHOTO"], array('width'=>50, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL, true);

                            ?>

                            <img src="<?=$file["src"]?>" alt="">
                        </div>
                    </div>


                </div>
            <?php } else { ?>
                <div class="header__r" style="display: flex;">
                    <div class="header__r_auth">
                        <a href="<?= SITE_TEMPLATE_PATH . "/includes/ajax-auth-login.php"?>" id="login-link"
                           class="header__r_auth_login">
                            <img class="header__r_auth_login_img"
                                 src="/local/templates/kdteam/images/svg/header/login/man-user.svg" alt="OMS">
                            <div class="header__r_auth_login_text">
                                Вход
                            </div>
                        </a>

                        <a href="<?= SITE_TEMPLATE_PATH . "/includes/ajax-auth-reg.php"?>" id="reg-link" data-rigstration="0" class="header__r_auth_reg">
                            <img class="header__r_auth_login_img"
                                 src="/local/templates/kdteam/images/svg/header/reg/key.svg" alt="OMS">
                            <div class="header__r_auth_login_text">
                                Регистрация
                            </div>
                        </a>
                    </div>

                    <a href="<?= SITE_TEMPLATE_PATH . "/includes/ajax-feedback.php"?>" class="header__r_mainBtn mainBtn" id="write-us_modal">
                        <img class="header__r_auth_login_img hidden-desk--image" src="/local/templates/kdteam/images/svg/header/write-us/contract.svg" alt="">  <div class="hidden-text-mob">
                            Написать нам
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </header>
    <!-- Content -->
    <main class="main">


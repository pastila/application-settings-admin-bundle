<?php
use Bitrix\Main\Page\Asset;
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
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
    <?php $APPLICATION->ShowHead(); ?>
    <?php
    $asset->addCss("https://fonts.googleapis.com/css?family=Rubik:400,700&display=swap");
    $asset->addCss(SITE_TEMPLATE_PATH . "/styles/main.min.css");
    $asset->addJs(SITE_TEMPLATE_PATH . "/js/main.min.js");

    ?>
    <script src="/local/templates/kdteam/js/validator.min.js"></script>
</head>

<body>
<?php $APPLICATION->ShowPanel(); ?>

<!-- Start Wrap -->
<div class="wrap">
    <!-- Header -->
    <header class="header">
        <div class="header__container">
            <a class="header__logo" href="/">
                <img class="header__logo_img" src="/local/templates/kdteam/images/svg/header/logo/logo_header.svg"
                     alt="OMS">

                <div class="header__logo_name">company name</div>
            </a>
            <?php
            global $USER;
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
                                    <a href="#">Личный кабинет</a>
                                </li>

                                <li>
                                    <!-- Если есть обращения в админке добавляем класс active -->
                                    <!-- И показываем блок с колличеством обращений -->
                                    <?php
                                    //Количество обращений
                                    if (CModule::IncludeModule("iblock")) {
                                        $arFilterSect = array('IBLOCK_ID' => 11, "UF_USER_ID" => $USER->GetID());
                                        $rsSect = CIBlockSection::GetList(array(), $arFilterSect);
                                        if ($arSect = $rsSect->GetNext()) {
                                            $arSelect = array("ID", "NAME", "DATE_ACTIVE_FROM");
                                            $arFilter = array("IBLOCK_ID" => 11, "!PROPERTY_SEND_REVIEW" => "1",
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
                                    <?php if ($countAppeals > 0) { ?>
                                        <div class="menu-req">
                                            <?php echo $countAppeals?>
                                        </div>
                                        <a class="active" href="/obrashcheniya/">Ваши обращения</a>
                                    <?php }?>

                                </li>

                                <li>
                                    <div class="menu-req">
                                        1
                                    </div>
                                    <a href="/otpravlennyye/">Отправленные</a>
                                </li>
                            </ul>

                            <a href="/ajax/logout.php">Выйти</a>
                        </div>

                        <div class="header__r_auth_user-image">
                            <img src="./local/templates/kdteam/images/png/header/head_login.png" alt="">
                        </div>
                    </div>


                </div>
            <?php } else { ?>
                <div class="header__r" style="display: flex;">
                    <div class="header__r_auth">
                        <a href="<?= SITE_TEMPLATE_PATH . "/includes/ajax-auth-login.php"?>" id="login-link"
                           class="header__r_auth_login">
                            <img class="header__r_auth_login_img"
                                 src="./local/templates/kdteam/images/svg/header/login/man-user.svg" alt="OMS">
                            <div class="header__r_auth_login_text">
                                Вход
                            </div>
                        </a>

                        <a href="<?= SITE_TEMPLATE_PATH . "/includes/ajax-auth-reg.php"?>" id="reg-link" class="header__r_auth_reg">
                            <img class="header__r_auth_login_img"
                                 src="./local/templates/kdteam/images/svg/header/reg/key.svg" alt="OMS">
                            <div class="header__r_auth_login_text">
                                Регистрация
                            </div>
                        </a>
                    </div>

                    <button class="header__r_mainBtn mainBtn">
                        Написать нам
                    </button>
                </div>
            <?php } ?>
        </div>
    </header>
    <!-- Content -->
    <main class="main">


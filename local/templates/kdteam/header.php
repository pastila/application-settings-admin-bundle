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
</head>

<body>
<?php $APPLICATION->ShowPanel(); ?>

<!-- Start Wrap -->
<div class="wrap">
    <!-- Header -->
    <header class="header">
        <div class="header__container">
            <a class="header__logo" href="/index.html">
                <img class="header__logo_img" src="./local/templates/kdteam/images/svg/header/logo/logo_header.svg"
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
                                    <div class="menu-req">
                                        3
                                    </div>
                                    <a class="active" href="/obrashcheniya.html">Ваши обращения</a>
                                </li>

                                <li>
                                    <div class="menu-req">
                                        1
                                    </div>
                                    <a href="/otpravlennyye.html">Отправленные</a>
                                </li>
                            </ul>

                            <a href="#">Выйти</a>
                        </div>

                        <div class="header__r_auth_user-image">
                            <img src="" alt="">
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

                        <a href="./includes/ajax-auth-reg.html" id="reg-link" class="header__r_auth_reg">
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


<?php
use Bitrix\Main\Page\Asset;
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

function getCountReviews($login, $token)
{
    $url  = sprintf('http://nginx/api/v1/panel?user=%s&api_token=%s', $login, $token);
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $res = curl_exec($ch);
    $info = curl_getinfo($ch);
    $code = key_exists('http_code', $info) ? $info['http_code'] : null;

    $nbReviews = 0;
    if ($code === 200)
    {
        $data = json_decode($res, true);
        $nbReviews = !empty($data['nbReviews']) ? $data['nbReviews'] : 0;
    }

    return $nbReviews;
}
$count_reviews = 0;
$api_token = '31409EDCC223DB7DAA72C54EE0107B9F';
if ($USER->IsAuthorized())
{
  $count_reviews = getCountReviews($USER->GetLogin(), $api_token);
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
<html lang="en" prefix="og: https://ogp.me/ns#">

<head>
<!--    <meta charset="UTF-8">-->
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="HandheldFriendly" content="true" />
    <meta name="yandex-verification" content="3117711c7cf9d47f" />
    <meta name="google-site-verification" content="6i-0zWPQEfVk1TaN63icBNxaPtFIwxrzlrG-w4oiTyg" />
    <!-- Адмитад -->
    <meta name="verify-admitad" content="d368515aeb" />
    <!-- /Адмитад -->

    <?php if ($_SERVER[REQUEST_URI] === '/') {?>
        <meta property="og:type" content="website"/>
        <meta property="og:url" content="<?= ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" ?>"/>
        <meta property="og:title" content="<?php $APPLICATION->ShowTitle(); ?>"/>
        <meta property="og:site_name" content="Без Бахил. Лечись бесплатно!"/>
        <meta property="og:description" content="Сервис bezbahil — сохраняет здоровье и деньги."/>
        <meta property="og:locale" content="ru_RU"/>
        <meta property="og:image" content="http://bezbahil.docker/local/templates/kdteam/images/png/header/logo-oms-white.png"/>
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
    <?php }?>

    <title><?php $APPLICATION->ShowTitle(); ?></title>
    <link rel="shortcut icon" href="/local/templates/kdteam/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php $APPLICATION->ShowHead(); ?>
<meta property = "og:image" content="https://bezbahil.ru/local/templates/kdteam/images/png/header/logo-oms-white.jpg"/>
<link rel="image_src" href="https://bezbahil.ru/local/templates/kdteam/images/png/header/logo-oms-white.jpg" />

    <?php
    $asset->addCss(SITE_TEMPLATE_PATH . "/styles/main.min.css");
    $asset->addJs(SITE_TEMPLATE_PATH . "/js/main.min.js");

    CModule::IncludeModule("iblock");
    ?>

    <?php if ($_SERVER[REQUEST_URI] === '/') { ?>
        <script type="application/ld+json">
            {
                "@context": "http://schema.org/",
                "@type": "WebSite",
                "name": "Без Бахил",
                "url": "https://bezbahil.ru/"
            }
        </script>
    <?php }?>
    

    <!-- Google Tag Manager -->
    <script data-skip-moving="true">
      (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
          j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
          'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','GTM-N3VBFGG');
    </script>
    <!-- End Google Tag Manager -->

    <!--  JivoSite -->
    <script src="//code-ya.jivosite.com/widget/u0hymF3kCA" async></script>
    <!-- / JivoSite -->
</head>

<body>
<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
  (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
    m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)

      [0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
  (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

  ym(57673804, "init", {
    clickmap:true,
    trackLinks:true,
    accurateTrackBounce:true,
    webvisor:true
  });
</script>
<noscript>
    <div>
        <img src="https://mc.yandex.ru/watch/57673804" style="position:absolute; left:-9999px;" alt="" />
    </div>
</noscript>
<!-- /Yandex.Metrika counter -->


<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N3VBFGG"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
    <?php $APPLICATION->ShowPanel();
?>

    <!-- Start Wrap -->
    <div class="wrap" <?php if ($APPLICATION->GetCurDir() === '/') {?> <?php } ?>>
        <!-- Header -->
        <header class="header">
            <div class="header__container">
                <a class="header__logo" href="/">
                    <img class="header__logo_img" src="/local/templates/kdteam/images/png/header/logo-oms-white.png"
                        alt="OMS">
                </a>




                <?php
            global $USER;
            $ID_USER = $USER->GetID();
            if ($USER->IsAuthorized()) { ?>
                <div class="header__r"><img class="header__r_auth_login_img" src="/images/message.svg" alt="OMS">
<a style="font-size: 2rem; color: #fff; display: flex; padding-right: 2rem;padding-left: 1rem;" href="/vopros-otvet/"><span class="header__r_auth_user_hello">Вопрос - Ответ</span></a>
                    <a rel="nofollow" href="javascript:void(0)" id="show-mnu" class="header__r_auth_user">
                        <span class="header__r_auth_user_hello">
                            Добрый день,
                        </span>

                        <span class="header__r_auth_user_name"><?php echo $USER->GetFullName();?> !</span>
                    </a>

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
                                <span id="number_calls" class="menu-req">
                                    <?php echo $countAppeals?>
                                </span>

                                <span class="header__r_nav_text">Ваши обращения</span>
                            </a>

                        </li>

                        <li>
                            <!-- Если есть обращения в админке добавляем класс active -->
                            <!-- И показываем блок с колличеством обращений -->
                            <a class="" href="/cabinet/feedback">
                                <span id="number_calls" class="menu-req">
                                    <?php echo $count_reviews?>
                                </span>

                                <span class="header__r_nav_text">Ваши отзывы</span>
                            </a>
                        </li>

                        <li>
                            <a href="/otpravlennyye/">
                                <span class="menu-req">
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
                                </span>
                                <span class="header__r_nav_text">Отправленные</span>
                            </a>
                        </li>

                        <li>
                            <a class="header__r_nav_link" href="/logout">Выйти</a>
                        </li>
                    </ul>

                    <!-- <div class="header__r_auth">
                        <div class="header__r_auth_user-image">
                            <?php
                            $rsUser = CUser::GetByID($ID_USER);
                            $person = $rsUser->Fetch();

                            $file = CFile::ResizeImageGet($person["PERSONAL_PHOTO"], array('width'=>50, 'height'=>50), BX_RESIZE_IMAGE_PROPORTIONAL, true);
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
                                            src: '<div class="white-popup custom_styles_popup">'+
                                            '<p>Уважаемый пользователь!</p>'+
                                            '<p>Страховая компания, указанная в вашем личном кабинете, прекратила свою '+
                                            'деятельность. Формирование обращения в адрес страховой компании недоступно. '+
                                            'Для выяснения своей страховой принадлежности и дальнейших действий по выбору '+
                                            'СМО необходимо обратиться в территориальный фонд ОМС своего региона. '+
                                            'После того, как определитесь с новой страховой компанией и сделаете '+
                                            'отметку на полисе ОМС, укажите ее данные в '+
                                            '<a href="/personal-cabinet/"> личном кабинете </a>.</p></div>',
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
                                            src: '<div class="white-popup custom_styles_popup">'+
                                            '<p>Уважаемый пользователь!</p>'+
                                            '<p>Страховая компания, указанная в вашем личном кабинете, прекратила свою '+
                                            'деятельность. Формирование обращения в адрес страховой компании недоступно. '+
                                            'Для выяснения своей страховой принадлежности и дальнейших действий по выбору '+
                                            'СМО необходимо обратиться в территориальный фонд ОМС своего региона. '+
                                            'После того, как определитесь с новой страховой компанией и сделаете '+
                                            'отметку на полисе ОМС, укажите ее данные в '+
                                            '<a href="/personal-cabinet/"> личном кабинете </a>.</p></div>',
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
                                            src: '<div class="white-popup custom_styles_popup">'+
                                            '<p>Уважаемый пользователь!</p>'+
                                            '<p>Страховая компания, указанная в вашем личном кабинете, прекратила свою '+
                                            'деятельность. Формирование обращения в адрес страховой компании недоступно. '+
                                            'Для выяснения своей страховой принадлежности и дальнейших действий по выбору '+
                                            'СМО необходимо обратиться в территориальный фонд ОМС своего региона. '+
                                            'После того, как определитесь с новой страховой компанией и сделаете '+
                                            'отметку на полисе ОМС, укажите ее данные в '+
                                            '<a href="/personal-cabinet/"> личном кабинете </a>.</p></div>',
                                            type: 'inline',
                                          },
                                        });
                                        $('body').css({'overflow': 'initial'});
                                      }, 1000);</script>
                                <?
                                }
                            }

                            ?>

                          <img src="-->
                    <?//=$file["src"]?>
                    <!--" alt="">
                        </div>
                    </div> -->
                </div>
                <?php } else { ?>

                <div class="header__r" style="display: flex;">
                    <div class="header__r_auth">
<span class="header__r_auth_login">
<img class="header__r_auth_login_img" src="/images/message.svg" alt="OMS">
<a style="font-size: 2rem;" href="/vopros-otvet/"><div class="header__r_auth_login_text">Вопрос - Ответ</div></a></span>
                        <a href="<?= SITE_TEMPLATE_PATH . "/includes/ajax-auth-login.php"?>" id="login-link"
                            class="header__r_auth_login">
                            <img class="header__r_auth_login_img"
                                src="/images/key.svg" alt="OMS">
                            <span class="header__r_auth_login_text">
                                Вход
                            </span>
                        </a>

                        <a href="<?= SITE_TEMPLATE_PATH . "/includes/ajax-auth-reg.php"?>" id="reg-link"
                            data-rigstration="0" class="header__r_auth_reg">
                            <img class="header__r_auth_login_img"
                                src="/images/edit.svg" alt="OMS">
                            <span class="header__r_auth_login_text">
                                Регистрация
                            </span>
                        </a>
                    </div>

                    <a href="<?= SITE_TEMPLATE_PATH . "/includes/ajax-feedback.php"?>"
                        class="header__r_mainBtn headerBtn" id="write-us_modal">
                        <svg style="fill: #ffffff; width: 2rem; height: 3rem;"
                            class="header__r_auth_login_img hidden-desk--image" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512">
                            <path
                                d="M486.4 59.733H25.6c-14.138 0-25.6 11.461-25.6 25.6v341.333c0 14.138 11.461 25.6 25.6 25.6h460.8c14.138 0 25.6-11.461 25.6-25.6V85.333c0-14.138-11.461-25.6-25.6-25.6zm8.533 366.934a8.533 8.533 0 01-8.533 8.533H25.6a8.533 8.533 0 01-8.533-8.533V85.333A8.533 8.533 0 0125.6 76.8h460.8a8.533 8.533 0 018.533 8.533v341.334z" />
                            <path
                                d="M470.076 93.898a8.53 8.53 0 00-6.229 1.966L266.982 261.239a17.068 17.068 0 01-21.965 0L48.154 95.863a8.535 8.535 0 00-10.974 13.073l196.864 165.367c12.688 10.683 31.224 10.683 43.913 0L474.82 108.937a8.532 8.532 0 001.049-12.023 8.524 8.524 0 00-5.793-3.016zM164.124 273.13a8.535 8.535 0 00-8.229 2.65l-119.467 128a8.532 8.532 0 1012.476 11.639l119.467-128a8.532 8.532 0 00-4.247-14.289zM356.105 275.78a8.534 8.534 0 10-12.476 11.639l119.467 128a8.533 8.533 0 0012.476-11.64L356.105 275.78z" />
                        </svg>

                        <span class="hidden-text-mob">
                            Написать нам
                        </span>
                    </a>
                </div>
                <?php } ?>
            </div>
        </header>
        <!-- Content -->
        <main class="main">


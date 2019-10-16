<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;
$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/otpravlennyye/otpravlennyye.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/otpravlennyye/otpravlennyye.min.js");
?>


<!-- Breadcrumbs -->
<ul class="breadcrumbs">
    <li><a href="/index.html">Главная</a></li>
    <li>Личный кабинет</li>
</ul>

<!-- Pages Title -->
<h2 class="page-title">Отправленные обращения</h2>

<!-- Обращения item -->
<div class="otpravlennyye">
    <div class="card">
        <div class="otpravlennyye__item">
            <div class="otpravlennyye__item_title">
                Обращение № 1
            </div>

            <p class="success">Направлено в страховую компанию</p>

            <div class="otpravlennyye__item_data">
                дата: 20.09.2019
            </div>

            <p class="otpravlennyye__item_text">
                В соответствии с действующим законодательством
                в течение 30 дней вам должны предоставить ответ на обращение либо проинформировать о
                продлении срока рассмотрения обращения, если для решения поставленных вопросов нужно
                проведение экспертизы
            </p>
        </div>
    </div>
</div>

<!-- Обращения item -->
<div class="otpravlennyye">
    <div class="card">
        <div class="otpravlennyye__item">
            <div class="otpravlennyye__item_title">
                Обращение № 5642
            </div>

            <p class="success">Направлено в страховую компанию</p>

            <div class="otpravlennyye__item_data">
                дата: 20.09.2019
            </div>

            <p class="otpravlennyye__item_text">
                В соответствии с действующим законодательством
                в течение 30 дней вам должны предоставить ответ на обращение либо проинформировать о
                продлении срока рассмотрения обращения, если для решения поставленных вопросов нужно
                проведение экспертизы
            </p>
        </div>
    </div>
</div>


<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>

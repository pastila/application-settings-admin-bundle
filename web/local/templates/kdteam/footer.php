<?php
if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) {
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog.php");
}
?>
</main>

<!-- Footer -->
<footer class="footer">
    <div class="footer__container">
        <div class="footer__logo">
            <img src="/landing/img/logo.png" alt="OMS">
        </div>

        <ul class="footer__menu">
            <li>
                <a href="/for_SMO">Страховым организациям</a>
            </li>
            <li>
                <a href="/o-nas">О нас</a>
            </li>
            <li>
                <a href="/contact_us">Контакты</a>
            </li>
            <li>
                <a href="/feedback">Отзывы</a>
            </li>
            <li>
                <a href="/news">Новости</a>
            </li>
            <li>
                <a href="/polzovatelskoe-soglashenie">Пользовательское соглашение</a>
            </li>
            <li>
                <a href="/politika-obrabotki-personalnyh-dannyh">Политика по обработке персональных данных</a>
            </li>
        </ul>
    </div>
</footer>


</div><!-- END Wrap -->
</body>
<script src="/local/templates/kdteam/js/validator.min.js"></script>
</html>
<?php

 $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PROPERTY_TITLE", "PROPERTY_KEYWORDS", "PROPERTY_DESCRIPTION");
$arFilter = Array("IBLOCK_CODE"=>"seo", "%NAME"=>  $APPLICATION->GetCurDir());
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
if($ob = $res->GetNextElement()){
    $arProps = $ob->GetFields();
    if($arProps["PROPERTY_TITLE_VALUE"]) {
        $APPLICATION->SetPageProperty("title", $arProps["PROPERTY_TITLE_VALUE"]);
    }
    if($arProps["PROPERTY_KEYWORDS_VALUE"]) {
        $APPLICATION->SetPageProperty("keywords", $arProps["PROPERTY_KEYWORDS_VALUE"]);
    }
        if($arProps["PROPERTY_DESCRIPTION_VALUE"]) {
            $APPLICATION->SetPageProperty("description", $arProps["PROPERTY_DESCRIPTION_VALUE"]);
        }
} ?>
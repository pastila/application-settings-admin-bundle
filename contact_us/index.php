<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/otpravlennyye/otpravlennyye.min.css");
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/contact_us/contact_us.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/otpravlennyye/otpravlennyye.min.js");
CModule::IncludeModule("iblock");

?>

<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/classes/general/captcha.php");
$cpt = new CCaptcha();

$captchaPass = COption::GetOptionString("main","captcha_password","");
if(strlen($captchaPass) <= 0){
    $captchaPass = randString(10);
    COption::SetOptionString("main","captcha_password",$captchaPass);
}
$cpt->SetCodeCrypt($captchaPass);
?>

<?$APPLICATION->SetTitle("Связаться с нами");?>
<?php $arSelect = Array("ID", "IBLOCK_ID", "NAME", "PREVIEW_TEXT");
$arFilter = Array("IBLOCK_ID"=>23, "CODE"=>"contact_us");
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
if($ob = $res->GetNextElement()){
    $arProps = $ob->GetFields();

} ?>
<!-- Breadcrumbs -->
<ul class="breadcrumbs">
    <? if ($detect->isTablet() || $detect->isMobile()) { ?>
        <li><a href="/" class=""><?= $arProps["NAME"]; ?></a></li>
    <? } else { ?>
        <li><a href="/">Главная</a></li>
        <li><?= $arProps["NAME"]; ?></li>
    <? } ?>

</ul>
<!-- Pages Title -->
<?= $arProps["PREVIEW_TEXT"]; ?>

<div class="form_contact-us white_block">
    <form id="feedback_modal_two" class="writeUs__form" enctype="multipart/form-data" >
        <div class="writeUs__form__wrap">
            <h2 class="title_medium"> Напишите нам</h2>
            <div class="writeUs__form__wrap__middle">
                <!-- Input -->
                <div class="input__wrap input__wrap__half">
                    <label class="input__wrap_label">Имя</label>
                    <input id="name" type="text" name="name" required>
                </div>
                <!-- Input -->
                <div class="input__wrap input__wrap__half">
                    <label class="input__wrap_label">Электронная почта</label>
                    <input id="email" type="email" name="email" required>
                </div>
            </div>
                <div class="input__wrap">
                    <textarea class="textarea_input" minlength="10" name="text" required></textarea>
                </div>
            <div class="input__wrap">
                <label class="input__wrap_label">Прикрепить к сообщению файлы(максимум 5):</label>
                <div class="file-input file_input_half">
                    <button class="button smallAccentBtn"  id="btn_myFileInput">.png .jpeg</button>
                    <label class="label_parent-elements" for="btn_myFileInput">Вы не выбрали файлы</label>
                    <input class="file-simple"  accept="image/*" type="file" id="myFileInput" multiple />
                    <span class="label block-error-label">Максимальный размер файла 10mb</span>
                    <span class="label block-error-label">Для выбора нескольких картинок зажмите Ctrl</span>
                </div>
            </div>
            <div class="block_captcha block_padd-modal">
                <div class="input__wrap input-initial_wrap" id="captcha-error_parent">
                    <input type="hidden" name="captcha_code" value="<?php echo htmlspecialcharsbx($cpt->GetCodeCrypt()) ?>">
                    <img class="image-captcha" src="/bitrix/tools/captcha.php?captcha_code=<?php echo htmlspecialcharsbx($cpt->GetCodeCrypt()) ?>" alt="">
                    <input type="text" name="captcha_word" id="captcha_word">
                </div>
            </div>
            <div class="writeUs__form__button">
                <button type="submit" class="mainBtn" id="ask">Отправить</button>
            </div>
        </div>
    </form>
    <script>

      /*add file input*/
      $(document).ready(function() {
        $(function () {
          $('#btn_myFileInput').data('default', $('label[for=btn_myFileInput]').text()).click(function () {
            $('#myFileInput').click()
          });
          $('#myFileInput').on('change', function () {
            var files = this.files;
            if (!files.length) {
              $('label[for=btn_myFileInput]').text($('#btn_myFileInput').data('default'));
              return;
            }
            $('label[for=btn_myFileInput]').empty();
            for (var i = 0, l = files.length; i < l; i++) {
              $('label[for=btn_myFileInput]').append('<span class="element-file">'  + files[i].name + '</span>' + '\n');
            }
          });
        });
      });
    </script>
</div>
<style>
    .decoration-link{
        text-decoration: underline;
    }
    p{
        margin-bottom: 1rem;
    }
    .list_block li{
        padding-left: 1.5rem;
        margin-bottom: .5rem;
    }
</style>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>

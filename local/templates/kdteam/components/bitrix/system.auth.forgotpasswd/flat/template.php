<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
    die();
}

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
use Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/recovery-pass/recovery-pass.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/recovery-pass/recovery-pass.min.js");
?>

<section class="personalCabinet">
    <ul class="breadcrumbs">
        <li><a href="/">Главная</a></li>
        <li>Восстановление пароля</li>
    </ul>
    <h1 class="page-title">Восстановление пароля</h1>
    <div class="white_block block-recovery_pass">

        <?
        if(!empty($APPLICATION->arAuthResult)):
            $text = str_replace(array("<br>", "<br />"), "\n", $APPLICATION->arAuthResult["MESSAGE"]);
            ?>
            <div class="alert <?=($APPLICATION->arAuthResult["TYPE"] == "OK"? "alert-success":"alert-danger")?>"><?=nl2br(htmlspecialcharsbx($text))?></div>
        <?endif?>


        <p class="bx-authform-note-container"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></p>

        <form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
            <?if($arResult["BACKURL"] <> ''):?>
                <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
            <?endif?>
            <input type="hidden" name="AUTH_FORM" value="Y">
            <input type="hidden" name="TYPE" value="SEND_PWD">

            <div class="bx-authform-formgroup-container">
                <div class="input__wrap">
                    <label class="input__wrap_label"><?echo GetMessage("AUTH_LOGIN_EMAIL")?></label>
                    <input type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" />
                    <input type="hidden" name="USER_EMAIL" />
                </div>
                <div class="bx-authform-note-container"><?echo GetMessage("forgot_pass_email_note")?></div>
            </div>

            <?if($arResult["PHONE_REGISTRATION"]):?>
                <div class="bx-authform-formgroup-container">
                    <div class="bx-authform-label-container"><?echo GetMessage("forgot_pass_phone_number")?></div>
                    <div class="bx-authform-input-container">
                        <div class="input__wrap">
                            <input type="text" name="USER_PHONE_NUMBER" maxlength="255" value="" />
                        </div>
                    </div>
                    <div class="bx-authform-note-container"><?echo GetMessage("forgot_pass_phone_number_note")?></div>
                </div>
            <?endif?>

            <?if ($arResult["USE_CAPTCHA"]):?>
                <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />

                <div class="bx-authform-formgroup-container">
                    <div class="bx-authform-label-container"><?echo GetMessage("system_auth_captcha")?></div>
                    <div class="bx-captcha"><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /></div>
                    <div class="bx-authform-input-container">
                        <div class="input__wrap">
                            <input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off"/>
                        </div>
                    </div>
                </div>

            <?endif?>

            <div class="bx-authform-formgroup-container">
                <input type="submit" class="mainBtn main-btn_fullwidth" name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>" />
            </div>

        </form>

    </div>
</section>

<script type="text/javascript">
  document.bform.onsubmit = function(){document.bform.USER_EMAIL.value = document.bform.USER_LOGIN.value;};
  document.bform.USER_LOGIN.focus();
</script>

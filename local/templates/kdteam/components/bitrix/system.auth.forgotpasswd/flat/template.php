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

//one css for all system.auth.* forms
$APPLICATION->SetAdditionalCSS("/bitrix/css/main/system.auth/flat/style.css");
?>
<section class="personalCabinet">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="tittleBlock mainTitle">
                    <h1 class="titleGreenLine">Восстановление пароля</h1>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-7 col-sm-8 col-12">
                <div class="bx-authform card cardAuthorization">

                    <?
                    if(!empty($APPLICATION->arAuthResult)):
                        $text = str_replace(array("<br>", "<br />"), "\n", $APPLICATION->arAuthResult["MESSAGE"]);
                        ?>
                        <div class="alert shadow <?=($APPLICATION->arAuthResult["TYPE"] == "OK"? "alert-success":"alert-danger")?>"><?=nl2br(htmlspecialcharsbx($text))?></div>
                    <?endif?>


                    <p class="bx-authform-note-container"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></p>

                    <form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
                        <?if($arResult["BACKURL"] <> ''):?>
                            <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
                        <?endif?>
                        <input type="hidden" name="AUTH_FORM" value="Y">
                        <input type="hidden" name="TYPE" value="SEND_PWD">

                        <div class="bx-authform-formgroup-container">
                            <label><?echo GetMessage("AUTH_LOGIN_EMAIL")?></label>
                            <div class="bx-authform-input-container input-style">
                                <input type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" />
                                <input type="hidden" name="USER_EMAIL" />
                            </div>
                            <div class="bx-authform-note-container b"><?echo GetMessage("forgot_pass_email_note")?></div>
                        </div>

                        <?if($arResult["PHONE_REGISTRATION"]):?>
                            <div class="bx-authform-formgroup-container">
                                <div class="bx-authform-label-container"><?echo GetMessage("forgot_pass_phone_number")?></div>
                                <div class="bx-authform-input-container">
                                    <input type="text" name="USER_PHONE_NUMBER" maxlength="255" value="" />
                                </div>
                                <div class="bx-authform-note-container b"><?echo GetMessage("forgot_pass_phone_number_note")?></div>
                            </div>
                        <?endif?>

                        <?if ($arResult["USE_CAPTCHA"]):?>
                            <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />

                            <div class="bx-authform-formgroup-container">
                                <div class="bx-authform-label-container"><?echo GetMessage("system_auth_captcha")?></div>
                                <div class="bx-captcha"><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /></div>
                                <div class="bx-authform-input-container">
                                    <input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off"/>
                                </div>
                            </div>

                        <?endif?>

                        <div class="bx-authform-formgroup-container">
                            <input type="submit" class="mainBtn primaryBtn buttonEntryUser" name="send_account_info" value="<?=GetMessage("AUTH_SEND")?>" />
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
  document.bform.onsubmit = function(){document.bform.USER_EMAIL.value = document.bform.USER_LOGIN.value;};
  document.bform.USER_LOGIN.focus();
</script>

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

if($arResult["PHONE_REGISTRATION"])
{
    CJSCore::Init('phone_auth');
}
?>
<section class="personalCabinet">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="tittleBlock mainTitle">
                    <h1 class="titleGreenLine">Смена пароля</h1>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-xl-5 col-lg-6 col-md-7 col-sm-8 col-12">
                <div class="bx-authform card cardAuthorization">

                    <?
                    if(!empty($APPLICATION->arAuthResult)):
                        $text = str_replace(array("<br>", "<br />"), "\n", $APPLICATION->arAuthResult["MESSAGE"]);
                        if ($APPLICATION->arAuthResult ["TYPE"] == "OK") {
                            $text .= ' Для продолжения авторизуйтесь.';
                        }
                        ?>
                        <div class="alert <?=($APPLICATION->arAuthResult ["TYPE"] == "OK"? "alert-success":"alert-danger")?>"><?=nl2br(htmlspecialcharsbx($text))?></div>
                    <?endif?>
                    <form method="post" action="<?=$arResult["AUTH_FORM"]?>" name="bform">
                        <?if (strlen($arResult["BACKURL"]) > 0): ?>
                            <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
                        <? endif ?>
                        <input type="hidden" name="AUTH_FORM" value="Y">
                        <input type="hidden" name="TYPE" value="CHANGE_PWD">

                        <?if($arResult["PHONE_REGISTRATION"]):?>
                            <div class="bx-authform-formgroup-container input-style d-none">
                                <label><?echo GetMessage("change_pass_phone_number")?></label>
                                <div class="bx-authform-input-container">
                                    <input type="text" value="<?=htmlspecialcharsbx($arResult["USER_PHONE_NUMBER"])?>" disabled="disabled" />
                                    <input type="hidden" name="USER_PHONE_NUMBER" value="<?=htmlspecialcharsbx($arResult["USER_PHONE_NUMBER"])?>" />
                                </div>
                            </div>
                            <div class="bx-authform-formgroup-container input-style d-none">
                                <label><?echo GetMessage("change_pass_code")?></label>
                                <div class="bx-authform-input-container">
                                    <input type="text" name="USER_CHECKWORD" maxlength="255" value="<?=$arResult["USER_CHECKWORD"]?>" autocomplete="off" />
                                </div>
                            </div>
                        <?else:?>
                            <div class="bx-authform-formgroup-container input-style d-none" style="display: none">
                                <label><?=GetMessage("AUTH_LOGIN")?></label>
                                <div class="bx-authform-input-container" >
                                    <input type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" />
                                </div>
                            </div>

                            <div class="bx-authform-formgroup-container input-style d-none" style="display: none">
                                <label><?=GetMessage("AUTH_CHECKWORD")?></label>
                                <div class="bx-authform-input-container">
                                    <input type="text" name="USER_CHECKWORD" maxlength="255" value="<?=$arResult["USER_CHECKWORD"]?>" autocomplete="off" />
                                </div>
                            </div>
                        <?endif?>

                        <div class="bx-authform-formgroup-container input-style">
                            <label><?=GetMessage("AUTH_NEW_PASSWORD_REQ")?></label>
                            <div class="bx-authform-input-container">
                                <?if($arResult["SECURE_AUTH"]):?>
                                    <div class="bx-authform-psw-protected" id="bx_auth_secure" style="display:none"><div class="bx-authform-psw-protected-desc"><span></span><?echo GetMessage("AUTH_SECURE_NOTE")?></div></div>

                                    <script type="text/javascript">
                                      document.getElementById('bx_auth_secure').style.display = '';
                                    </script>
                                <?endif?>
                                <input type="password" name="USER_PASSWORD" maxlength="255" value="<?=$arResult["USER_PASSWORD"]?>" autocomplete="off" />
                            </div>
                        </div>

                        <div class="bx-authform-formgroup-container input-style">
                            <label><?=GetMessage("AUTH_NEW_PASSWORD_CONFIRM")?></label>
                            <div class="bx-authform-input-container">
                                <?if($arResult["SECURE_AUTH"]):?>
                                    <div class="bx-authform-psw-protected" id="bx_auth_secure_conf" style="display:none"><div class="bx-authform-psw-protected-desc"><span></span><?echo GetMessage("AUTH_SECURE_NOTE")?></div></div>

                                    <script type="text/javascript">
                                      document.getElementById('bx_auth_secure_conf').style.display = '';
                                    </script>
                                <?endif?>
                                <input type="password" name="USER_CONFIRM_PASSWORD" maxlength="255" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" autocomplete="off" />
                            </div>
                        </div>
                        <div class="bx-authform-description-container pass-must-consist d-none">
                            <?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?>
                        </div>

                        <?if ($arResult["USE_CAPTCHA"]):?>
                            <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />

                            <div class="bx-authform-formgroup-container input-style">
                                <label><?echo GetMessage("system_auth_captcha")?></label>
                                <div class="bx-captcha"><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /></div>
                                <div class="bx-authform-input-container">
                                    <input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off"/>
                                </div>
                            </div>

                        <?endif?>

                        <div class="bx-authform-formgroup-container">
                            <input type="submit" class="mainBtn primaryBtn buttonEntryUser" name="change_pwd" value="<?=GetMessage("AUTH_CHANGE")?>" />
                        </div>


                    </form>

                </div>
            </div>
        </div>
    </div>
</section>


<?if($arResult["PHONE_REGISTRATION"]):?>

    <script type="text/javascript">
      new BX.PhoneAuth({
        containerId: 'bx_chpass_resend',
        errorContainerId: 'bx_chpass_error',
        interval: <?=$arResult["PHONE_CODE_RESEND_INTERVAL"]?>,
        data:
          <?=CUtil::PhpToJSObject([
              'signedData' => $arResult["SIGNED_DATA"]
          ])?>,
        onError:
            function(response)
            {
              var errorNode = BX('bx_chpass_error');
              errorNode.innerHTML = '';
              for(var i = 0; i < response.errors.length; i++)
              {
                errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br />';
              }
              errorNode.style.display = '';
            }
      });
    </script>

    <div class="alert alert-danger" id="bx_chpass_error" style="display:none"></div>

    <div id="bx_chpass_resend"></div>

<?endif?>

<script type="text/javascript">
  document.bform.USER_CHECKWORD.focus();
</script>

<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
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

if ($arResult["PHONE_REGISTRATION"]) {
    CJSCore::Init('phone_auth');
}
?>
<section class="personalCabinet">
    <ul class="breadcrumbs">
        <li><a href="/">Главная</a></li>
        <li>Смена пароля</li>
    </ul>
    <h1 class="page-title">Смена пароля</h1>
    <div class="white_block block-recovery_pass">

        <?


        global $USER;
        if (!empty($APPLICATION->arAuthResult)) {
            $text = str_replace(array("<br>", "<br />"), "\n", $APPLICATION->arAuthResult["MESSAGE"]);
            if ($APPLICATION->arAuthResult ["TYPE"] == "OK") {

                preg_match('/(.*)(USER_LOGIN=)(.*)/',$APPLICATION->GetCurUri(),$preg_url);
                $email = $preg_url[3];

                preg_match('/(.*)(\S)([0-9][0-9])(.*)/',$email,$preg_email);

                $true_email = $preg_email[1]."@".$preg_email[4];

                $cook= $_COOKIE["ghTfq4"];
                // так нужно , первые несколько попыток $USER->Login отдает просто null
                sleep(1);
                $res = $USER->Login(strip_tags($true_email), strip_tags($cook), 'Y');
                $res = $USER->Login(strip_tags($true_email), strip_tags($cook), 'Y');
                $res = $USER->Login(strip_tags($true_email), strip_tags($cook), 'Y');
                $res = $USER->Login(strip_tags($true_email), strip_tags($cook), 'Y');
                $res = $USER->Login(strip_tags($true_email), strip_tags($cook), 'Y');
                $res = $USER->Login(strip_tags($true_email), strip_tags($cook), 'Y');
                $res = $USER->Login(strip_tags($true_email), strip_tags($cook), 'Y');
                $res = $USER->Login(strip_tags($true_email), strip_tags($cook), 'Y');
                $res = $USER->Login(strip_tags($true_email), strip_tags($cook), 'Y');
                $res = $USER->Login(strip_tags($true_email), strip_tags($cook), 'Y');
                $res = $USER->Login(strip_tags($true_email), strip_tags($cook), 'Y');
                unset($_COOKIE["ghTfq4"]);

                LocalRedirect("/personal-cabinet/");
            }
            ?>
            <div class="alert <?= ($APPLICATION->arAuthResult ["TYPE"] == "OK" ? "alert-success" : "alert-danger") ?>"><?= nl2br(htmlspecialcharsbx($text)) ?></div>
        <? } else {
            ?>
            <form class="form-recovery_main" id="recovery-password" method="post" action="<?= $arResult["AUTH_FORM"] ?>" name="bform">
                <? if (strlen($arResult["BACKURL"]) > 0): ?>
                    <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                <? endif ?>
                <input type="hidden" name="AUTH_FORM" value="Y">
                <input type="hidden" name="TYPE" value="CHANGE_PWD">

                <? if ($arResult["PHONE_REGISTRATION"]):?>
                    <div class="bx-authform-formgroup-container input-style d-none">
                        <label><? echo GetMessage("change_pass_phone_number") ?></label>
                        <div class="input__wrap">
                            <input type="text" value="<?= htmlspecialcharsbx($arResult["USER_PHONE_NUMBER"]) ?>"
                                   disabled="disabled"/>
                            <input type="hidden" name="USER_PHONE_NUMBER"
                                   value="<?= htmlspecialcharsbx($arResult["USER_PHONE_NUMBER"]) ?>"/>
                        </div>
                    </div>
                    <div class="bx-authform-formgroup-container input-style d-none">
                        <div class="input__wrap">
                            <label class="input__wrap_label"><? echo GetMessage("change_pass_code") ?></label>
                            <input type="text" name="USER_CHECKWORD" maxlength="255"
                                   value="<?= $arResult["USER_CHECKWORD"] ?>" autocomplete="off"/>
                        </div>
                    </div>
                <? else:?>
                    <div class="bx-authform-formgroup-container input-style d-none" style="display: none">
                        <div class="input__wrap">
                            <label class="input__wrap_label"><?= GetMessage("AUTH_LOGIN") ?></label>
                            <input type="text" name="USER_LOGIN" maxlength="255"
                                   value="<?= $arResult["LAST_LOGIN"] ?>"/>
                        </div>
                    </div>

                    <div class="bx-authform-formgroup-container input-style d-none" style="display: none">
                        <div class="input__wrap">
                            <label class="input__wrap_label"><?= GetMessage("AUTH_CHECKWORD") ?></label>
                            <input type="text" name="USER_CHECKWORD" maxlength="255"
                                   value="<?= $arResult["USER_CHECKWORD"] ?>" autocomplete="off"/>
                        </div>
                    </div>
                <? endif ?>

                <div class="bx-authform-formgroup-container input-style">
                    <div class="input__wrap">

                        <label class="input__wrap_label"><?= GetMessage("AUTH_NEW_PASSWORD_REQ") ?></label>
                        <? if ($arResult["SECURE_AUTH"]):?>
                            <div class="bx-authform-psw-protected" id="bx_auth_secure" style="display:none">
                                <div class="bx-authform-psw-protected-desc">
                                    <span></span><? echo GetMessage("AUTH_SECURE_NOTE") ?></div>
                            </div>

                            <script type="text/javascript">
                              document.getElementById('bx_auth_secure').style.display = '';
                            </script>
                        <? endif ?>
                        <input type="password" id="pass" name="USER_PASSWORD" maxlength="255"
                               value="<?= $arResult["USER_PASSWORD"] ?>" autocomplete="off"/>
                    </div>
                </div>

                <div class="bx-authform-formgroup-container input-style">
                    <div class="input__wrap">
                        <label class="input__wrap_label"><?= GetMessage("AUTH_NEW_PASSWORD_CONFIRM") ?></label>
                        <? if ($arResult["SECURE_AUTH"]):?>
                            <div class="bx-authform-psw-protected" id="bx_auth_secure_conf" style="display:none">
                                <div class="bx-authform-psw-protected-desc">
                                    <span></span><? echo GetMessage("AUTH_SECURE_NOTE") ?></div>
                            </div>

                            <script type="text/javascript">
                              document.getElementById('bx_auth_secure_conf').style.display = '';
                            </script>
                        <? endif ?>
                        <input type="password" id="pass_conf" name="USER_CONFIRM_PASSWORD" maxlength="255"
                               value="<?= $arResult["USER_CONFIRM_PASSWORD"] ?>" autocomplete="off"/>
                    </div>
                </div>
                <div class="bx-authform-note-container">
                    <p><? echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"]; ?></p>
                </div>

                <? if ($arResult["USE_CAPTCHA"]):?>
                    <input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>

                    <div class="bx-authform-formgroup-container input-style">
                        <div class="input__wrap">
                            <label class="input__wrap_label"><? echo GetMessage("system_auth_captcha") ?></label>
                            <div class="bx-captcha"><img
                                        src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>"
                                        width="180" height="40" alt="CAPTCHA"/></div>
                            <div class="bx-authform-input-container">
                                <input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off"/>
                            </div>
                        </div>
                    </div>

                <? endif ?>

                <div class="bx-authform-formgroup-container">
                    <input type="submit" class="mainBtn  main-btn_fullwidth save" name="change_pwd"
                           value="<?= GetMessage("AUTH_CHANGE") ?>"/>
                </div>


            </form>
        <?php } ?>

    </div>
</section>


<? if ($arResult["PHONE_REGISTRATION"]): ?>

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
            function(response) {
              var errorNode = BX('bx_chpass_error');
              errorNode.innerHTML = '';
              for (var i = 0; i < response.errors.length; i++) {
                errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) +
                    '<br />';
              }
              errorNode.style.display = '';
            },
      });


    </script>

    <div class="alert alert-danger" id="bx_chpass_error" style="display:none"></div>

    <div id="bx_chpass_resend"></div>

<? endif ?>
<script>

  $(document).on('click', '.save', function(e) {

    e.preventDefault();
    let pass2 = $('#pass_conf').val();
    let pass1 = $('#pass').val();

    if (pass1.length < 6) {
      $('#pass_conf').after('   <div class="popover_error">\n' +
          '          <div class="popover_error_arrow"></div>\n' +
          '          <div class="popover_error_image">\n' +
          '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
          '          </div>\n' +
          '          <div class="popover_error_text">\n' +
          '        Пароль должен быть не менее 6 символов длиной.\n' +
          '      </div>\n' +
          '      </div>');

    } else {

      if (pass2.length > 0) {
        if (pass2.length > pass1.length) {
          $('#pass_conf').after('   <div class="popover_error">\n' +
              '          <div class="popover_error_arrow"></div>\n' +
              '          <div class="popover_error_image">\n' +
              '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
              '          </div>\n' +
              '          <div class="popover_error_text">\n' +
              '       Пароль подтверждения длинее основного.\n' +
              '      </div>\n' +
              '      </div>');
        } else if (pass2.length < pass1.length) {
          $('#pass_conf').after('   <div class="popover_error">\n' +
              '          <div class="popover_error_arrow"></div>\n' +
              '          <div class="popover_error_image">\n' +
              '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
              '          </div>\n' +
              '          <div class="popover_error_text">\n' +
              '       Пароль подтверждения короче основного.\n' +
              '      </div>\n' +
              '      </div>');
        } else {
          if (pass1 != pass2) {
            $('#pass_conf').after('   <div class="popover_error">\n' +
                '          <div class="popover_error_arrow"></div>\n' +
                '          <div class="popover_error_image">\n' +
                '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
                '          </div>\n' +
                '          <div class="popover_error_text">\n' +
                '        Пароли не совпадают.\n' +
                '      </div>\n' +
                '      </div>');
          }else{
            setCookie("ghTfq4",pass1);
            $("#recovery-password").submit();
          }
        }
      } else {
        $('#pass_conf').after('   <div class="popover_error">\n' +
            '          <div class="popover_error_arrow"></div>\n' +
            '          <div class="popover_error_image">\n' +
            '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
            '          </div>\n' +
            '          <div class="popover_error_text">\n' +
            '        Пароли не совпадают.\n' +
            '      </div>\n' +
            '      </div>');
      }
    }
  });


</script>
<script type="text/javascript">
  document.bform.USER_CHECKWORD.focus();
</script>

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
?>

<section class="personalCabinet">
    <ul class="breadcrumbs">
        <li><a href="/">Главная</a></li>
        <li>Восстановление пароля</li>
    </ul>
    <h1 class="page-title">Восстановление пароля</h1>
    <div class="white_block block-recovery_pass">

        <?
        if (!empty($APPLICATION->arAuthResult)) {
            $text = "Данные для востановления пароля высланы вам на почту.";
            ?>
            <div class="alert  <?= ($APPLICATION->arAuthResult["TYPE"] == "OK" ? "alert-success" : "alert-danger") ?>"><?= nl2br(htmlspecialcharsbx($text)) ?></div>
        <? } else {
            ?>


            <!--        <p class="bx-authform-note-container">--><? //=GetMessage("AUTH_FORGOT_PASSWORD_2")
            ?><!--</p>-->

            <form name="bform" method="post" target="_top" action="<?= $arResult["AUTH_URL"] ?>">
                <? if ($arResult["BACKURL"] <> ''): ?>
                    <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
                <? endif ?>
                <input type="hidden" name="AUTH_FORM" value="Y">
                <input type="hidden" name="TYPE" value="SEND_PWD">

                <div class="bx-authform-formgroup-container">
                    <div class="input__wrap">
                        <label class="input__wrap_label"><? echo GetMessage("AUTH_LOGIN_EMAIL") ?></label>
                        <input type="text" name="USER_LOGIN" placeholder="example@gmail.com" maxlength="255" value=""/>
                        <input type="hidden" name="USER_EMAIL"/>
                    </div>
                    <div class="bx-authform-note-container"><? echo GetMessage("forgot_pass_email_note") ?></div>
                </div>

                <? if ($arResult["PHONE_REGISTRATION"]): ?>
                    <div class="bx-authform-formgroup-container">
                        <div class="bx-authform-label-container"><? echo GetMessage("forgot_pass_phone_number") ?></div>
                        <div class="bx-authform-input-container">
                            <div class="input__wrap">
                                <input type="text" name="USER_PHONE_NUMBER" maxlength="255" value=""/>
                            </div>
                        </div>
                        <div class="bx-authform-note-container"><? echo GetMessage("forgot_pass_phone_number_note") ?></div>
                    </div>
                <? endif ?>

                <? if ($arResult["USE_CAPTCHA"]): ?>
                    <input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>

                    <div class="bx-authform-formgroup-container">
                        <div class="bx-authform-label-container"><? echo GetMessage("system_auth_captcha") ?></div>
                        <div class="bx-captcha"><img
                                    src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"] ?>"
                                    width="180" height="40" alt="CAPTCHA"/></div>
                        <div class="bx-authform-input-container">
                            <div class="input__wrap">
                                <input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off"/>
                            </div>
                        </div>
                    </div>

                <? endif ?>

                <div class="bx-authform-formgroup-container">
                    <input type="submit" class="mainBtn main-btn_fullwidth send_account_info" name="send_account_info"
                           value="<?= GetMessage("AUTH_SEND") ?>"/>
                </div>

            </form>
        <?php } ?>

    </div>
</section>

<script type="text/javascript">


  $('.send_account_info').click(function(e) {
    e.preventDefault();
    var data = $('[name=bform]').serializeArray();

    if (data[3]['value'] === '') {

      $('[name=' + data[3]['name'] + ']').after('   <div class="popover_error">\n' +
          '          <div class="popover_error_arrow"></div>\n' +
          '          <div class="popover_error_image">\n' +
          '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
          '          </div>\n' +
          '          <div class="popover_error_text">\n' +
          '        Введите email.\n' +
          '      </div>\n' +
          '      </div>');
    } else {

      var pattern = new RegExp('^[a-z0-9.-]+@[a-z.-]+.[a-z]{2,6}$');

      if (pattern.test(data[3]['value'])) {

        $.ajax({
          dataType: 'html',
          url: '/ajax/personal-cabinet/already_email.php',
          type: 'POST',
          data: {'email': data[3]['value']},
          beforeSend: function() {

          },
          success: function(msg) {
            if (msg > 0) {
              document.bform.USER_EMAIL.value = document.bform.USER_LOGIN.value;

              $('[name=bform]').submit();
            } else {
              $('[name=' + data[3]['name'] + ']').after('   <div class="popover_error">\n' +
                  '          <div class="popover_error_arrow"></div>\n' +
                  '          <div class="popover_error_image">\n' +
                  '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
                  '          </div>\n' +
                  '          <div class="popover_error_text">\n' +
                  '        Пользователя с таким  email не существует.\n' +
                  '      </div>\n' +
                  '      </div>');
            }
          },
        }).done(function(msg) {

        });

      } else {

        $('[name=' + data[3]['name'] + ']').after('   <div class="popover_error">\n' +
            '          <div class="popover_error_arrow"></div>\n' +
            '          <div class="popover_error_image">\n' +
            '          <img src="/local/templates/kdteam/images/svg/error.svg" alt="Ошибка">\n' +
            '          </div>\n' +
            '          <div class="popover_error_text">\n' +
            '        Введите email в нужном формате (example@gmail.com).\n' +
            '      </div>\n' +
            '      </div>');
      }
    }
  });

</script>

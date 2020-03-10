<!-- Popup Always at the bottom -->
<?php
 require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
 require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/classes/general/captcha.php");
$cpt = new CCaptcha();

$captchaPass = COption::GetOptionString("main","captcha_password","");
if(strlen($captchaPass) <= 0){
    $captchaPass = randString(10);
    COption::SetOptionString("main","captcha_password",$captchaPass);
}
$cpt->SetCodeCrypt($captchaPass);
?>

<form id="feedback_modal" class="auth-form" enctype="multipart/form-data" >
    <div class="close-modal">
        <img src="/local/templates/kdteam/images/svg/close_modal.svg" alt="">
    </div>

    <div class="popup__wrap">
        <div class="popup__wrap_tabs">
            <h3 class="popup__wrap_tabs_title">
                Напишите нам
            </h3>
        </div>
        <div class="popup__wrap_middle">
            <!-- Input -->
            <div class="input__wrap">
                <label class="input__wrap_label">Имя</label>
                <input id="name" type="text" name="name" required>
            </div>
            <!-- Input -->
            <div class="input__wrap">
                <label class="input__wrap_label">Электронная почта</label>
                <input id="email" type="email" name="email" required>
            </div>
        </div>
        <div class="block_padd-modal">
            <div class="input__wrap">
                <textarea class="textarea_input" minlength="10" name="text" required></textarea>
            </div>
        </div>
        <div class="input__wrap block_padd-modal">
            <label class="input__wrap_label">Прикрепить к сообщению файлы(максимум 5):</label>
            <div class="file-input file_input_half">
                <button class="button mainBtn"  id="btn_myFileInput">.png .jpeg</button>
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
        <div class="block_padd-modal">
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
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
            <div class="popup__wrap_tabs_title">
                Напишите нам
            </div>
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
                <textarea minlength="10" name="text" required></textarea>
            </div>
        </div>
        <div class="input__wrap block_padd-modal">
            <label class="input__wrap_label">Прикрепить к сообщению файлы(максимум 5):</label>
            <div class="file-input file_input_half">
                <input type="file" name="file" class="file-simple"
                       accept="image/*" multiple>
                <span class="button smallAccentBtn">Выберите файл</span>
                <span class="label label_name" data-js-label>.png .jpeg</span>
                <span class="label block-error-label">Максимальный размер файла 10mb</span>
            </div>
        </div>
        <div class="block_captcha block_padd-modal">
            <input type="hidden" name="captcha_code" value="<?php echo htmlspecialcharsbx($cpt->GetCodeCrypt()) ?>">
            <img src="/bitrix/tools/captcha.php?captcha_code=<?php echo htmlspecialcharsbx($cpt->GetCodeCrypt()) ?>" alt="">
            <input type="text" name="captcha_word" id="captcha_word">
            <p>здесь будет каптча</p>
        </div>
        <div class="block_padd-modal">
            <button type="submit" class="mainBtn" id="ask">Отправить</button>
        </div>
    </div>
</form>
<script>

  /*add file input*/
  $(document).ready(function() {
    var inputs = document.querySelectorAll('.file-input')

    for (var i = 0, len = inputs.length; i < len; i++) {
      customInput(inputs[i])
    }

    function customInput (el) {
      const fileInput = el.querySelector('[type="file"]')
      const label = el.querySelector('[data-js-label]')

      fileInput.onchange =
          fileInput.onmouseout = function () {
            if (!fileInput.value) return

            var value = fileInput.value.replace(/^.*[\\\/]/, '')
            el.className += ' -chosen'
            label.innerText = value
          }
    };
  });
</script>
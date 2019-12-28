<!-- Popup Always at the bottom -->
<form id="feedback_modal" class="auth-form">
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
                <input id="" type="text" name="login" required>
            </div>
            <!-- Input -->
            <div class="input__wrap">
                <label class="input__wrap_label">Электронная почта</label>
                <input id="" type="email" name="email" required>
            </div>
        </div>
        <div class="block_padd-modal">
            <div class="input__wrap">
                <textarea minlength="10" name="" required></textarea>
            </div>
        </div>
        <div class="input__wrap block_padd-modal">
            <label class="input__wrap_label">Прикрепить к сообщению файлы(максимум 5):</label>
            <div class="file-input file_input_half">
                <input type="file" name="file" class="file-simple"
                       accept="image/*">
                <span class="button smallAccentBtn">Выберите файл</span>
                <span class="label label_name" data-js-label>.png .jpeg</span>
                <span class="label block-error-label">Максимальный размер файла 10mb</span>
            </div>
        </div>
        <div class="block_captcha block_padd-modal">
            <p>здесь будет каптча</p>
        </div>
        <div class="block_padd-modal">
            <button class="mainBtn">Отправить</button>
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
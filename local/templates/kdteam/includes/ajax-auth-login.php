<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");?>
<form id="auth-form-login" class="auth-form">
    <div class="close-modal">
        <img src="/local/templates/kdteam/images/svg/close_modal.svg" alt="">
    </div>

    <div class="popup__wrap">
        <div id="auth__form" class="popup__wrap_tabs">
            <h3 class="popup__wrap_tabs_title">
                Вход
            </h3>
        </div>
        <p class="message error"></p>

        <div class="popup__wrap_tabs_tab">
            <div id="popup-login-content" class="popup__wrap_logIn">

                <!-- Input -->
                <div class="input__wrap">
                    <label class="input__wrap_label">Логин / Электронная почта</label>
                    <input id="name" type="text" name="login" required>
                </div>

                <!-- Input -->
                <div class="input__wrap">
                    <label class="input__wrap_label">Пароль</label>
                    <input type="password" name="password" required>
                </div>
                
                <div class="popup-login-content__links">
                    <a href="/personal-cabinet/?forgot_password=yes" class="popup-login-content__links__item">
                        Забыли пароль ?
                    </a>
                    <a id="trigger-reg-form" class="popup-login-content__links__item">
                        Зарегистрироваться
                    </a>
                </div>

                <div class="center__child">
                    <button class="mainBtn">Вход</button>
                </div>
            </div>

            <!-- <div id="popup-reg-content" class="popup__wrap_reg"></div> -->
        </div>
    </div>
</form>

    <script>

    </script>

<?require_once($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/include/epilog_after.php");

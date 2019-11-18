<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");?>
<form id="auth-form-login" class="auth-form">
    <div class="close-modal">
        <img src="./local/templates/kdteam/images/svg/close_modal.svg" alt="">
    </div>

    <div class="popup__wrap">
        <div id="auth__form" class="popup__wrap_tabs">
            <div class="popup__wrap_tabs_title">
                Вход
            </div>
        </div>
        <div class="message error"></div>

        <div class="popup__wrap_tabs_tab">
            <div id="popup-login-content" class="popup__wrap_logIn">

                <!-- Input -->
                <div class="input__wrap">
                    <label class="input__wrap_label">Логин</label>
                    <input id="name" type="text" name="login" required>
                </div>

                <!-- Input -->
                <div class="input__wrap">
                    <label class="input__wrap_label">Пароль</label>
                    <input type="password" name="password" required>
                </div>
                
                <a href="#" class="input__lost-pass">
                    Забыли пароль ?
                </a>

                <button class="mainBtn">Вход</button>
            </div>

            <!-- <div id="popup-reg-content" class="popup__wrap_reg"></div> -->
        </div>
    </div>
</form>

<?require_once($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/include/epilog_after.php");

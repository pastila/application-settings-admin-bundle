<?php
use Bitrix\Main\Application;
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
?>
<!-- Popup Always at the bottom -->
<form id="auth-form-reg" class="auth-form">
    <div class="close-modal">
        <img src="./local/templates/kdteam/images/svg/close_modal.svg" alt="">
    </div>

    <div class="popup__wrap">
        <div id="auth__form" class="popup__wrap_tabs">
            <div class="popup__wrap_tabs_title">
Регистрация
            </div>
        </div>

        <div class="popup__wrap_top">
            <!-- Input -->
            <div class="input__wrap">
                <label class="input__wrap_label">Электронная почта</label>
                <input id="email" type="email" name="email" required>
            </div>

            <!-- Input -->
            <div class="input__wrap">
                <label class="input__wrap_label">Ваше имя</label>
                <input id="name" type="text" name="name" required>
            </div>
            <div class="input__wrap">
                <label class="input__wrap_label">Ваш номер телефона</label>
                <input id="phone" type="tel" name="phone" data-inputmask="'mask': '+38 099 999 99 99'" required>
            </div>
        </div>

        <div class="popup__wrap_middle">
            <!-- Input -->
            <div class="input__wrap">
                <label class="input__wrap_label">Ваша фамилия</label>
                <input id="famaly-name" type="text" name="famaly-name" required>
            </div>

            <!-- Input -->
            <div class="input__wrap">
                <label class="input__wrap_label">Ваше отчество</label>
                <input id="last-name" type="text" name="last-name" required>
            </div>

            <div class="input__wrap">
                <label class="input__wrap_label">Номер страхового полиса</label>
                <input id="number_polic" type="text" name="number_polic" required>
            </div>
            <div class="input__wrap">
                <label class="input__wrap_label">Пароль</label>
                <input id="password"  minlength="6" type="password" name="password" required>
            </div>
        </div>

        <div class="popup__wrap_bottom">
            <!-- Input -->
            <div class="input__wrap">
                <label class="input__wrap_label">Укажите свою страховую компанию</label>
                <input id="company" type="text" name="company" required>

                <div class="search_company"></div>
            </div>

        </div>

        <button type="submit" id="registration" class="mainBtn">Регистрация</button>
    </div>
</form>

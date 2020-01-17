<?php
use Bitrix\Main\Application;
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");




?>
<!-- Popup Always at the bottom -->
<form id="auth-form-reg" class="auth-form">
    <div class="close-modal">
        <img src="/local/templates/kdteam/images/svg/close_modal.svg" alt="">
    </div>

    <div class="popup__wrap">
        <p class="register_before_review error hidden">
            Для создания обращение вам необходимо зарегистрироваться
        </p>
        <div id="auth__form" class="popup__wrap_tabs">
            <div class="popup__wrap_tabs_title">
                Регистрация
            </div>
        </div>

        <div class="popup__wrap_top">
            <!-- Input -->
            <div class="input__wrap">
                <label class="input__wrap_label">Фамилия</label>
                <input id="famaly-name" type="text" name="famaly-name" required>
            </div>

            <!-- Input -->
            <div class="input__wrap">
                <label class="input__wrap_label">Имя</label>
                <input id="name" type="text" name="name" required>
            </div>

            <!-- Input -->
            <div class="input__wrap">
                <label class="input__wrap_label">Отчество</label>
                <input id="last-name" type="text" name="last-name" required>
            </div>
        </div>
        <div class="popup__wrap_middle">
            <div class="input__wrap">
                <label class="input__wrap_label">Дата рождения</label>
                <input class="datepicker-here" type="text" name="time" autocomplete="off" required >
                <div class="danger date" style="display: none;">Регистрация лиц, не достигших 18 лет, не допускается</div>
            </div>
            <!-- Input -->
            <div class="input__wrap">
                <label class="input__wrap_label">Электронная почта</label>
                <input id="email" type="email" name="email" required>
            </div>

        </div>
        <div class="popup__wrap_middle">
            <div class="input__wrap">
                <label class="input__wrap_label">Ваш номер телефона</label>
                <input id="phone" type="tel" pattern="\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}" name="phone"
                       data-mask="+7 (000) 000 00 00"
                       placeholder="+7 (___) ___ __ __"  required>
                <p id="sms_confirm_error" style="display: none" class="error sms-error">Введите номер телефона</p>
            </div>
            <div class="input__wrap hidden_wrap_phone flex_hidden_wrap_phone main_btn-parent">
                <input type="button" class="accept-phone-js blue-bg" value="Подтвердить номер телефона">
            </div>
            <div class="input__wrap" id="sms_confirm" style="display: none">
                <div class="input_phone-flex">
                    <label class="input__wrap_label">Введите код подтверждения</label>
                    <input id="check-code-js" name="sms-code" maxlength="5" class="check-code-js" type="text">
                </div>
                <div class="input_phone-flex main_btn-parent">
                <input type="button" class="sms-again-button mainBtn" value="Не получили SMS? Отправить повторно.">
                </div>
            </div>
        </div>
        <div class="popup__wrap_middle">
            <div class="input__wrap">
                <label class="input__wrap_label">Номер страхового полиса (6ти значный)</label>
                <input id="number_polic" type="text" minlength="16" maxlength="16" name="number_polic" required>
            </div>
            <div class="input__wrap">
                <label class="input__wrap_label">Пароль</label>
                <input id="password" minlength="6" type="password" name="password" required>
            </div>
            <div class="input__wrap">
                <label class="input__wrap_label">Подтвердите пароль</label>
                <input id="pass_conf" minlength="6" type="password" name="pass_conf" required>
            </div>
        </div>
        <div class="popup__wrap_middle">
            <div class="input__wrap">
                <label class="input__wrap_label" for="user_pass">Регион в котором застрахованы по ОМС</label>
                <div class="block_relative">
                    <div class="input__ico">
                        <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                    </div>
                    <input id="referal"  value="" type="text" placeholder="Поиск по региону" autocomplete="off"/>
                    <ul style="cursor: pointer;" class="custom-serach__items" id="search_result">
                        <?
                        $arOrder = Array("name"=>"asc");
                        $arFilter = Array("IBLOCK_ID"=>16);
                        $res = CIBlockSection::GetList($arOrder, $arFilter, false );
                        while($ob = $res->GetNext()){

                            ?>
                            <li value="<?=$ob["ID"]?>" class="custom-serach__items_item region_reg " data-id-city="<?=$ob["ID"]?>"><?=$ob["NAME"]?></li>

                        <?  }?>
                    </ul>
                </div>
            </div>
            <div class="input__wrap">
                <label class="input__wrap_label" for="user_pass">Укажите свою страховую компанию </label>
                <div class="block_relative">
                    <div class="input__ico">
                        <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                    </div>
                    <input id="referal_two" value="" type="text" placeholder="Поиск по компаниям" autocomplete="off"/>
                    <ul style="cursor: pointer;" class="custom-serach__items" id="search_result_hospital">

                    </ul>
                </div>
            </div>
        </div>
        <div class="wrap-chrckbox checkbox_registration checkbox_registration_modal">
            <label class="check-label">
                Я ознакомлен и согласен с условиями <a target="_blank" href="/terms-of-use/">пользовательского соглашения</a> и
                <a target="_blank" href="/personal-data-processing/">политикой по обработке персональных данных</a>
                <input type="checkbox" value="" />
                <span class="check-img check-img_reg"></span>
        </div>
        <div class="popup__wrap_bottom center__child">
        <button type="submit" id="registration" class="mainBtn">Регистрация</button>
    </div>
</form>

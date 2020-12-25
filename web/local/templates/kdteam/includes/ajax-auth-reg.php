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

    <div class="popup__wrap" style="position: relative0; left: 0;">
        <p class="register_before_review error hidden">
            Для создания обращение вам необходимо зарегистрироваться
        </p>
        <div id="auth__form" class="popup__wrap_tabs">
            <h3 class="popup__wrap_tabs_title">
                Регистрация
            </h3>
        </div>

        <div class="popup__wrap_top">
            <!-- Input -->
            <div class="input__wrap" >
                <label class="input__wrap_label">Фамилия</label>
                <input id="famaly-name" type="text" name="famaly-name" pattern="^[А-ЯЁ][а-яё]*([-][А-ЯЁ][а-яё]*)*$"
                       placeholder="Фамилия" required>

            </div>

            <!-- Input -->
            <div class="input__wrap" >
                <label class="input__wrap_label">Имя</label>
                <input id="name" type="text" name="name" pattern="^[А-ЯЁ][а-яё]*([-][А-ЯЁ][а-яё]*)*$"
                       placeholder="Имя" required>
            </div>

            <!-- Input -->
            <div class="input__wrap" >
                <label class="input__wrap_label">Отчество</label>
                <input id="last-name" type="text" name="last-name" pattern="^[А-ЯЁ][а-яё]*([-][А-ЯЁ][а-яё]*)*$"
                       placeholder="Отчество" required>
            </div>

            <div class="input__wrap">
                <label class="input__wrap_label">Дата рождения</label>
<?$adult= date('Y-m-d', strtotime("-6570 day"));?>
                <input id="datepicker_reg0" class="datepicker-here" type="date" name="time" autocomplete="off" required 
                      placeholder="DD.MM.YYYY" max="<?=$adult?>" title="Регистрация лиц, не достигших 18 лет, не допускается">
                <div class="danger date" style="display: none;">Регистрация лиц, не достигших 18 лет, не допускается</div>
            </div>

        </div>
<style>
input[type="date"]::-webkit-clear-button,
input[type="date"]::-webkit-inner-spin-button,
input[type="date"]::-webkit-calendar-picker-indicator
{ display: none; }
</style>

        <div class="popup__wrap_middle popup__wrap_middle_align-flex-end">
            <div class="input__wrap" >
                <label class="input__wrap_label" for="user_pass">Регион в котором застрахованы по ОМС</label>
                <div class="block_relative">
                    <div class="input__ico">
                        <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                    </div>
                    <input id="referal"  value="" type="text" placeholder="Поиск по региону" required autocomplete="off"/>
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
            <div class="input__wrap" >
                <label class="input__wrap_label" for="user_pass">Укажите свою страховую компанию </label>
                <div class="block_relative">
                    <div class="input__ico">
                        <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                    </div>
                    <input id="referal_two" value="" class="referal_two_reg" type="text" placeholder="Поиск по компаниям"  required autocomplete="off"/>
                    <ul style="cursor: pointer;" class="custom-serach__items" id="search_result_hospital">

                    </ul>
                </div>
            </div>
            <div class="input__wrap">
                <label class="input__wrap_label">Номер страхового полиса (шестнадцатизначный номер)</label>
                <input  title="Номер страхового полиса должен состоять из цифр!" id="number_polic"
                        name="number_polic" class="numberInput" minlength="16" maxlength="16"
                        placeholder="0000000000000000" required>
            </div>
        </div>



        <div class="popup__wrap_middle">
            <div class="input__wrap">
                <label class="input__wrap_label">Электронная почта</label>
                <input id="email" type="email" name="email" pattern="^[a-z0-9\.\-]+@[a-z]+\.[a-z]{2,6}$" required placeholder="Электронная почта">
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

        <div class="popup__wrap_middle popup__wrap_middle_align-flex-end0">
            <div class="input__wrap">
                <div class="input__wrap--column">
                    <label class="input__wrap_label">Ваш номер телефона</label>
                    <input style="width0: 60%;padding0: 0 2rem;" id="phone" type="tel" pattern="\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}" name="phone"
                           data-mask="+7 (000) 000 00 00"
                           placeholder="+7 (___) ___ __ __"  required>
                    <p id="sms_confirm_error" style="display: none" class="error sms-error">Введите номер телефона</p>
                    <p id="tel_confirm_error" style="display: none" class="error sms-error">
                        Пользователь с таким телефоном уже существует
                    </p>
                </div>
            </div>
            <div class="input__wrap hidden_wrap_phone flex_hidden_wrap_phone main_btn-parent">
                <input type="button" class="accept-phone-js whiteBtn" value="Подтвердить номер телефона">
            </div>
            <div class="wrap-chrckbox checkbox_registration checkbox_registration_modal ">
                <label class="check-label relative_block" >
                    Я ознакомлен и согласен с условиями <a target="_blank" href="/terms-of-use/">пользовательского соглашения</a> и
                    <a target="_blank" href="/personal-data-processing/">политикой по обработке персональных данных</a>
                    <input type="checkbox" required value="" />
                    <span class="check-img check-img_reg"></span>
                </label>
            </div>
            <div class="input__wrap" id="sms_confirm" style="display: none">
                <div class="input__wrap--column">
                <div class="input_phone-flex">
                    <label class="input__wrap_label">Введите код подтверждения</label>
                    <input id="check-code-js" onkeyup='checkParams()' name="sms-code" maxlength="5" class="check-code-js" type="text">
                </div>
                </div>
                <div class="input_phone-flex main_btn-parent">
                <input type="button" class="sms-again-button whiteBtn" value="Не получили SMS? Отправить повторно возможно через 30 секунд.">
                </div>
            </div>
            <div class="input__wrap adc" style="margin-top0: 70px;">
              <button type="submit" id="registration" class="mainBtn" disabled style="background-color:grey">Регистрация</button>
            </div>
        </div>
        <div class="popup__wrap_bottom center__child">
        
    </div>
</form>
<style>
@media (min-width: 992px){
.adc {
   margin-top: 70px;
}
}
</style>
<script>
function checkParams() {
    var kod = $('#check-code-js').val();

    if(kod.length != 0) {
        $('#registration').removeAttr('disabled');
        $('#registration').attr("style","background-color:#0866a3");
    } else {
        $('#registration').attr('disabled', 'disabled');
        $('#registration').attr("style","background-color:grey");
    }
}
</script>
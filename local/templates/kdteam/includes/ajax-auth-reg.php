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
                <label class="input__wrap_label">Ваша фамилия</label>
                <input id="famaly-name" type="text" name="famaly-name" required>
            </div>

            <!-- Input -->
            <div class="input__wrap">
                <label class="input__wrap_label">Ваше имя</label>
                <input id="name" type="text" name="name" required>
            </div>

            <!-- Input -->
            <div class="input__wrap">
                <label class="input__wrap_label">Ваше отчество</label>
                <input id="last-name" type="text" name="last-name" required>
            </div>

        </div>
        <div class="popup__wrap_middle">
            <!-- Input -->
            <div class="input__wrap">
                <label class="input__wrap_label">Электронная почта</label>
                <input id="email" type="email" name="email" required>
            </div>
            <div class="input__wrap">
                <label class="input__wrap_label">Ваш номер телефона</label>
                <input id="phone" type="tel" pattern="\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}" name="phone"
                       data-mask="+7 (000) 000 00 00"
                       placeholder="+7 (___) ___ __ __"  required>
            </div>
            <div class="input__wrap">
                <label class="input__wrap_label">Номер страхового полиса</label>
                <input id="number_polic" type="text" name="number_polic" required>
            </div>
            <div class="input__wrap">
                <label class="input__wrap_label">Пароль</label>
                <input id="password"  minlength="6" type="password" name="password" required>
            </div>
            <div class="input__wrap">
                <label class="input__wrap_label">Подтвердите пароль</label>
                <input id="pass_conf"  minlength="6" type="password" name="pass_conf" required>
            </div>
            <div class="input__wrap">
                <label class="input__wrap_label">Дата рождение</label>
                <input class="datepicker-here" type="text" name="time"  >
                <div class="danger date" style="display: none;">Регистрация лиц, не достигших 18 лет, не допускается</div>
            </div>
            <div class="uu">sds</div>
        </div>

        <div class="popup__wrap_bottom">
            <!-- Input -->
            <div class="input__wrap select_block">
                <label class="input__wrap_label">Регион в котором заключали договор</label>

                <select  class="select-selected" id="sel_reg" required>
                    <option>Выберите регион</option>
                    <?php
                    $arSelect = Array("ID", "IBLOCK_ID", "NAME");
                    $arFilter = Array("IBLOCK_ID"=> 16, "ACTIVE"=>"Y" ,"%NAME"=>$_POST["name"]);
                    $res = CIBlockSection::GetList(false, $arFilter, false, $arSelect,false);
                    while($ob = $res->GetNext()) {
                        $arFields = $ob;
                        echo '<option class="region_reg" value="' . $arFields['ID'] . '">' . $arFields["NAME"] . '</option>';
                    }
                    ?>
                </select>

                <div class="search_company scrollbar"></div>
            </div>

            <div class="input__wrap select_block">
                <label class="input__wrap_label">Укажите свою страховую компанию</label>
                <select class="select-selected" id="oms_company" required>
                    <option>Вы не выбрали регион</option>
                </select>
                <input value="" id="company" type="hidden" name="company" required>
            </div>
        </div>

        <button type="submit" id="registration" class="mainBtn">Регистрация</button>
    </div>
</form>

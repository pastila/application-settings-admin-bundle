<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);


?>
<?php foreach ($arResult["ITEMS"] as $arItem) {
    $sect_id = "";
    $phone = "";
    $phone2 = "";
    $phone3 = "";
    if (CModule::IncludeModule("iblock")) {

        $res = CIBlockElement::GetByID($arItem["PROPERTIES"]['COMPANY']['VALUE']);
        if ($ar_res = $res->GetNextElement()) {
            $ar_Field = $ar_res->GetFields();
            if($ar_Field["ACTIVE"] == 'Y') {
                $ar_property = $ar_res->GetProperties();

                if ($ar_property["MOBILE_NUMBER"]["VALUE"] != "") {
                    $phone = $ar_property["MOBILE_NUMBER"]["VALUE"];
                }
                if ($ar_property["MOBILE_NUMBER2"]["VALUE"] != "") {
                    $phone2 = $ar_property["MOBILE_NUMBER2"]["VALUE"];
                }
                if ($ar_property["MOBILE_NUMBER3"]["VALUE"] != "") {
                    $phone3 = $ar_property["MOBILE_NUMBER3"]["VALUE"];
                }
                $hospital_id = $ar_Field['ID'];

                $logo_company = CFile::GetFileArray($ar_property["LOGO_IMG"]['VALUE']);

                $hospital = $ar_Field['~NAME'];

            }
            $sect_id = $ar_Field['IBLOCK_SECTION_ID'];
        }
    }
    $arFilter = array("IBLOCK_ID" => 16, 'ID' => $sect_id);
    $res_reg = CIBlockSection::GetList(
        array(),
        $arFilter,
        false,
        array()
    );
    if ($ob = $res_reg->GetNext()) {

    }
    ?>

        <div class="flex_personal white_block" id="element_<?= $arItem['ID'] ?>">
            <div class="personal_data">
                <div class="flex_data">
                    <div class="item_data">
                        <p>Фамилия</p>
                    </div>
                    <div class="item_data">
                        <p><?= $arItem["PROPERTIES"]['SURNAME']['VALUE']; ?></p>
                    </div>
                </div>
                <div class="flex_data">
                    <div class="item_data">
                        <p>Имя</p>
                    </div>
                    <div class="item_data">
                        <p><?= $arItem["NAME"]; ?></p>
                    </div>
                </div>
                <div class="flex_data">
                    <div class="item_data">
                        <p>Отчество</p>
                    </div>
                    <div class="item_data">
                        <p><?= $arItem["PROPERTIES"]['PARTONYMIC']['VALUE']; ?></p>
                    </div>
                </div>
                <div class="flex_data">
                    <div class="item_data">
                        <p>Дата рождения</p>
                    </div>
                    <div class="item_data">
                        <p><?= $arItem["PROPERTIES"]['BIRTHDAY']['VALUE']; ?></p>
                    </div>
                </div>
                <div class="flex_data">
                    <div class="item_data">
                        <p>Cтраховой полис</p>
                    </div>
                    <div class="item_data">
                        <p><?= $arItem["PROPERTIES"]['POLICY']['VALUE']; ?></p>
                    </div>
                </div>
                <div class="flex_data">
                    <div class="item_data">
                        <p>Регион страхования</p>
                    </div>
                    <div class="item_data">
                        <p><?= $ob["NAME"] ?></p>
                    </div>
                </div>
                <div class="flex_data">
                    <div class="item_data">
                        <p>Cтраховая компания</p>
                    </div>
                    <div class="item_data">
                        <div class="logo_block">
                            <img src="<?=$logo_company["SRC"]?>" alt="<?php htmlspecialchars($hospital); ?>">
                        </div>
                        <p><?= $hospital ?></p>
                    </div>
                </div>
                <div class="flex_data">
                    <div class="item_data">
                        <p>Горячая линия компании</p>
                    </div>
                    <div class="item_data">
                        <p><?= $phone ?></p>
                        <p><?= $phone2 ?></p>
                        <p><?= $phone3 ?></p>
                    </div>
                </div>
            </div>

            <div class="submit_button submit_button-child edit_block">
                <button id="edit_<?= $arItem['ID'] ?>" class="edit_js mainBtn submit_button-child_button">Редактировать</button>
                <button id="del_<?= $arItem['ID'] ?>" class="del_js accentBtn submit_button-child_button">Удалить</button>
            </div>
        </div>
        <div id="edit_children_<?= $arItem['ID'] ?>" style="display: none">
            <form onsubmit="return false" id="edit_children_form_<?= $arItem['ID'] ?>" action=""
                  enctype="multipart/form-data">
                <div class="flex_personal white_block">
                    <div class="personal_data">
                        <div class="flex_data">
                            <div class="item_data">
                                <p>Имя</p>
                            </div>
                            <div class="item_data input__wrap">
                                <input id="children_name_add_<?= $arItem['ID'] ?>" required type="text" name="name"
                                       value="<?= $arItem["NAME"]; ?>">
                            </div>
                        </div>
                        <div class="flex_data">
                            <div class="item_data">
                                <p>Фамилия</p>
                            </div>
                            <div class="item_data input__wrap">
                                <input id="children_last_name_add_<?= $arItem['ID'] ?>" required type="text" name="last_name"
                                       value="<?= $arItem["PROPERTIES"]['SURNAME']['VALUE']; ?>">
                            </div>
                        </div>
                        <div class="flex_data">
                            <div class="item_data">
                                <p>Отчество</p>
                            </div>
                            <div class="item_data input__wrap">
                                <input id="children_second_name_add_<?= $arItem['ID'] ?>" required type="text"
                                       name="second_name"
                                       value="<?= $arItem["PROPERTIES"]['PARTONYMIC']['VALUE']; ?>">

                            </div>
                        </div>
                        <div class="flex_data">
                            <div class="item_data">
                                <p>Дата рождения</p>
                            </div>
                            <div class="item_data input__wrap">
                                <input class="datepicker-here" required type="text" name="time" readonly
                                       value="<?= $arItem["PROPERTIES"]['BIRTHDAY']['VALUE']; ?>"
                                       id="children_birthday_add_<?= $arItem['ID'] ?>"
                                       placeholder="DD.MM.YYYY"
                                       pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}">
                            </div>
                        </div>
                        <div class="flex_data">
                            <div class="item_data">
                                <p>Cтраховой полис</p>
                            </div>
                            <div class="item_data input__wrap">
                                <input title="Номер страхового полиса должен состоять из цифр!"
                                       class="numberInput" required type="text"
                                       id="child_policy_add_<?= $arItem['ID'] ?>" minlength="16"
                                       maxlength="16"
                                       name="uf_insurance_policy" value="<?= $arItem["PROPERTIES"]['POLICY']['VALUE']; ?>">
                            </div>
                        </div>

                        <div id="hospitals_<?= $arItem['ID'] ?>" class="region_child">
                            <?php
                            $APPLICATION->IncludeComponent(
                                "bitrix:catalog.section.list",
                                "choise_hospital_edit",
                                array(
                                    "VIEW_MODE" => "LIST",
                                    "SHOW_PARENT_NAME" => "N",
                                    "IBLOCK_TYPE" => "",
                                    "IBLOCK_ID" => "16",
                                    "SECTION_ID" => $sect_id,
                                    "SECTION_CODE" => "",
                                    "SECTION_URL" => "",
                                    "COUNT_ELEMENTS" => "N",
                                    "TOP_DEPTH" => "1",
                                    "SECTION_FIELDS" => "",
                                    "SECTION_USER_FIELDS" => "",
                                    "ADD_SECTIONS_CHAIN" => "N",
                                    "CACHE_TYPE" => "A",
                                    "CACHE_TIME" => "36000000",
                                    "CACHE_NOTES" => "",
                                    "CACHE_GROUPS" => "Y",
                                    "HOSPITAL" => $hospital_id,
                                    "HOSPITAL_NAME" => $hospital,
                                    "ID_ELEM" => $arItem['ID'],
                                )
                            ); ?>
                        </div>
                    </div>
                    <div class="submit_button submit_button-child edit_block">
                        <button class="mainBtn submit_button-child_button" type="submit" id="save_edit_<?= $arItem['ID'] ?>">
                            Сохранить
                        </button>
                        <button class="accentBtn submit_button-child_button" type="submit" id="cancel_edit_<?= $arItem['ID'] ?>">
                            Отмена
                        </button>
                    </div>
                </div>
            </form>
        </div>



<?php } ?>


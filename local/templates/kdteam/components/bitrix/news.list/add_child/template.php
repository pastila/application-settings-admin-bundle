<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
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
    if (CModule::IncludeModule("iblock")) {
        $res = CIBlockElement::GetByID($arItem["PROPERTIES"]['COMPANY']['VALUE']);
        if ($ar_res = $res->GetNext()) {
            $hospital_id = $ar_res['ID'];
            $hospital = $ar_res['~NAME'];
            $sect_id = $ar_res['IBLOCK_SECTION_ID'];
        }
    }
    ?>
    <div id="element_<?=$arItem['ID']?>" class="personal_data">
        <div class="flex_data">
            <div class="item_data">
                <p>Имя</p>
            </div>
            <div class="item_data">
                <p><?=$arItem["NAME"];?></p>
            </div>
        </div>
        <div class="flex_data">
            <div class="item_data">
                <p>Фамилия</p>
            </div>
            <div class="item_data">
                <p><?=$arItem["PROPERTIES"]['SURNAME']['VALUE'];?></p>
            </div>
        </div>
        <div class="flex_data">
            <div class="item_data">
                <p>Отчество</p>
            </div>
            <div class="item_data">
                <p><?=$arItem["PROPERTIES"]['PARTONYMIC']['VALUE'];?></p>
            </div>
        </div>
        <div class="flex_data">
            <div class="item_data">
                <p>Cтраховой полис</p>
            </div>
            <div class="item_data">
                <p><?=$arItem["PROPERTIES"]['POLICY']['VALUE'];?></p>
            </div>
        </div>
        <div class="flex_data">
            <div class="item_data">
                <p>Cтраховая компания</p>
            </div>
            <div class="item_data">
                <p><?=$hospital?></p>
            </div>
        </div>
        <div class="submit_button submit_button-child">
            <button id="edit_<?=$arItem['ID']?>" class="edit_js mainBtn margin-button main-button-styles">Редактировать</button>
            <button id="del_<?=$arItem['ID']?>" class="del_js mainBtn  main-button-styles">Удалить</button>
        </div>
    </div>
    <div class="personal_data" id="edit_children_<?=$arItem['ID']?>" style="display: none">
        <form  onsubmit="return false" id="edit_children_form_<?=$arItem['ID']?>" action="" enctype="multipart/form-data">
            <div class="flex_data">
                <div class="item_data">
                    <p>Имя</p>
                </div>
                <div class="item_data input__wrap">
                    <input id="children_name_add_<?=$arItem['ID']?>" required type="text" name="name" value="<?=$arItem["NAME"];?>">
                </div>
            </div>
            <div class="flex_data">
                <div class="item_data">
                    <p>Фамилия</p>
                </div>
                <div class="item_data input__wrap">
                    <input id="children_last_name_add_<?=$arItem['ID']?>" required type="text" name="last_name"
                           value="<?=$arItem["PROPERTIES"]['SURNAME']['VALUE'];?>">
                </div>
            </div>
            <div class="flex_data">
                <div class="item_data">
                    <p>Отчество</p>
                </div>
                <div class="item_data input__wrap">
                    <input id="children_second_name_add_<?=$arItem['ID']?>" required type="text" name="second_name"
                           value="<?=$arItem["PROPERTIES"]['PARTONYMIC']['VALUE'];?>">

                </div>
            </div>
            <div class="flex_data">
                <div class="item_data">
                    <p>Cтраховой полис</p>
                </div>
                <div class="item_data input__wrap">
                    <input required type="text" id="child_policy_add_<?=$arItem['ID']?>"  minlength="16" maxlength="16"
                           name="uf_insurance_policy" value="<?=$arItem["PROPERTIES"]['POLICY']['VALUE'];?>">
                </div>
            </div>
            <div id="hospitals_<?=$arItem['ID']?>" class="region_child">
                <?php
                $APPLICATION->IncludeComponent(
                    "bitrix:catalog.section.list",
                    "choise_hospital_edit",
                    array(
                        "VIEW_MODE" => "LIST",
                        "SHOW_PARENT_NAME" => "N",
                        "IBLOCK_TYPE" => "",
                        "IBLOCK_ID" => "9",
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
                );?>
            </div>
            <div class="submit_button submit_button-child">
                <button class="mainBtn margin-button main-button-styles" type="submit" id="save_edit_<?=$arItem['ID']?>">Сохранить</button>
                <button class="mainBtn main-button-styles" type="submit" id="cancel_edit_<?=$arItem['ID']?>">Отмена</button>
            </div>
        </form>
    </div>
    <div class="edit_block">

    </div>




<?php }?>


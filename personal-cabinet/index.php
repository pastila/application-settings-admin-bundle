<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;
$asset = Asset::getInstance();
CModule::IncludeModule('iblock');
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/personal-cabinet/personal-cabinet.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/personal-cabinet/personal-cabinet.min.js");
global $USER;
if ($_GET['forgot_password'] == 'yes' and !$USER->IsAuthorized()) {
    $APPLICATION->IncludeComponent(
        "bitrix:system.auth.forgotpasswd",
        "flat",
        array()
    );
} elseif ($_GET['change_password'] == 'yes' and !$USER->IsAuthorized()) {
    $APPLICATION->IncludeComponent(
        "bitrix:system.auth.changepasswd",
        "flat",
        array()
    );
} elseif ($USER->IsAuthorized()) {
$ID_USER = $USER->GetID();
$rsUser = CUser::GetByID($ID_USER);
$person = $rsUser->Fetch();

$ID_company = $person["UF_INSURANCE_COMPANY"];

    $logo_user = CFile::GetFileArray($person["PERSONAL_PHOTO"]);
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_LOGO_IMG","PROPERTY_MOBILE_NUMBER");
$arFilter = Array("IBLOCK_ID"=>16, "ID"=>$ID_company);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement()){
    $arFields = $ob->GetFields();

  $logo_company = CFile::GetFileArray($arFields["PROPERTY_LOGO_IMG_VALUE"]);

}
    $arFilter = Array('IBLOCK_ID'=>16, 'ID'=>$person["UF_REGION"]);
    $db_list = CIBlockSection::GetList(Array($by=>$order), $arFilter, true);
    if($ar_result = $db_list->GetNext()){
      
    }

?>

<div class="personal_cabinet">
    <ul class="breadcrumbs">
        <? if ($detect->isTablet() || $detect->isMobile()) { ?>
            <li><a href="/" class="">Личный кабинет</a></li>
        <? } else { ?>
            <li><a href="/">Главная</a></li>
            <li>Личный кабинет</li>
        <? } ?>

    </ul>
    <h1 class="page-title">Мои данные</h1>
    <?php if ($person["UF_REPRESENTATIVE"] == "1") { ?>
    <div class="user_vip"><p class="text_vip">Аккаунт представителя страховой службы</p></div>
    <?php } ?>

    <div class="flex_personal">
       <div class="personal_data" id="true_data_person">

            <div class="flex_data">
                <div class="item_data">
                    <p>Фамилия</p>
                </div>
                <div class="item_data">
                    <p><?=$person["LAST_NAME"];?></p>
                </div>
            </div>
           <div class="flex_data">
               <div class="item_data">
                   <p>Имя</p>
               </div>
               <div class="item_data">
                   <p><?=$person["NAME"];?></p>
               </div>
           </div>
            <div class="flex_data">
                <div class="item_data">
                    <p>Отчество</p>
                </div>
                <div class="item_data">
                    <p><?=$person["SECOND_NAME"];?></p>
                </div>
            </div>

           <div class="flex_data">
               <div class="item_data">
                   <p>Номер телефона</p>
               </div>
               <div class="item_data">
                   <p><?=$person["PERSONAL_PHONE"];?></p>
               </div>
           </div>
            <div class="flex_data">
                <div class="item_data">
                    <p>E-mail</p>
                </div>
                <div class="item_data">
                    <p><?=$person["EMAIL"];?></p>
                </div>
            </div>
           <div class="flex_data">
               <div class="item_data">
                   <p>Cтраховой полис</p>
               </div>
               <div class="item_data">
                   <p><?=$person["UF_INSURANCE_POLICY"];?></p>
               </div>
           </div>
           <div class="flex_data">
               <div class="item_data">
                   <p>Регион страхования</p>
               </div>
               <div class="item_data">
                   <p><?=$ar_result["NAME"];?></p>
               </div>
           </div>
           <div class="flex_data">
               <div class="item_data">
                   <p>Cтраховая компания</p>
               </div>
               <div class="item_data">
                   <div class="logo_block">
                       <img src="<?=$logo_company["SRC"]?>">
                   </div>
                   <p><?=$arFields["NAME"]?></p>
               </div>
           </div>
           <div class="flex_data">
               <div class="item_data">
                   <p>Горячая линия компании</p>
               </div>
               <div class="item_data">
                  <p><?=$arFields["PROPERTY_MOBILE_NUMBER_VALUE"]?></p>
               </div>
           </div>
       </div>
       <div class="personal_data" id="for_change_person" style="display: none">

           <form id="form_change_data" action="" enctype="multipart/form-data">
               <div class="flex_data">
                   <div class="item_data">
                       <p>Фамилия</p>
                   </div>
                   <div class="item_data input__wrap">
                       <input type="text" name="last_name" value="<?=$person["LAST_NAME"];?>">
                   </div>
               </div>
           <div class="flex_data">
               <div class="item_data">
                   <p>Имя</p>
               </div>
               <div class="item_data input__wrap">
                   <input type="text" name="name" value="<?=$person["NAME"];?>">
               </div>
           </div>

           <div class="flex_data">
               <div class="item_data">
                   <p>Отчество</p>
               </div>
               <div class="item_data input__wrap">
                   <input type="text" name="second_name" value="<?=$person["SECOND_NAME"];?>">

               </div>
           </div>

           <div class="flex_data">
               <div class="item_data">
                   <p>Номер телефона</p>
               </div>
               <div class="item_data input__wrap">
                   <input type="text" name="personal_phone" maxlength="16" data-mask="+7 (000) 000 00 00" placeholder="+7 (___) ___ __ __" value="<?=$person["PERSONAL_PHONE"];?>">
               </div>
           </div>
           <div class="flex_data">
               <div class="item_data">
                   <p>E-mail</p>
               </div>
               <div class="item_data input__wrap">
                   <input type="text" name="email" class="input_email" value="<?=$person["EMAIL"];?>">

               </div>
           </div>
           <div class="flex_data">
               <div class="item_data">
                   <p>Cтраховой полис</p>
               </div>
               <div class="item_data input__wrap">

                   <input type="text"  minlength="16" maxlength="16" name="uf_insurance_policy" value="<?=$person["UF_INSURANCE_POLICY"];?>">

               </div>
           </div>

               <div class="flex_data">
               <div class="item_data">
                   <p>Аватарка</p>
               </div>
               <div class="item_data file-input">
                   <input type="file" name="file" class="input_file"
                          accept="image/*">
                   <span class="button smallAccentBtn">Выберите файл</span>
                   <span class="label" data-js-label>.png .jpeg</span>
                   <span class="label error-inputs block-error-label">Максимальный размер файла 10mb</span>
                   <span class="label error-inputs block-error-label_size" style="display: none" >Размер файла превышен</span>
                   <span class="label error-inputs block-error-label_format" style="display: none" >Не вервый формат</span>
               </div>
           </div>
               <div class="feedback__top">
                   <div class="custom-select custom-select-js-cite styles-select-personal">


                       <div class="input__wrap half-wrap_input">
                           <label class="input__wrap_label" for="user_pass">Выбор региона: </label>
                           <div class="block_relative">
                               <div class="input__ico">
                                   <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                               </div>




                               <input id="referal"  value="<?php echo $ar_result["NAME"]  ?>" type="text" data-id_region="0" placeholder="Поиск по региону" autocomplete="off"/>
                               <ul style="cursor: pointer;" class="custom-serach__items" id="search_result">
                                   <?
                                   $arOrder = Array("name"=>"asc");
                                   $arFilter = Array("IBLOCK_ID"=>16);
                                   $res = CIBlockSection::GetList($arOrder, $arFilter, false );
                                   while($ob = $res->GetNext()){

                                       ?>
                                       <li value="<?=$ob["ID"]?>" class="custom-serach__items_item region " data-id-city="<?=$ob["ID"]?>"><?=$ob["NAME"]?></li>

                                   <?  }?>
                               </ul>
                           </div>
                       </div>
                       <div class="input__wrap half-wrap_input">
                           <label class="input__wrap_label" for="user_pass">Список страховых компаний : </label>
                           <div class="block_relative search-second" style="pointer-events: none">
                               <div class="input__ico">
                                   <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                               </div>
                               <input id="referal_two" value="<?= $arFields["NAME"]?>" type="text" data-id_region="0" placeholder="Поиск страховых компаний " autocomplete="off"/>
                               <ul style="cursor: pointer;" class="custom-serach__items" id="search_result_hospital">

                               </ul>
                           </div>
                       </div>







                   </div>


               </div>
              <div class="submit_button">
                  <button class="mainBtn" type="submit" id="save_data">Сохранить</button>
              </div>
           </form>

       </div>
       <div class="edit_block">
           <a  class="accentBtn" id="change-data">Редактировать данные</a>
           <div class="photo_user">
               <img src="<?=$logo_user["SRC"]?>" alt="">
           </div>
       </div>
   </div>
    <div class="info_child">
        <p>Если у вас есть несовершеннолетние дети или иные лица, для которых вы являетесь законным представителем, вы
            можете внести данные о них, чтобы иметь возможность действовать на <a href="http://bezbahil.ru/">bezbahil.ru</a> в их интересах
            - направлять в страховую компанию заявление о возврате средств, уплаченных за медицинскую помощь, предусмотренную
            программой ОМС, или писать отзыв о работе страховой компании
        </p>
    </div>

    <a class="mainBtn main-button-styles" id="add_children_btn">Добавить ребенка</a>
    <div class="flex_personal">
        <div class="personal_data" id="add_children" style="display: none">
            <form  onsubmit="return false" id="add_children_form" action="" enctype="multipart/form-data">
                <h2 class="small-page-title">Заполните данные опекаемого человека</h2>

                <div class="flex_data">
                    <div class="item_data">
                        <p>Фамилия</p>
                    </div>
                    <div class="item_data input__wrap">
                        <input id="children_last_name_add" required type="text" name="last_name" value="">
                    </div>
                </div>
                <div class="flex_data">
                    <div class="item_data">
                        <p>Имя</p>
                    </div>
                    <div class="item_data input__wrap">
                        <input id="children_name_add" required type="text" name="name" value="">
                    </div>
                </div>
                <div class="flex_data">
                    <div class="item_data">
                        <p>Отчество</p>
                    </div>
                    <div class="item_data input__wrap">
                        <input id="children_second_name_add" required type="text" name="second_name" value="">

                    </div>
                </div>
                <div class="flex_data">
                    <div class="item_data">
                        <p>Дата рождения</p>
                    </div>
                    <div class="item_data input__wrap">
                        <input class="datepicker-here" required type="text" name="time" value=""
                               id="children_birthday_add"
                               placeholder="DD.MM.YYYY"
                               pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}"
                               autocomplete="off">
                    </div>
                </div>
                <div class="flex_data">
                    <div class="item_data">
                        <p>Cтраховой полис</p>
                    </div>
                    <div class="item_data input__wrap">
                        <input required type="text" id="child_policy_add"  minlength="16" maxlength="16" name="uf_insurance_policy" value="">
                    </div>
                </div>
                <div id="hospitals" class="region_child">
                    <?php
                    $APPLICATION->IncludeComponent(
                        "bitrix:catalog.section.list",
                        "choice_hospital",
                        array(
                            "VIEW_MODE" => "LIST",
                            "SHOW_PARENT_NAME" => "N",
                            "IBLOCK_TYPE" => "",
                            "IBLOCK_ID" => "16",
                            "SECTION_ID" => "",
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
                            "CACHE_GROUPS" => "Y"
                        )
                    );?>
                </div>
                <div class="submit_button submit_button-child">
                    <button class="mainBtn main-button-styles" type="submit" id="save_children">Сохранить</button>
                    <button class="accentBtn" type="submit" id="cancel">Отмена</button>
                </div>
            </form>
        </div>
        <div class="edit_block">

        </div>
    </div>
    <div id="cur_children">

        <?php
        $id_section ="";

        $arFilter = Array('IBLOCK_ID' => 21, '=NAME' => $person["ID"]);
                $db_list = CIBlockSection::GetList(Array(), $arFilter, false);
                if ($ar_result = $db_list->GetNext()) {

                    $id_section = $ar_result['ID'];

                }
if($id_section != "") {
    $APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "add_child",
        array(
            "DISPLAY_DATE" => "Y",
            "DISPLAY_NAME" => "Y",
            "DISPLAY_PICTURE" => "Y",
            "DISPLAY_PREVIEW_TEXT" => "Y",
            "AJAX_MODE" => "N",
            "IBLOCK_TYPE" => "",
            "IBLOCK_ID" => "21",
            "FILTER_NAME" => "arrFilter",
            "NEWS_COUNT" => "100",
            "SORT_BY1" => "SORT",
            "SORT_ORDER1" => "ASC",
            "CHECK_DATES" => "Y",
            "SET_TITLE" => "N",
            "SET_BROWSER_TITLE" => "N",
            "SET_META_KEYWORDS" => "N",
            "SET_META_DESCRIPTION" => "N",
            "SET_LAST_MODIFIED" => "N",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "ADD_SECTIONS_CHAIN" => "N",
            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
            "PARENT_SECTION" => $id_section,
            "PARENT_SECTION_CODE" => "",
            "INCLUDE_SUBSECTIONS" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "CACHE_FILTER" => "Y",
            "CACHE_GROUPS" => "Y",
            "DISPLAY_TOP_PAGER" => "Y",
            "DISPLAY_BOTTOM_PAGER" => "Y",
            "PAGER_TITLE" => "Новости",
            "PAGER_SHOW_ALWAYS" => "Y",
            "PAGER_TEMPLATE" => "",
            "PAGER_DESC_NUMBERING" => "Y",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL" => "Y",
            "PAGER_BASE_LINK_ENABLE" => "Y",
            "SET_STATUS_404" => "Y",
            "SHOW_404" => "Y",
            "MESSAGE_404" => "",
            "PAGER_BASE_LINK" => "",
            "PAGER_PARAMS_NAME" => "arrPager",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "N",
            "AJAX_OPTION_HISTORY" => "N",
            "AJAX_OPTION_ADDITIONAL" => "",
            "PROPERTY_CODE" => array("PROPERTY_*")
        )
    );
}
        ?>
    </div>

</div>
    <?}else{
   LocalRedirect("/",false,"301 Moved permanently");
}?>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>
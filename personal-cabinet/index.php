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


?>

<div class="personal_cabinet">
    <h1 class="page-title">Мои данные</h1>
    <?php if ($person["UF_REPRESENTATIVE"] == "1") { ?>
    <div class="user_vip"><p class="text_vip">Аккаунт представителя страховой службы</p></div>
    <?php } ?>

    <div class="flex_personal">
       <div class="personal_data" id="true_data_person">
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
                    <p>Фамилия</p>
                </div>
                <div class="item_data">
                    <p><?=$person["LAST_NAME"];?></p>
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
                   <p>Имя</p>
               </div>
               <div class="item_data input__wrap">
                   <input type="text" name="name" value="<?=$person["NAME"];?>">
               </div>
           </div>
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
                   <span class="label error-inputs block-error-label_format" style="display: none" >Не первый формат</span>
               </div>
           </div>
               <div class="feedback__top">
                   <div class="custom-select custom-select-js-cite">
                       <div class="title-select" data-select="city">Ваш Город</div>



                       <div class="danger" style="display: none" >Выбирете город</div>
                       <select style="display:none;">
                           <?php
                           $db_list=   CIBlockSection::GetList(
                               Array("SORT"=>"ASC"), Array('IBLOCK_ID'=>16,"ID"=>$person["UF_REGION"]), false);
                           $ar_result = $db_list->GetNext();

                           ?>
                           <option value="0" ><?=$ar_result["NAME"]?></option>

                           <?
                           $arOrder = Array("name"=>"asc");
                           $arFilter = Array("IBLOCK_ID"=>16);
                           $res = CIBlockSection::GetList($arOrder, $arFilter, false );
                           while($ob = $res->GetNext()){

                               ?>
                               <option value="<?=$ob["ID"]?>" data-id-city="<?=$ob["ID"]?>"><?=$ob["NAME"]?></option>

                           <?  }?>

                       </select>
                   </div>

                   <div class="custom-select  custom-select-js  no_click">

                       <div class="title-select" data-select="company">Страховая компания</div>
                       <div class="danger" style="display: none">Выбирете компанию</div>
                       <select style="display:none;">
                           <?php $arSelect = Array("ID", "IBLOCK_ID", "NAME");
                           $arFilter = Array("IBLOCK_ID"=>16,"ID"=>$person["UF_INSURANCE_COMPANY"] );
                           $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                           while($ob = $res->GetNextElement()){
                               $arProps = $ob->GetFields();

                           } ?>
                           <option value="0" ><?=$arProps["NAME"]?> </option>
                       </select>

                   </div>
               </div>
              <div class="submit_button">
                  <button class="mainBtn" type="submit" id="save_data">Сохранить</button>
              </div>
           </form>

       </div>
       <div class="edit_block">
           <a  class="mainBtn" id="change-data">Редактировать данные</a>
           <div class="photo_user">
               <img src="<?=$logo_user["SRC"]?>" alt="">
           </div>
       </div>
   </div>
    <a class="mainBtn" id="add_children_btn">Добавить ребенка</a>
    <div class="flex_personal">
        <div class="personal_data" id="add_children" style="display: none">
            <form  onsubmit="return false" id="add_children_form" action="" enctype="multipart/form-data">
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
                        <p>Фамилия</p>
                    </div>
                    <div class="item_data input__wrap">
                        <input id="children_last_name_add" required type="text" name="last_name" value="">
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
                        <p>Cтраховой полис</p>
                    </div>
                    <div class="item_data input__wrap">
                        <input required type="text" id="child_policy_add"  minlength="16" maxlength="16" name="uf_insurance_policy" value="">
                    </div>
                </div>
                <div id="hospitals" class="card">
                    <?php
                    $APPLICATION->IncludeComponent(
                        "bitrix:catalog.section.list",
                        "choice_hospital",
                        array(
                            "VIEW_MODE" => "LIST",
                            "SHOW_PARENT_NAME" => "N",
                            "IBLOCK_TYPE" => "",
                            "IBLOCK_ID" => "9",
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
                <div class="submit_button">
                    <button class="mainBtn" type="submit" id="save_children">Сохранить</button>
                    <button class="mainBtn" type="submit" id="cancel">Отмена</button>
                </div>
            </form>
        </div>
    </div>
    <div class="flex_personal" id="cur_children">
        <?php
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
                "PARENT_SECTION" => $arSection["ID"],
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
        ?>
    </div>

</div>
    <?}else{
   LocalRedirect("/",false,"301 Moved permanently");
}?>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>
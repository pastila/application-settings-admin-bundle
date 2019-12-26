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
$prop=CIBlockSection::GetByID($person["UF_REGION"])->GetNextElement()->GetFields();

?>

<div class="personal_cabinet">
    <h1 class="page-title">Мои данные</h1>
    <?php if($person["UF_REPRESENTATIVE"] == "1"){ ?>
    <div class="user_vip"><p class="text_vip">Аккаунт представителя страховой службы</p></div>
        <?}?>
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
                   <p>Ваш Номер телефона</p>
               </div>
               <div class="item_data">
                   <p><?=$person["PERSONAL_PHONE"];?></p>
               </div>
           </div>
            <div class="flex_data">
                <div class="item_data">
                    <p>Ваш e-mail</p>
                </div>
                <div class="item_data">
                    <p><?=$person["EMAIL"];?></p>
                </div>
            </div>
           <div class="flex_data">
               <div class="item_data">
                   <p>Ваш страховой полис</p>
               </div>
               <div class="item_data">
                   <p><?=$person["UF_INSURANCE_POLICY"];?></p>
               </div>
           </div>
           <div class="flex_data">
               <div class="item_data">
                   <p>Ваша страховая компания</p>
               </div>
               <div class="item_data">
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
           <div class="flex_data">
               <div class="item_data">
                   <p>Логотип вашей компании</p>
               </div>
               <div class="item_data">
                   <div class="logo_block">
                       <img src="<?=$logo_company["SRC"]?>">
                   </div>
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
                   <p>Ваш номер телефона</p>
               </div>
               <div class="item_data input__wrap">
                   <input type="text" name="personal_phone" maxlength="16" data-mask="+7 (000) 000 00 00" placeholder="+7 (___) ___ __ __" value="<?=$person["PERSONAL_PHONE"];?>">
               </div>
           </div>
           <div class="flex_data">
               <div class="item_data">
                   <p>Ваш e-mail</p>
               </div>
               <div class="item_data input__wrap">
                   <input type="text" name="email" class="input_email" value="<?=$person["EMAIL"];?>">

               </div>
           </div>
           <div class="flex_data">
               <div class="item_data">
                   <p>Ваш страховой полис</p>
               </div>
               <div class="item_data input__wrap">

                   <input type="text"  minlength="16" maxlength="16" name="uf_insurance_policy" value="<?=$person["UF_INSURANCE_POLICY"];?>">

               </div>
           </div>

               <div class="flex_data">
               <div class="item_data">
                   <p>Ваша аватарка</p>
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
                   <div class="custom-select custom-select-js-cite">


                       <label class="title-select " for="user_pass">Выбор региона: </label>
                       <div class="input__wrap">
                           <div class="input__ico">
                               <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                           </div>
                           <input id="referal"  value="<?php echo $prop["NAME"] ?>" type="text" placeholder="Поиск по региону" autocomplete="off"/>
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
                       <label class="title-select" for="user_pass">Список больниц : </label>
                       <div class="input__wrap">
                           <div class="input__ico">
                               <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                           </div>
                           <input id="referal_two" value="<?= $arFields["NAME"]?>" type="text" placeholder="Поиск по компаниям" autocomplete="off"/>
                           <ul style="cursor: pointer;" class="custom-serach__items" id="search_result_hospital">

                           </ul>
                       </div>







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
</div>
    <?}else{
   LocalRedirect("/",false,"301 Moved permanently");
}?>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>
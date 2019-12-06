<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;
$asset = Asset::getInstance();
CModule::IncludeModule('iblock');
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/personal-cabinet/personal-cabinet.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/personal-cabinet/personal-cabinet.min.js");
global $USER;
if($USER->IsAuthorized()){
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
                   <img src="<?=$logo_company["SRC"]?>">
               </div>
           </div>
       </div>







       <div class="personal_data" id="for_change_person" style="display: none">
           <form id="form_change_data" action="" enctype="multipart/form-data">
           <div class="flex_data">
               <div class="item_data">
                   <p>Имя</p>
               </div>
               <div class="item_data">
                   <input type="text" name="name" value="<?=$person["NAME"];?>">
               </div>
           </div>
           <div class="flex_data">
               <div class="item_data">
                   <p>Фамилия</p>
               </div>
               <div class="item_data">
                   <input type="text" name="last_name" value="<?=$person["LAST_NAME"];?>">
               </div>
           </div>
           <div class="flex_data">
               <div class="item_data">
                   <p>Отчество</p>
               </div>
               <div class="item_data">
                   <input type="text" name="second_name" value="<?=$person["SECOND_NAME"];?>">

               </div>
           </div>

           <div class="flex_data">
               <div class="item_data">
                   <p>Ваш Номер телефона</p>
               </div>
               <div class="item_data">
                   <input type="text" name="personal_phone" value="<?=$person["PERSONAL_PHONE"];?>">
               </div>
           </div>
           <div class="flex_data">
               <div class="item_data">
                   <p>Ваш e-mail</p>
               </div>
               <div class="item_data">
                   <input type="text" name="email" value="<?=$person["EMAIL"];?>">

               </div>
           </div>
           <div class="flex_data">
               <div class="item_data">
                   <p>Ваш страховой полис</p>
               </div>
               <div class="item_data">
                   <input type="text" name="uf_insurance_policy" value="<?=$person["UF_INSURANCE_POLICY"];?>">
               </div>
           </div>

               <div class="flex_data">
               <div class="item_data">
                   <p>Ваша аватарка</p>
               </div>
               <div class="item_data">
                   <input type="file" name="file" class="input_file"
                          accept="image/*">
                   <span class="label" data-js-label>.png .jpeg</span>
                   <span class="label block-error-label">Максимальный размер файла 10mb</span>
                   <span class="label block-error-label_size" style="display: none" >Размер файла превышен</span>
                   <span class="label block-error-label_format" style="display: none" >Не первый формат</span>
               </div>
           </div>
               <div class="feedback__top">
                   <div class="custom-select custom-select-js-cite">
                       <div class="title-select" data-select="city">Ваш Город</div>
                       <div class="danger" style="display: none" >Выбирете город</div>
                       <select>
                           <option value="0" >Выберите регион</option>

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
                       <select>
                           <option value="0" >Выберите регион: </option>
                       </select>

                   </div>
               </div>
               <button type="submit" id="save_data">Сохранить</button>
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
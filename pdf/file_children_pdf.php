<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use \Bitrix\Main\Loader as Loader;
use \Bitrix\Iblock as Iblock;
use Bitrix\Catalog;

use Bitrix\Main\Application;

Loader::includeModule('iblock');

require '/var/www/vendor/autoload.php';

global $USER;

$ID_child = $_POST["id"];
//$ID_child = "58480";
$data_user_oplata_POST = $_POST["oplata"];
$data_user_oformlenie_POST = date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());

$rsUser = CUser::GetByID($USER->GetID());
$person = $rsUser->Fetch();

$person_LAST_NAME = $person["LAST_NAME"];
$person_NAME = $person["NAME"];
$person_SECOND_NAME = $person["SECOND_NAME"];
$person_INSURANCE_POLICY = $person["UF_INSURANCE_POLICY"];
$person_EMAIL = $person["EMAIL"];
$person_PERSONAL_PHONE = $person["PERSONAL_PHONE"];
$person_LAST_NAME = $person["LAST_NAME"];
$PERSONAL_BIRTHDAT = $person["PERSONAL_BIRTHDAY"];

if ($_POST['id_obr']) {
    $arSel = array("ID", "IBLOCK_ID", "NAME", "PROPERTY_FULL_NAME", "PROPERTY_HOSPITAL", "PROPERTY_ADDRESS");
    $arFil = array("IBLOCK_ID" => 11,"ID" => $_POST['id_obr']);
    $res = CIBlockElement::GetList(Array(), $arFil, false, false, $arSel);
    $ob = $res->GetNextElement();
    $arFields = $ob->GetFields();
    $full_name_user = $arFields['PROPERTY_FULL_NAME_VALUE'];
    $hospital = $arFields['PROPERTY_HOSPITAL_VALUE'];
    $hospital_adress = $arFields['PROPERTY_ADDRESS_VALUE'];
} else {
    $full_name_user = $person_SECOND_NAME.' '. $person_NAME .' '. $person_LAST_NAME;
}

//Проверка на дублировние
$hospital_name = str_replace('&amp;', '', str_replace('quot;', '"', $hospital));

$res_hospital = CIBlockElement::GetList(
    array(),
    array('NAME' => $hospital_name, 'IBLOCK_ID' => 9),
    false,
    false,
    array("ID", "IBLOCK_ID", "NAME", "PROPERTY_FULL_NAME", "PROPERTY_MEDICAL_CODE", "IBLOCK_SECTION_ID")
);
while ($ob_hospital = $res_hospital->GetNextElement()) {
    $arFields = $ob_hospital->GetFields();
    $res = CIBlockSection::GetByID($arFields['IBLOCK_SECTION_ID']);
    if ($ar_res = $res->GetNext()) {
        if ($ar_res['NAME'] == $hospital_adress) {
            $FULL_NAME_HOSPITAL = $arFields['PROPERTY_FULL_NAME_VALUE'];
            $MEDICAL_CODE = $arFields['PROPERTY_MEDICAL_CODE_VALUE'];
        }
    }

}
//Проверка на дублировние



$arSelect_child = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_SURNAME","PROPERTY_PARTONYMIC","PROPERTY_POLICY","PROPERTY_COMPANY","PROPERTY_BIRTHDAY");
$arFilter_child = Array("IBLOCK_ID"=>21,"ID"=> $ID_child);
$res_child = CIBlockElement::GetList(Array(), $arFilter_child, false, false, $arSelect_child);

$ob_child = $res_child->GetNextElement();

$arFields_child = $ob_child->GetFields();


$SURNAME = $arFields_child["PROPERTY_SURNAME_VALUE"];
$NAME = $arFields_child["NAME"];

$PARTONYMIC = $arFields_child["PROPERTY_PARTONYMIC_VALUE"];
$id_company= $arFields_child["PROPERTY_COMPANY_VALUE"];
$polic = $arFields_child["PROPERTY_POLICY_VALUE"];
$BIRTHDAY= $arFields_child["PROPERTY_BIRTHDAY_VALUE"];


$arSelect = Array("ID", "IBLOCK_ID", "NAME", "IBLOCK_SECTION_ID","PROPERTY_*");
$arFilter = Array("IBLOCK_ID"=>16,"ID"=> $id_company);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
$ob = $res->GetNextElement();
$arProps = $ob->GetProperties();
$arFields = $ob->GetFields();





$name_hospital = $arFields["NAME"];// название компании

$NAME_BOSS = $arProps["NAME_BOSS"]["VALUE"];// руководитель компании
$email_CMO = $arProps["EMAIL_FIRST"]["VALUE"];// электронный адрес СМО
$email_CMO2 = $arProps["EMAIL_SECOND"]["VALUE"];// электронный адрес СМО
$email_CMO3 = $arProps["EMAIL_THIRD"]["VALUE"];// электронный адрес СМО

$res = CIBlockSection::GetByID($arFields['IBLOCK_SECTION_ID']);
$name_rerion = "";
if ($ar_res = $res->GetNext()) {

    $name_rerion = "(" .$ar_res["NAME"] . ")";


}

$html = '

<page>
 <div class="header">
 <table class="table" width="100%" style="margin-bottom: 10px;">
      <thead>
        <tr>
          <th valign="baseline" scope="col" style="text-align: left;">
           <div class="header__items_item--label">
                     Подготовлено с помощью сервиса bezbahil.ru
                </div>
                  <br>
                <div><img style="width: 75px;" src="logo_oms.png"/></div>
                  <br>
          <div class="header__items_item--label" style="font-weight: normal;">
                    Контактные данные - info@bezbahil.ru
                </div>
          </th>
           <th valign="baseline" style="float: right; text-align: left;">
 <div class="header__items">
            <div class="header__items_item">
                <div class="header__items_item_wrap">
                    <div class="header__items_item--label" style="font-weight: bold;">
                        Кому: <span style="font-weight: normal;">Руководителю страховой медицинской организации</span>
                    </div>
                      <br>
                    <div class="header__items_item--text">
                        <div class="blue-text cursive" style="font-style: italic; font-weight: normal;">
                            ' . $name_hospital  . $name_rerion . '<br>
                            ' . $NAME_BOSS . '<br>
                            ' . $email_CMO . '<br>
                            ' . $email_CMO2 . '<br>
                            ' . $email_CMO3 . '
                        </div>
                    </div>
                      <br>
                </div>
            </div>
            <div class="header__items_item">
                <div class="header__items_item_wrap">
                    <div class="header__items_item--label">
                        <span style="font-weight: bold;">От:</span> <span style="font-style: italic; font-weight: normal; font-weight: normal;">
                         ' . $full_name_user . ', ' . $PERSONAL_BIRTHDAT . '</span>
                    </div>
                </div>
                  <br>
            </div>
            <div class="header__items_item">
                <div class="header__items_item_wrap">
                    <div class="header__items_item--label">
                        <span style="font-weight: bold;">Номер полиса:</span> <span style="font-style: italic; font-weight: normal;">
                         ' . $person_INSURANCE_POLICY . '</span>
                    </div>
                </div>
                  <br>
            </div>
           <div class="header__items_item">
                <div class="header__items_item_wrap">
                    <div class="header__items_item--label" >
                        <span style="font-weight: bold;">Адрес электронной почты:</span>
                    </div>
                    <div class="header__items_item--text blue-text cursive" style=" font-style: italic; font-weight: normal;">
                        '. $person_EMAIL .'
                    </div>
                </div>
                  <br>
            </div>

            <div class="header__items_item">
                <div class="header__items_item_wrap">
                    <div class="header__items_item--label" >
                        <span style="font-weight: bold;">Телефон:</span> <span style="font-style: italic; font-weight: normal;">
                         ' . $person_PERSONAL_PHONE . '</span>
                    </div>
                </div>
                  <br>
            </div>
            <div class="header__items_item" >
                <div class="header__items_item_wrap">
                    <div class="header__items_item--label" >
                    <div>
                      <span style="font-weight: bold;">Действую в интересах лица, законным
                         представителем которого являюсь.</span>
                    </div>
                    <div>
                      <br>
                       <span style="font-weight: bold;">
                       Сведения о лице, получившем медицинскую помощь:</span>
                    </div>
                    </div>
                      <div class="header__items_item--text blue-text cursive" style=" font-style: italic; font-weight: normal;">
                        ' . $SURNAME . '
                        ' . $NAME . '
                        ' . $PARTONYMIC . ',
                        ' . $BIRTHDAY . '
                    </div>
                </div>
                  <br>
            </div>
            <div class="header__items_item">
                <div class="header__items_item_wrap">
                    <div class="header__items_item--label">
                        <span style="font-weight: bold;">Полис:</span> <span style="font-style: italic; font-weight: normal;">
                         ' . $polic . '</span>
                    </div>   
                </div>
            </div>
        </div>
            
           </th>
        </tr>
      </thead>
</table>
</div>

​   <div>
        <p style="text-align: center; margin-bottom: 5px; font-size: 15px; font-weight: bold;">ПРЕТЕНЗИЯ</p>
    <p>
        <span class="red-text cursive" style="font-style: italic;">«' . $data_user_oplata_POST . '</span> г. в медицинской организации 
        <span class="blue-text cursive" style="font-style: italic;">'.$FULL_NAME_HOSPITAL.'</span>
         (код в реестре медицинских организаций, осуществляющих деятельность в сфере ОМС 
         ' . $MEDICAL_CODE . ', ' . $hospital_adress . '),
         мною была совершена оплата медицинских
         услуг, что подтверждается прилагаемыми к настоящему письму документами.
    </p>
    <p>
Медицинские услуги были оказаны в связи с заболеванием, лечение которого, согласно Программе государственных
        гарантий бесплатного оказания гражданам медицинской помощи, финансируется из средств обязательного медицинского
        страхования.
    </p>
    <p>
В медицинских организациях, участвующих в реализации программы обязательного медицинского страхования, правилами
        предоставления платных медицинских услуг (утв. постановлением Правительства РФ от 04.12.2012 № 1006) не
        допускается взимание платы за медицинскую помощь с пациентов, застрахованных по ОМС, при их самостоятельном
        обращении за медицинской помощью в неотложной форме, а также при самостоятельном обращении в медицинскую
        организацию, выбранную для получения первичной медико-санитарной помощи.
    </p>
    <p>
При обращении в <span class="blue-text cursive" style="font-style: italic;">'.$hospital_name.'</span> мне не сообщили о возможности
        получения
        медицинской помощи бесплатно в
        сроки, установленные Территориальной программой государственных гарантий оказания гражданам бесплатной
        медицинской помощи, что является нарушением условий предоставления платных медицинских услуг. Мне было сообщено,
        что необходимая  медицинская помощь может быть оказана только на платной основе, и в случае отказа от
        оплаты помощь может быть оказана либо в сроки, значительно превышающие установленные Территориальной программой
        государственных гарантий, либо не будет оказана вообще.
    </p>
    <p>
Также мне не сообщали об особенностях течения заболевания, требующих получения дополнительных услуг, не
        предусмотренных программой обязательного медицинского страхования.
    </p>
    <p>
Таким образом, положения Правил предоставления платных медицинских услуг были нарушены, в результате чего я был
        вынужден оплатить медицинскую помощь, которая предусмотрена программой обязательного медицинского страхования.
    </p>
    <p>
В связи с изложенным, в рамках работы по защите прав на получение медицинской помощи по программе
        обязательного медицинского страхования, прошу организовать взаимодействие с медицинской организацией по возврату
        оплаченных мною средств.
    </p>
    <p>
Копию договора, документы, подтверждающие факт оплаты прилагаю.
    </p>
    <p>
Ответ прошу направить в составе электронного письма на указанный адрес электронной почты не позднее срока,
        установленного законом для рассмотрения обращений граждан.
    </p>
    <p>
При необходимости получения дополнительных сведений прошу связываться со мной по указанному телефону либо по
        электронной почте.
    </p>
    <p>
Приложение: документы, подтверждающие факт оплаты медицинских услуг – в электронном виде.
    </p>
    <span class="blue-text cursive" style="font-style: italic; margin-top: 5px;"> Дата формирования заявления ' . $data_user_oformlenie_POST.'</span>
    </div>
  </div>
</page>

';

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'orientation' => 'P',
    'margin_top' => 9,
    'margin_bottom' => 0,
    'margin_left' => 9,
    'margin_right' => 9,
    'default_font_size' => 9,
]);
//создаем PDF файл, задаем формат, отступы и.т.д.

//
$data= date('Y-m-d-h:i:s');
$name_file = "/var/www/upload/pdf/PDF_child_";
$name_file .= $data;
$name_file .= "_". $person_EMAIL. "_";
$name_file .= "file.pdf";

$mpdf->WriteHTML($html);
$mpdf->Output($name_file,'F');

$url_pdf_for_user = "/upload/pdf/PDF_child_".$data."_". $person_EMAIL. "_file.pdf";
$arFile = CFile::MakeFileArray($url_pdf_for_user);

$arProperty = Array(
    "PDF" => $arFile,
);

$ID_appeal = $_POST["id_obr"];
CIBlockElement::SetPropertyValuesEx($ID_appeal, 11, $arProperty);
CIBlockElement::SetPropertyValuesEx($ID_child, 11, $arProperty);

echo $url_pdf_for_user;


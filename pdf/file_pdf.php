<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use \Bitrix\Main\Loader as Loader;
use \Bitrix\Iblock as Iblock;
use Bitrix\Catalog;

use Bitrix\Main\Application;

Loader::includeModule('iblock');

require '/var/www/vendor/autoload.php';

global $USER;



//$data_user_oformlenie_POST = $_POST["data_user_oformlenie_POST"];
//$data_user_oplata_POST = $_POST["data_user_oplata_POST"];
//$number_polic_POST = $_POST["number_polic"];
$data_user_oformlenie_POST = "21.05.2018";
$data_user_oplata_POST = "21.06.2018";
$number_polic_POST = "polic_number";


$ID_user = $USER->GetID();

$rsUser = CUser::GetByID($ID_user);
$person = $rsUser->Fetch();

// echo '<pre>';
// print_r($person);
// echo '</pre>';

$email = $person["EMAIL"];
$mobail_number = $person["PERSONAL_MOBILE"];
$strahovay_compani = $person["UF_INSURANCE_COMPANY"];



$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");
$arFilter = Array("IBLOCK_ID"=>16,"NAME"=> $strahovay_compani);
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
$ob = $res->GetNextElement();
$arProps = $ob->GetProperties();
$arFields = $ob->GetFields();
//   echo '<pre>';
//    print_r($arProps);
//   echo '</pre>';
$boss_compani = "кто-то кто-тович  ктотов";
$mail_compani = $arProps["EMAIL_FIRST"]["VALUE"];
if($arProps["EMAIL_SECOND"]["VALUE"] !="") {
    $mail_compani .= "," . $arProps["EMAIL_SECOND"]["VALUE"];
}
if($arProps["EMAIL_THIRD"]["VALUE"] != "") {
    $mail_compani .= "," . $arProps["EMAIL_THIRD"]["VALUE"];
}


$res = CIBlockElement::GetByID($_SESSION["HOSPITAL"]);
if ($ar_res = $res->GetNext()) {
    $arHospital = $ar_res;
}
// $res = CIBlockSection::GetByID($_SESSION["REGION"]);
// if ($ar_res = $res->GetNext()) {
//     $arRegion = $ar_res;
// }
// $res = CIBlockSection::GetByID($_SESSION["CLASS"]);
// if ($ar_res = $res->GetNext()) {
//     $arClass = $ar_res;
// }
// $res = CIBlockSection::GetByID($_SESSION["GROUP"]);
// if ($ar_res = $res->GetNext()) {
//     $arGroup = $ar_res;
// }
// $res = CIBlockSection::GetByID($_SESSION["SUBGROUP"]);
// if ($ar_res = $res->GetNext()) {
//     $arSubGroup = $ar_res;
// }
// $res = CIBlockElement::GetByID($_SESSION["DIAGNOZ"]);
// if ($ar_res = $res->GetNext()) {
//     $arDiagnoz = $ar_res;
// }
// $arName = $USER->GetFullName();
// $arAppeal = $_SESSION["APPEAL"];



$html ='
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-weight: normal;
            font-size: calc(100vw / 50);
            padding: 2rem;
        }
        p {
            line-height: calc(100vw / 40);
        }
    </style>
<page>

 <div class="header" style="display: flex;flex-direction: column;width: 80%; margin-left: auto;">
        <div class="header__items" style="display: flex; flex-direction: column;  width: 100%; margin-bottom: 1rem;">
            <div class="header__items_item">
                <div class="header__items_item_wrap" style=" display: flex;">
                    <div class="header__items_item--label" style=" flex: 2; font-weight: bold;">
Кому:
                    </div>
​
                    <div class="header__items_item--text" style="flex: 5;">
                        <span class="bold-text" style="font-weight: bold;">Руководителю страховой медицинской организации</span>
​
                        <div class="blue-text cursive" style="color: rgb(55, 144, 223); font-style: italic;">
'.$strahovay_compani.',
                            '.$boss_compani.'
                            '.$mail_compani.'
                        </div>
                    </div>
                </div>
​
​
</div>
​
            <div class="header__items_item">
                <div class="header__items_item_wrap">
                    <div class="header__items_item--label">
От:
                    </div>
​
                    <div class="header__items_item--text blue-text cursive" style=" color: rgb(55, 144, 223); font-style: italic;">
(ФИО, указанные при регистрации, с возможностью корректировки перед отправкой)
                    </div>
                </div>
​
            </div>
​
            <div class="header__items_item">
                <div class="header__items_item_wrap">
                    <div class="header__items_item--label">
Номер полиса: 
                    </div>
​
                    <div class="header__items_item--text red-text cursive" style=" color: red;  font-style: italic;">
'. $number_polic .'
</div>
                </div>
            </div>
​
            <div class="header__items_item">
                <div class="header__items_item_wrap">
                    <div class="header__items_item--label">
Адрес электронной почты:
                    </div>
​
                    <div class="header__items_item--text blue-text cursive" style=" color: rgb(55, 144, 223); font-style: italic;">
'. $email .'
</div>
                </div>
            </div>
​
            <div class="header__items_item">
                <div class="header__items_item_wrap">
                    <div class="header__items_item--label">
Телефон:
                    </div>
​
                    <div class="header__items_item--text blue-text cursive" style=" color: rgb(55, 144, 223); font-style: italic;">
'. $mobail_number .'
</div>
                </div>
            </div>
        </div>
    </div>
​
    <p>
        <span class="red-text cursive" style=" color: red; font-style: italic;">«'.$data_user_oplata_POST.'</span> г. в медицинской организации <span
            class="blue-text cursive" style=" color: rgb(55, 144, 223); font-style: italic;">'.$arHospital.'</span> мною
        была совершена
        оплата медицинских услуг, что подтверждается прилагаемыми к настоящему письму документами.
    </p>
    <p>
Медицинские услуги были оказаны мне в связи с заболеванием, лечение которого, согласно Программе государственных
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
При обращении в <span class="blue-text cursive" style=" color: rgb(55, 144, 223); font-style: italic;">'.$arHospital.'</span> мне не сообщили о возможности
        получения
        медицинской помощи бесплатно в
        сроки, установленные Территориальной программой государственных гарантий оказания гражданам бесплатной
        медицинской помощи, что является нарушением условий предоставления платных медицинских услуг. Мне было сообщено,
        что необходимая мне медицинская помощь может быть оказана только на платной основе, и в случае моего отказа от
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
В связи с изложенным, в рамках работы по защите моих прав на получение медицинской помощи по программе
        обязательного медицинского страхования, прошу организовать взаимодействие с медицинской организацией по возврату
        оплаченных мною средств.
    </p>
    <p>
Копию договора, документы, подтверждающие факт оплаты прилагаю.
    </p>
    <p>
твет прошу направить в составе электронного письма на указанный адрес электронной почты не позднее срока,
        установленного законом для рассмотрения обращений граждан.
    </p>
    <p>
При необходимости получения дополнительных сведений прошу связываться со мной по указанному телефону либо по
        электронной почте.
    </p>
    <p>
Приложение: документы, подтверждающие факт оплаты медицинских услуг – в электронном виде.
    </p>
    <span class="blue-text cursive" style=" color: rgb(55, 144, 223); font-style: italic;"> Дата формирования заявления'.$data_user_oformlenie_POST.'</span>

</page>

';

$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4',
    'orientation' => 'L',
    'margin_top' => 0,
    'margin_bottom' => 0,
    'margin_left' => 0,
    'margin_right' => 0,
]);
//создаем PDF файл, задаем формат, отступы и.т.д.

//
$name_file = "";
$name_file .= date('Y-m-d-h:i:s');
$name_file .= "_". $email. "_";
$name_file .= "file";
$mpdf->WriteHTML($html);
$mpdf->Output('upload/pdf/PDF_'.$name_file.'.pdf','F');
echo '/upload/pdf/PDF_'.$name_file.'.pdf';
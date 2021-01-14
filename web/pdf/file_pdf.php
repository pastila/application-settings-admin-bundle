<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use \Bitrix\Main\Loader as Loader;
use \Bitrix\Iblock as Iblock;
use Bitrix\Catalog;
use Bitrix\Main\Application;

Loader::includeModule('iblock');

require_once dirname(__FILE__).'/../vendor/autoload.php';
require_once($_SERVER["DOCUMENT_ROOT"]."/symfony-integration/rabbitmq.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/symfony-integration/config_obrashcheniya.php");

global $USER;
if ($_POST['id'] != "") {
    if ($_POST['hospitl'] != "") {
        if ($_POST["number_polic"] != "") {
            if ($_POST["data_user_oplata_POST"] != "") {

                preg_match('/^\s+(.*)/', $_POST["hospitl"], $preg_hospital);
//Проверка на дублировние
                $arSel = array(
                    "ID",
                    "IBLOCK_ID",
                    "NAME",
                    "PROPERTY_FULL_NAME",
                    "PROPERTY_HOSPITAL",
                    "PROPERTY_ADDRESS"
                );
                $arFil = array("IBLOCK_ID" => 11, "ID" => $_POST['id']);
                $res = CIBlockElement::GetList(array(), $arFil, false, false, $arSel);
                $ob = $res->GetNextElement();
                $arFields = $ob->GetFields();
                $hospital_adress = $arFields['PROPERTY_ADDRESS_VALUE'];
                $res_hospital = CIBlockElement::GetList(
                    array(),
                    array('IBLOCK_ID' => 9, '%NAME' => $preg_hospital[1]),
                    false,
                    false,
                    array("ID", "IBLOCK_ID", "NAME", "PROPERTY_FULL_NAME", "PROPERTY_MEDICAL_CODE", "IBLOCK_SECTION_ID")
                );
                while ($ob_hospital = $res_hospital->GetNextElement()) {

                    $arFields = $ob_hospital->GetFields();
                    $MEDICAL_CODE = $arFields['PROPERTY_MEDICAL_CODE_VALUE'];
                    $FULL_NAME_HOSPITAL = $arFields['PROPERTY_FULL_NAME_VALUE'];
                }
//Проверка на дублировние

                $data_user_oformlenie_POST = date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time());


                $data_user_oplata_POST = $_POST["data_user_oplata_POST"];

                $number_polic_POST = $_POST["number_polic"];


                $ID_user = $USER->GetID();
                $rsUser = CUser::GetByID($ID_user);
                $person = $rsUser->Fetch();


                $email = $person["EMAIL"];
                $mobail_number = $person["PERSONAL_PHONE"];
                $full_name_user = $_POST["usrname"];
                $strahovay_compani = $person["UF_INSURANCE_COMPANY"];
                $PERSONAL_BIRTHDAT = $person["PERSONAL_BIRTHDAY"];


                $arSelect = Array("ID", "IBLOCK_ID", "NAME", "IBLOCK_SECTION_ID", "PROPERTY_*");
                $arFilter = Array("IBLOCK_ID" => 16, "ID" => $strahovay_compani);
                $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                $ob = $res->GetNextElement();
                $arProps = $ob->GetProperties();
                $arFields = $ob->GetFields();

                $name_company = $arFields["NAME"];// название компании
                $boss_compani = $arProps["NAME_BOSS"]["VALUE"];// руководитель компании
                $mail_compani = $arProps["EMAIL_FIRST"]["VALUE"];// имйл компании
                $mail_compani2 = $arProps["EMAIL_SECOND"]["VALUE"];// имйл компании
                $mail_compani3 = $arProps["EMAIL_THIRD"]["VALUE"];// имйл компании


                $res = CIBlockSection::GetByID($arFields['IBLOCK_SECTION_ID']);
                $name_rerion = "";
                if ($ar_res = $res->GetNext()) {

                    $name_rerion = "(" . $ar_res["NAME"] . ")";


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
                <div><img style="width: 75px;" src="logo_oms.png" alt="OMS"/></div>
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
                            ' . $name_company . $name_rerion . '<br>
                            ' . $boss_compani . '<br>
                            ' . $mail_compani . ' <br>
                            ' . $mail_compani2 . '<br>
                            ' . $mail_compani3 . '
                        </div>
                          <br>
                    </div>
                </div>
            </div>
            <div class="header__items_item">
                <div class="header__items_item_wrap">
                    <div class="header__items_item--label">
                        <span style="font-weight: bold;">От: </span> 
                        <span class="header__items_item--text blue-text cursive" style="font-style: italic; font-weight: normal;">
                        ' . $full_name_user . ',' . $PERSONAL_BIRTHDAT . '</span>
                    </div>
                      <br>
                </div>
            </div>
             <div class="header__items_item">
                <div class="header__items_item_wrap">
                    <div class="header__items_item--label">
                        <span style="font-weight: bold;">Номер полиса:</span> 
                         <span class="header__items_item--text red-text cursive" style="font-style: italic; font-weight: normal;">
                        ' . $number_polic_POST . '
                    </span>
                    </div>
                      <br>
                </div>
            </div>
               <div class="header__items_item">
                <div class="header__items_item_wrap">
                    <div class="header__items_item--label" >
                        <span style="font-weight: bold;">Адрес электронной почты:</span>
                    </div>
                    <div class="header__items_item--text blue-text cursive" style=" font-style: italic; font-weight: normal;">
                        ' . $email . '
                    </div>
                      <br>
                </div>
            </div>
            <div class="header__items_item">
                <div class="header__items_item_wrap">
                    <div class="header__items_item--label" >
                        <span style="font-weight: bold;">Телефон:</span>  <span class="header__items_item--text
                         blue-text cursive" style=" font-style: italic; font-weight: normal;">' . $mobail_number . '</span>
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
        <p style="text-align: center; margin-bottom: 5px; font-size: 15px; font-weight: bold;">ЖАЛОБА</p>
        
         <p style="text-align: center;  font-size: 15px; ">на взимание денежных средств за медицинскую помощь,</p>
         
         <p style="text-align: center;  font-size: 15px; ">предусмотренную программой ОМС</p>
         
         
        <span class="red-text cursive" style="font-style: italic;">' . $data_user_oplata_POST . '</span> г. в медицинской организации <span
            class="blue-text cursive" style="font-style: italic;">' . $FULL_NAME_HOSPITAL . '</span> (код в реестре медицинских
             организаций, осуществляющих деятельность в сфере ОМС ' . $MEDICAL_CODE . ', ' . $hospital_adress . '),
              мною была совершена оплата медицинских услуг, что подтверждается прилагаемыми к настоящему письму
              документами.
    </p>
    <p>
Медицинские услуги были оказаны мне в связи с заболеванием, лечение которого, согласно Программе государственных
        гарантий бесплатного оказания гражданам медицинской помощи, финансируется из средств обязательного медицинского
        страхования.
    </p>
    <p>
В медицинских организациях, участвующих в реализации программы обязательного медицинского страхования, Правилами
        предоставления платных медицинских услуг (утв. постановлением Правительства РФ от 04.12.2012 № 1006) не
        допускается взимание платы за медицинскую помощь с пациентов, застрахованных по ОМС, при их самостоятельном
        обращении за медицинской помощью в неотложной форме, а также при самостоятельном обращении в медицинскую
        организацию, выбранную для получения первичной медико-санитарной помощи.
    </p>
    <p>
При обращении в <span class="blue-text cursive" style="font-style: italic;">' . $preg_hospital[1] . '</span> мне не сообщили о возможности
        получения
        медицинской помощи бесплатно в
        сроки, установленные Территориальной программой государственных гарантий оказания гражданам бесплатной
        медицинской помощи, что является нарушением условий предоставления платных медицинских услуг. 
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
    <span class="blue-text cursive" style="font-style: italic; margin-top: 5px;"> Дата формирования заявления ' . $data_user_oformlenie_POST . '</span>
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

                if (!file_exists(obrashcheniya_report_path)) {
                  mkdir(obrashcheniya_report_path, 0777, true);
                }
                $name_dir = obrashcheniya_report_path;
                $data = date('Y-m-d-h:i:s');
                $name_file = 'PDF_';
                $name_file .= $data;
                $name_file .= "_" . "file.pdf";
                $full_name_file = $name_dir . $name_file;

                $mpdf->SetTitle($name_file);
                $mpdf->WriteHTML($html);
                $mpdf->Output($full_name_file, 'F');

                /**
                 * Подготовка и отправка данных о принадлежности файла пользователю
                 */
                $rsUser = $USER->GetByLogin($USER->GetLogin());
                if ($arUser = $rsUser->Fetch())
                {
                  rabbitmqSend(queue_obrashcheniya_files, json_encode([
                    'user_id' => $arUser['ID'],
                    'user_login' => $arUser['LOGIN'],
                    'file_type' => obrashcheniya_file_type_report,
                    'file_name' => $full_name_file,
                    'obrashcheniya_id' => $_POST['id'],
                  ]));
                }

                $url_pdf_for_user = sprintf(obrashcheniya_report_url_download, $_POST['id']);
                $arFile = CFile::MakeFileArray($full_name_file);
                $arProperty = Array(
                    "PDF" => $arFile,
                );

                echo $url_pdf_for_user;
            } else {
                echo "data_user_oplata_POST пустое";
            }
        } else {
            echo "number_polic пустое";
        }
    } else {
        echo "hospitl пустое";
    }
} else {
    echo "id пустое";
}

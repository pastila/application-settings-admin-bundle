<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

require_once dirname(__FILE__).'/../vendor/autoload.php';
require_once($_SERVER["DOCUMENT_ROOT"]."/symfony-integration/rabbitmq.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/symfony-integration/config_obrashcheniya.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/symfony-integration/obrashcheniya_helper.php");

if ($USER->IsAuthorized())
{
  $rsUser = $USER->GetByLogin($USER->GetLogin());
  $arUser = $rsUser->Fetch();
}

if (CModule::IncludeModule("iblock")) {
    $arSelect = array(
        "IBLOCK_ID",
        "ID",
        "PREVIEW_PICTURE",
        "PROPERTY_FULL_NAME",
        "PROPERTY_HOSPITAL",
        "PROPERTY_ADDRESS",
        "PROPERTY_CLASS",
        "PROPERTY_GROUP",
        "PROPERTY_SUBGROUP",
        "PROPERTY_DIAGNOZ",
        "PROPERTY_APPEAL",
        "PROPERTY_YEARS",
        "PROPERTY_POLICY",
        "PROPERTY_VISIT_DATE",
        "PROPERTY_IMG_2",
        "PROPERTY_IMG_3",
        "PROPERTY_IMG_4",
        "PROPERTY_IMG_5",
        "PROPERTY_PDF",
        "PROPERTY_IMG_1"
    );
    $arFilter = array("IBLOCK_ID" => 11, "ID" => $_POST['ID'], "!PROPERTY_SEND_REVIEW" => 1);
    $res = CIBlockElement::GetList(
        array(),
        $arFilter,
        false,
        false,
        $arSelect
    );
    if ($ob = $res->GetNextElement()) {
      $arFields = $ob->GetFields();
      $arProps = $ob->GetProperties();
    }

    $array = [];
    $rsUser = $USER->GetByLogin($USER->GetLogin());
    if ($arUser = $rsUser->Fetch())
    {
      $array = getAppealFromSymfony($_POST['ID'], $arUser['LOGIN']);
    }
    $exist = (findExistFile($array, 1) || findExistFile($array, 2) || findExistFile($array, 3) || findExistFile($array, 4) || findExistFile($array, 5));

    //Проверка на дублировние
    $hospital_name = str_replace('&amp;', '', str_replace('quot;', '"', $arFields['PROPERTY_HOSPITAL_VALUE']));
    $hospital_adress = $arFields['PROPERTY_ADDRESS_VALUE'];
    $res_hospital = CIBlockElement::GetList(
        array(),
        array('NAME' => $hospital_name, 'IBLOCK_ID' => 9),
        false,
        false,
        array("ID", "IBLOCK_ID", "NAME", "PROPERTY_FULL_NAME", "PROPERTY_MEDICAL_CODE", "IBLOCK_SECTION_ID")
    );
    while ($ob_hospital = $res_hospital->GetNextElement()) {
        $arFields_hospital = $ob_hospital->GetFields();
        $res = CIBlockSection::GetByID($arFields_hospital['IBLOCK_SECTION_ID']);
        if ($ar_res = $res->GetNext()) {
            if ($ar_res['NAME'] == $hospital_adress) {
                $FULL_NAME_HOSPITAL = htmlspecialchars_decode($arFields_hospital['PROPERTY_FULL_NAME_VALUE']);
                $MEDICAL_CODE = $arFields_hospital['PROPERTY_MEDICAL_CODE_VALUE'];
            }
        }

    }
    //Проверка на дублировние

    if (!empty($arFields['PROPERTY_FULL_NAME_VALUE'])) {
        if (!empty($arFields['PROPERTY_POLICY_VALUE'])) {
            if (!empty($arFields['PROPERTY_VISIT_DATE_VALUE'])) {
                if (!empty($arFields['PROPERTY_IMG_1_VALUE']) || !empty($arFields['PROPERTY_IMG_2_VALUE']) || !empty($arFields['PROPERTY_IMG_3_VALUE']) ||
                    !empty($arFields['PROPERTY_IMG_4_VALUE']) || !empty($arFields['PROPERTY_IMG_5_VALUE']) || $exist) {
                    $count = count($arProps['YEARS']['VALUE']);
                    $i = 1;
                    $nameYear = '';
                    foreach ($arProps['YEARS']['VALUE'] as $arYear) {
                        $res = CIBlockElement::GetByID($arYear);
                        if ($ar_res = $res->GetNext()) {
                            if ($i === $count) {
                                $nameYear .= $ar_res['NAME'];
                            } else {
                                $nameYear .= $ar_res['NAME'] . ', ';
                            }
                            $i++;
                        }
                    }

                    global $USER;
                    $rsUser = CUser::GetByID($USER->GetID());
                    $arUser = $rsUser->Fetch();

                    $arSel = array(
                        "ID",
                        "IBLOCK_ID",
                        "NAME",
                        "IBLOCK_SECTION_ID",
                        "PROPERTY_EMAIL_FIRST",
                        "PROPERTY_EMAIL_SECOND",
                        "PROPERTY_EMAIL_THIRD",
                        "PROPERTY_NAME_BOSS"
                    );
                    $arFil = array("IBLOCK_ID" => 16, "ID" => $arUser['UF_INSURANCE_COMPANY']);
                    $rs = CIBlockElement::GetList(array(), $arFil, false, false, $arSel);
                    if ($obs = $rs->GetNextElement()) {
                        $arOMS = $obs->GetFields();
                        $email = '';
                        if (!empty($arOMS['PROPERTY_EMAIL_FIRST_VALUE'])) {
                            $email .= $arOMS['PROPERTY_EMAIL_FIRST_VALUE'];
                        }
                        if (!empty($arOMS['PROPERTY_EMAIL_SECOND_VALUE'])) {
                            $email .= ' , ' . $arOMS['PROPERTY_EMAIL_SECOND_VALUE'];
                        }
                        if (!empty($arOMS['PROPERTY_EMAIL_THIRD_VALUE'])) {
                            $email .= ' , ' . $arOMS['PROPERTY_EMAIL_THIRD_VALUE'];
                        }
                    }


                    $pdf = false;
                    preg_match("/^\/.*(.pdf)/", mb_strtolower(CFile::GetPath($arFields['PROPERTY_IMG_1_VALUE'])), $file);
                    if ($file[1] == ".pdf") {
                        $pdf = true;
                    }

                    $message .='Приложение:<br>';
                    if ($pdf === true) {

                        $message .= 'PDF: 
                        <a style="color:#186b9c" href="' . $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER['SERVER_NAME'] .
                            CFile::GetPath($arFields['PROPERTY_IMG_1_VALUE']) . '">скачать</a>
                        <br>';
                    } else {
                        $message .= '
                    <img style="max-height: 200px;max-width: 100%;" alt="" src="http://' . $_SERVER['SERVER_NAME'] .
                            CFile::GetPath($arFields['PROPERTY_IMG_1_VALUE']) . '">
                    <br>';
                    }



                    if (!empty($arFields['PROPERTY_IMG_2_VALUE'])) {
                        $pdf = false;
                        preg_match("/^\/.*(.pdf)/", mb_strtolower(CFile::GetPath($arFields['PROPERTY_IMG_2_VALUE'])), $file);
                        if ($file[1] == ".pdf") {
                            $pdf = true;
                        }


                        if ($pdf === true) {

                            $message .= 'PDF: 
                        <a style="color:#186b9c" href="' . $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER['SERVER_NAME'] .
                                CFile::GetPath($arFields['PROPERTY_IMG_2_VALUE']) . '">скачать</a>
                        <br>';
                        } else {
                            $message .= '
                    <img style="max-height: 200px;max-width: 100%;" alt="" src="http://' . $_SERVER['SERVER_NAME'] .
                                CFile::GetPath($arFields['PROPERTY_IMG_2_VALUE']) . '">
                    <br>';
                        }
                    }
                    if (!empty($arFields['PROPERTY_IMG_3_VALUE'])) {
                        $pdf = false;
                        preg_match("/^\/.*(.pdf)/", mb_strtolower(CFile::GetPath($arFields['PROPERTY_IMG_3_VALUE'])), $file);
                        if ($file[1] == ".pdf") {
                            $pdf = true;
                        }


                        if ($pdf === true) {

                            $message .= 'PDF: 
                        <a style="color:#186b9c" href="' . $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER['SERVER_NAME'] .
                                CFile::GetPath($arFields['PROPERTY_IMG_3_VALUE']) . '">скачать</a>
                        <br>';
                        } else {
                            $message .= '
                    <img style="max-height: 200px;max-width: 100%;" alt="" src="http://' . $_SERVER['SERVER_NAME'] .
                                CFile::GetPath($arFields['PROPERTY_IMG_3_VALUE']) . '">
                    <br>';
                        }
                    }
                    if (!empty($arFields['PROPERTY_IMG_4_VALUE'])) {
                        $pdf = false;
                        preg_match("/^\/.*(.pdf)/", mb_strtolower(CFile::GetPath($arFields['PROPERTY_IMG_4_VALUE'])), $file);
                        if ($file[1] == ".pdf") {
                            $pdf = true;
                        }


                        if ($pdf === true) {

                            $message .= 'PDF: 
                        <a style="color:#186b9c" href="' . $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER['SERVER_NAME'] .
                                CFile::GetPath($arFields['PROPERTY_IMG_4_VALUE']) . '">скачать</a>
                        <br>';
                        } else {
                            $message .= '
                    <img style="max-height: 200px;max-width: 100%;" alt="" src="http://' . $_SERVER['SERVER_NAME'] .
                                CFile::GetPath($arFields['PROPERTY_IMG_4_VALUE']) . '">
                    <br>';
                        }
                    }
                    if (!empty($arFields['PROPERTY_IMG_5_VALUE'])) {
                        $pdf = false;
                        preg_match("/^\/.*(.pdf)/", mb_strtolower(CFile::GetPath($arFields['PROPERTY_IMG_5_VALUE'])), $file);
                        if ($file[1] == ".pdf") {
                            $pdf = true;
                        }


                        if ($pdf === true) {

                            $message .= 'PDF: 
                        <a style="color:#186b9c" href="' . $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER['SERVER_NAME'] .
                                CFile::GetPath($arFields['PROPERTY_IMG_5_VALUE']) . '">скачать</a>
                        <br>';
                        } else {
                            $message .= '
                    <img style="max-height: 200px;max-width: 100%;" alt="" src="http://' . $_SERVER['SERVER_NAME'] .
                                CFile::GetPath($arFields['PROPERTY_IMG_5_VALUE']) . '">
                    <br>';
                        }
                    }

                    $arFile = CFile::GetFileArray($arFields['PROPERTY_PDF_VALUE']);
                    $message .= 'Скачать PDF жалобы: 
                        <a style="min-width: 200px;padding: 10px;font-size: 16px;font-family: Exo-SemiBold;background-color:#186b9c;color:#fff;cursor: pointer;text-align: center;border: solid .1rem #186b9c;border-radius: 20px;display: inline-block;margin-top: 20px;text-decoration: none;text-transform: uppercase;line-height: 16px;" href="' . $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER['SERVER_NAME'] .
                        $arFile['SRC'] . '">скачать</a>
                        <br>';
                    $ID_user = $USER->GetID();
                    $rsUser = CUser::GetByID($ID_user);
                    $person = $rsUser->Fetch();

                    $PERSONAL_BIRTHDAT = $person["PERSONAL_BIRTHDAY"];
                    $user_email = $person["EMAIL"];
                    $BIRTHDAY_person = $person["PERSONAL_BIRTHDAY"];
                    $mobail_number = $person["PERSONAL_PHONE"];
                    $full_name_user = $USER->GetFullName();

                    $name_company = $arOMS["NAME"];// название компании
                    $boss_company = $arOMS["PROPERTY_NAME_BOSS_VALUE"];// руководитель компании

                    $arMAil = array();


                    if (!empty($arOMS['PROPERTY_EMAIL_FIRST_VALUE'])) {
                        $arMAil[] = $arOMS['PROPERTY_EMAIL_FIRST_VALUE'];
                        $mail_company .= $arOMS['PROPERTY_EMAIL_FIRST_VALUE'];
                    }
                    if (!empty($arOMS['PROPERTY_EMAIL_SECOND_VALUE'])) {
                        $arMAil[] = $arOMS['PROPERTY_EMAIL_FIRST_VALUE'];
                        $mail_company .= '<br> ' . $arOMS['PROPERTY_EMAIL_SECOND_VALUE'];
                    }
                    if (!empty($arOMS['PROPERTY_EMAIL_THIRD_VALUE'])) {
                        $arMAil[] = $arOMS['PROPERTY_EMAIL_FIRST_VALUE'];
                        $mail_company .= '<br> ' . $arOMS['PROPERTY_EMAIL_THIRD_VALUE'];
                    }


                    $res = CIBlockSection::GetByID($arOMS['IBLOCK_SECTION_ID']);

                    if ($ar_res = $res->GetNext()) {

                        $name_company .= "(" . $ar_res["NAME"] . ")";


                    }


                    if (!empty($email)) {
                        if (!empty($_POST['CHILD'])) {
                            $arSelect_child = array(
                                "ID",
                                "IBLOCK_ID",
                                "NAME",
                                "DATE_ACTIVE_FROM",
                                "PROPERTY_SURNAME",
                                "PROPERTY_PARTONYMIC",
                                "PROPERTY_POLICY",
                                "PROPERTY_COMPANY",
                                "PROPERTY_BIRTHDAY"
                            );
                            $arFilter_child = array("IBLOCK_ID" => 21, "ID" => $_POST['CHILD']);
                            $res_child = CIBlockElement::GetList(
                                array(),
                                $arFilter_child,
                                false,
                                false,
                                $arSelect_child
                            );
                            $ob_child = $res_child->GetNextElement();
                            $arFields_child = $ob_child->GetFields();

                            $arSelect_comp = array(
                                "ID",
                                "IBLOCK_ID",
                                "IBLOCK_SECTION_ID",
                                "PROPERTY_NAME_BOSS",
                                "NAME",
                                "PROPERTY_EMAIL_FIRST",
                                "PROPERTY_EMAIL_SECOND",
                                "PROPERTY_EMAIL_THIRD"
                            );
                            $arFilter_comp = array(
                                "IBLOCK_ID" => 16,
                                "ID" => $arFields_child["PROPERTY_COMPANY_VALUE"]
                            );
                            $res_comp = CIBlockElement::GetList(
                                array(),
                                $arFilter_comp,
                                false,
                                false,
                                $arSelect_comp
                            );
                            $ob_comp = $res_comp->GetNextElement();
                            $arFields_comp = $ob_comp->GetFields();

                            $SURNAME = $arFields_child["PROPERTY_SURNAME_VALUE"];
                            $NAME = $arFields_child["NAME"];
                            $PARTONYMIC = $arFields_child["PROPERTY_PARTONYMIC_VALUE"];
                            $polic = $arFields_child["PROPERTY_POLICY_VALUE"];
                            $BIRTHDAY = $arFields_child["PROPERTY_BIRTHDAY_VALUE"];

                            $email_child_smo = '';
                            if (!empty($arFields_comp['PROPERTY_EMAIL_FIRST_VALUE'])) {
                                $email_child_smo .= $arFields_comp['PROPERTY_EMAIL_FIRST_VALUE'];
                            }
                            if (!empty($arFields_comp['PROPERTY_EMAIL_SECOND_VALUE'])) {
                                $email_child_smo .= ' , ' . $arFields_comp['PROPERTY_EMAIL_SECOND_VALUE'];
                            }
                            if (!empty($arFields_comp['PROPERTY_EMAIL_THIRD_VALUE'])) {
                                $email_child_smo .= ' , ' . $arFields_comp['PROPERTY_EMAIL_THIRD_VALUE'];
                            }

                            $mail_cmo = '';
                            if (!empty($arFields_comp['PROPERTY_EMAIL_FIRST_VALUE'])) {
                                $mail_cmo .= $arFields_comp['PROPERTY_EMAIL_FIRST_VALUE'];
                            }
                            if (!empty($arFields_comp['PROPERTY_EMAIL_SECOND_VALUE'])) {
                                $mail_cmo .= '<br> ' . $arFields_comp['PROPERTY_EMAIL_SECOND_VALUE'];
                            }
                            if (!empty($arFields_comp['PROPERTY_EMAIL_THIRD_VALUE'])) {
                                $mail_cmo .= '<br> ' . $arFields_comp['PROPERTY_EMAIL_THIRD_VALUE'];
                            }


                            $name_rerion = $arFields_comp['NAME'];
                            $full_name_child = $SURNAME . ' ' . $NAME . ' ' . $PARTONYMIC;

                            $res = CIBlockSection::GetByID($arFields_comp['IBLOCK_SECTION_ID']);

                            if ($ar_res = $res->GetNext()) {

                                $name_rerion .= "(" . $ar_res["NAME"] . ")";


                            }
                          try
                          {
                            rabbitmqSend(queue_obrashcheniya_emails, json_encode([
                              'child' => true,
                              'login' => $arUser['LOGIN'],
                              'id' => $arFields['ID'],
                              'email' => $email_child_smo,
                              /*
                              'login' => $arUser['LOGIN'],
                              'SEND_MESSAGE_CHILD',
                              's1',
                              array(
                                'PROPERTY_IMG_1_VALUE' => CFile::GetPath($arFields['PROPERTY_IMG_1_VALUE']),
                                'PROPERTY_IMG_2_VALUE' => CFile::GetPath($arFields['PROPERTY_IMG_2_VALUE']),
                                'PROPERTY_IMG_3_VALUE' => CFile::GetPath($arFields['PROPERTY_IMG_3_VALUE']),
                                'PROPERTY_IMG_4_VALUE' => CFile::GetPath($arFields['PROPERTY_IMG_4_VALUE']),
                                'PROPERTY_IMG_5_VALUE' => CFile::GetPath($arFields['PROPERTY_IMG_5_VALUE']),
                                'PDF' => parsingPdfPath($arFile['SRC']),
                                'ID' => $arFields['ID'],
                                'MESSAGE' => $message,
                                'NAME_COMPANY' => $name_rerion,
                                'BOSS_COMPANY' => $arFields_comp['PROPERTY_NAME_BOSS_VALUE'],
                                'EMAIL' => $email_child_smo,
                                'USER_MAIL' => $user_email,
                                'FULLNAME' => $arFields['PROPERTY_FULL_NAME_VALUE'],
                                'FULLNAME_CHILD' => $full_name_child,
                                "BIRTHDAY" => $BIRTHDAY,
                                'POLICY' => $arFields['PROPERTY_POLICY_VALUE'],
                                'POLICY_CHILD' => $polic,
                                'PHONE' => $mobail_number,
                                'HOSPITAL' => htmlspecialchars_decode($arFields['PROPERTY_HOSPITAL_VALUE']),
                                'DATE_SEND' => date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time()),
                                'DATE_PAY' => $arFields['PROPERTY_VISIT_DATE_VALUE'],
                                'PERSONAL_BIRTHDAT' => $PERSONAL_BIRTHDAT,
                                'MAIL_CMO' => $mail_cmo,
                                'FULL_NAME_HOSPITAL' => $FULL_NAME_HOSPITAL,
                                'MEDICAL_CODE' => $MEDICAL_CODE,
                                'HOSPITAL_ADRESS' => $hospital_adress,
                                'SRC_LOGO' => $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/pdf/logo_oms.png",

                               * */
                            ]));
                            CIBlockElement::SetPropertyValuesEx(
                              $_POST['ID'],
                              11,
                              array(
                                "SEND_REVIEW" => 8, //ID статуса ожидает отправки
                              )
                            );
                            $result['success'] = 'Ваше обращение ожидает отправки в страховую компанию. За состоянием отправки сообщения Вы 
                            можете наблюдать в личном кабинете «Отправленные» ';
                          } catch (ErrorException $channelException)
                          {
                            $result['error'] = 'Не удалось отправить обращение. Пожалуйста, обратитесь в службу поддержки.';
                          }
                        } else {
                          try
                          {
                            rabbitmqSend(queue_obrashcheniya_emails, json_encode([
                              'child' => false,
                              'login' => $arUser['LOGIN'],
                              'id' => $arFields['ID'],
                              'email' => $email,
                              /*
                              'login' => $arUser['LOGIN'],
                              'SEND_MESSAGE',
                              's1',
                              array(
                                'PROPERTY_IMG_1_VALUE' => CFile::GetPath($arFields['PROPERTY_IMG_1_VALUE']),
                                'PROPERTY_IMG_2_VALUE' => CFile::GetPath($arFields['PROPERTY_IMG_2_VALUE']),
                                'PROPERTY_IMG_3_VALUE' => CFile::GetPath($arFields['PROPERTY_IMG_3_VALUE']),
                                'PROPERTY_IMG_4_VALUE' => CFile::GetPath($arFields['PROPERTY_IMG_4_VALUE']),
                                'PROPERTY_IMG_5_VALUE' => CFile::GetPath($arFields['PROPERTY_IMG_5_VALUE']),
                                'PDF' => parsingPdfPath($arFile['SRC']),
                                'ID' => $arFields['ID'],
                                'NAME_COMPANY' => $name_company,
                                'BOSS_COMPANY' => $boss_company,
                                'MAIL_COMPANY' => $mail_company,
                                'EMAIL' => $email,
                                'BIRTHDAY' => $BIRTHDAY_person,
                                'USER_MAIL' => $user_email,
                                'FULLNAME' => $arFields['PROPERTY_FULL_NAME_VALUE'],
                                'POLICY' => $arFields['PROPERTY_POLICY_VALUE'],
                                'PHONE' => $mobail_number,
                                'HOSPITAL' => htmlspecialchars_decode($arFields['PROPERTY_HOSPITAL_VALUE']),
                                'DATE_SEND' => date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time()),
                                'DATE_PAY' => $arFields['PROPERTY_VISIT_DATE_VALUE'],
                                'FULL_NAME_HOSPITAL' => $FULL_NAME_HOSPITAL,
                                'MEDICAL_CODE' => $MEDICAL_CODE,
                                'HOSPITAL_ADRESS' => $hospital_adress,
                                'SRC_LOGO' => $_SERVER["REQUEST_SCHEME"] . "://" . $_SERVER["SERVER_NAME"] . "/pdf/logo_oms.png",

                               * */
                            ]));
                            CIBlockElement::SetPropertyValuesEx(
                              $_POST['ID'],
                              11,
                              array(
                                "SEND_REVIEW" => 8, //ID статуса ожидает отправки
                              )
                            );
                            $result['success'] = 'Ваше обращение ожидает отправки в страховую компанию. За состоянием отправки сообщения Вы
                            можете наблюдать в личном кабинете «Отправленные» ';
                          } catch (ErrorException $channelException)
                          {
                            $result['error'] = 'Не удалось отправить обращение. Пожалуйста, обратитесь в службу поддержки.';
                          }
                        }
                    } else {
                        $result['error'] = 'Нет почтового адреса';
                    }

                } else {
                    $result['error'] = 'Прикрепите к обращению сканкопии или фотографии документов об оплате медицинской помощи (чеки, договор) в формате jpg, png, bmp, jpeg.';
                }
            } else {
                $result['error'] = 'Введите дату посещения больницы';
            }
        } else {
            $result['error'] = 'Введите полис';
        }
    } else {
        $result['error'] = 'Введите ФИО';
    }
}

exit(json_encode($result));


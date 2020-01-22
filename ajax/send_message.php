<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
if (CModule::IncludeModule("iblock")) {
    $arSelect = array("IBLOCK_ID", "ID", "PREVIEW_PICTURE", "PROPERTY_FULL_NAME", "PROPERTY_HOSPITAL",
        "PROPERTY_ADDRESS", "PROPERTY_CLASS", "PROPERTY_GROUP", "PROPERTY_SUBGROUP", "PROPERTY_DIAGNOZ",
        "PROPERTY_APPEAL", "PROPERTY_YEARS", "PROPERTY_POLICY", "PROPERTY_VISIT_DATE", "PROPERTY_IMG_2",
        "PROPERTY_IMG_3", "PROPERTY_IMG_4", "PROPERTY_IMG_5", "PROPERTY_PDF");
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
                if (!empty($arFields['PREVIEW_PICTURE'])) {
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

                    $arSel = array("ID", "IBLOCK_ID", "NAME", "PROPERTY_EMAIL_FIRST", "PROPERTY_NAME_BOSS");
                    $arFil = array("IBLOCK_ID" => 16, "ID" => $arUser['UF_INSURANCE_COMPANY']);
                    $rs = CIBlockElement::GetList(array(), $arFil, false, false, $arSel);
                    if ($obs = $rs->GetNextElement()) {
                        $arOMS = $obs->GetFields();
                        $email = '';
                        if (!empty($arOMS['PROPERTY_EMAIL_FIRST_VALUE'])) {
                            $email .= $arOMS['PROPERTY_EMAIL_FIRST_VALUE'];
                        }
                        if (!empty($arOMS['PROPERTY_EMAIL_SECOND_VALUE'])) {
                            $email .= ', ' . $arOMS['PROPERTY_EMAIL_SECOND_VALUE'];
                        }
                        if (!empty($arOMS['PROPERTY_EMAIL_THIRD_VALUE'])) {
                            $email .= ', ' . $arOMS['PROPERTY_EMAIL_THIRD_VALUE'];
                        }
                    }

                    $message .= 'Загруженные документы пользователем:<br>';
                    $message .= 'Картинка №1: 
                    <img src="http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($arFields['PREVIEW_PICTURE']) . '">
                    <br>';
                    if (!empty($arFields['PROPERTY_IMG_2_VALUE'])) {
                        $message .= 'Картинка №2: 
                        <img src="http://' . $_SERVER['SERVER_NAME'] .
                            CFile::GetPath($arFields['PROPERTY_IMG_2_VALUE']) . '">
                        <br>';
                    }
                    if (!empty($arFields['PROPERTY_IMG_3_VALUE'])) {
                        $message .= 'Картинка №3: 
                        <img src="http://' . $_SERVER['SERVER_NAME'] .
                            CFile::GetPath($arFields['PROPERTY_IMG_3_VALUE']) . '">
                        <br>';
                    }
                    if (!empty($arFields['PROPERTY_IMG_4_VALUE'])) {
                        $message .= 'Картинка №4: 
                        <img src="http://' . $_SERVER['SERVER_NAME'] .
                            CFile::GetPath($arFields['PROPERTY_IMG_4_VALUE']) . '">
                        <br>';
                    }
                    if (!empty($arFields['PROPERTY_IMG_5_VALUE'])) {
                        $message .= 'Картинка №5: 
                        <img src="http://' . $_SERVER['SERVER_NAME'] .
                            CFile::GetPath($arFields['PROPERTY_IMG_5_VALUE']) . '">
                        <br>';
                    }
                    $arFile = CFile::GetFileArray($arFields['PROPERTY_PDF_VALUE']);
                    $message .= 'PDF: 
                        <a href="http://' . $_SERVER['SERVER_NAME'] .
                        $arFile['SRC'] . '">скачать</a>
                        <br>';
                    $ID_user = $USER->GetID();
                    $rsUser = CUser::GetByID($ID_user);
                    $person = $rsUser->Fetch();

                    $PERSONAL_BIRTHDAT = $person["PERSONAL_BIRTHDAY"];
                    $user_email = $person["EMAIL"];
                    $mobail_number = $person["PERSONAL_PHONE"];
                    $full_name_user = $USER->GetFullName();

                    $name_company = $arOMS["NAME"];// название компании
                    $boss_company = $arOMS["PROPERTY_NAME_BOSS_VALUE"];// руководитель компании
                    $mail_company = $arOMS["PROPERTY_EMAIL_FIRST_VALUE"];// имйл компании

                    if (!empty($email)) {
                        if (!empty($_POST['CHILD'])) {
                            $arSelect_child = array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_SURNAME",
                                "PROPERTY_PARTONYMIC","PROPERTY_POLICY","PROPERTY_COMPANY","PROPERTY_BIRTHDAY");
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

                            $arSelect_comp = array("ID", "IBLOCK_ID", "PROPERTY_NAME_BOSS", "NAME", "PROPERTY_EMAIL_FIRST");
                            $arFilter_comp = array("IBLOCK_ID" => 16, "ID" => $arFields_child["PROPERTY_COMPANY_VALUE"]);
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
                            $mail_cmo = $arFields_comp["PROPERTY_EMAIL_FIRST_VALUE"];

                            $full_name_child = $SURNAME . ' ' . $NAME . ' ' . $PARTONYMIC;


                            CEvent::Send(
                                'SEND_MESSAGE_CHILD',
                                's1',
                                array(
                                    'MESSAGE' => $message,
                                    'NAME_COMPANY' => $arFields_comp['NAME'],
                                    'BOSS_COMPANY' => $arFields_comp['PROPERTY_NAME_BOSS_VALUE'],
                                    'EMAIL' => $email,
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
                                    'HOSPITAL_ADRESS' => $hospital_adress

                                )
                            );
                            CIBlockElement::SetPropertyValuesEx(
                                $_POST['ID'],
                                11,
                                array("SEND_REVIEW" => 3,
                                    "SEND_MESSAGE" => date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time()))
                            );
                            $result['success'] = 'Обращение успешно отправлено в страховую компанию.
                         Ваше обращение находится в личном кабинете «Отправленные»';
                        } else {
                            CEvent::Send(
                                'SEND_MESSAGE',
                                's1',
                                array(
                                    'MESSAGE' => $message,
                                    'NAME_COMPANY' => $name_company,
                                    'BOSS_COMPANY' => $boss_company,
                                    'MAIL_COMPANY' => $mail_company,
                                    'EMAIL' => $email,
                                    'USER_MAIL' => $user_email,
                                    'FULLNAME' => $arFields['PROPERTY_FULL_NAME_VALUE'],
                                    'POLICY' => $arFields['PROPERTY_POLICY_VALUE'],
                                    'PHONE' => $mobail_number,
                                    'HOSPITAL' => htmlspecialchars_decode($arFields['PROPERTY_HOSPITAL_VALUE']),
                                    'DATE_SEND' => date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time()),
                                    'DATE_PAY' => $arFields['PROPERTY_VISIT_DATE_VALUE'],
                                    'FULL_NAME_HOSPITAL' => $FULL_NAME_HOSPITAL,
                                    'MEDICAL_CODE' => $MEDICAL_CODE,
                                    'HOSPITAL_ADRESS' => $hospital_adress
                                )
                            );
                            CIBlockElement::SetPropertyValuesEx(
                                $_POST['ID'],
                                11,
                                array("SEND_REVIEW" => 3,
                                    "SEND_MESSAGE" => date($DB->DateFormatToPHP(CSite::GetDateFormat("SHORT")), time()))
                            );
                            $result['success'] = 'Обращение успешно отправлено в страховую компанию.
                         Ваше обращение находится в личном кабинете «Отправленные»';

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



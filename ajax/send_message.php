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

                    $arSel = array("ID", "IBLOCK_ID", "NAME", "PROPERTY_EMAIL_FIRST", "PROPERTY_EMAIL_SECOND",
                        "PROPERTY_EMAIL_THIRD");
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

                    $user_email = $person["EMAIL"];
                    $mobail_number = $person["PERSONAL_PHONE"];
                    $full_name_user = $USER->GetFullName();

                    $name_company = $arOMS["NAME"];// название компании
                    $boss_company = $arOMS["NAME_BOSS"]["VALUE"];// руководитель компании
                    $mail_company = $arOMS["EMAIL_FIRST"]["VALUE"];// имйл компании

                    if (!empty($email)) {
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
                    $result['error'] = 'Прикрепите к обращению как минимум 1 документ в формате: jpg, png, bmp, jpeg';
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



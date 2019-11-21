<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if (CModule::IncludeModule("iblock")) {
    $arSelect = array("IBLOCK_ID", "ID", "PREVIEW_PICTURE", "PROPERTY_FULL_NAME", "PROPERTY_HOSPITAL",
        "PROPERTY_ADDRESS", "PROPERTY_CLASS", "PROPERTY_GROUP", "PROPERTY_SUBGROUP", "PROPERTY_DIAGNOZ",
        "PROPERTY_APPEAL", "PROPERTY_YEARS", "PROPERTY_POLICY", "PROPERTY_VISIT_DATE");
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
                    $db_list = CIBlockSection::GetList(
                        array(),
                        array("IBLOCK_ID" => 16 ,'NAME' => $arFields['PROPERTY_ADDRESS_VALUE']),
                        false,
                        array('ID'),
                        false
                    )->GetNext();

                    $arSel = array("ID", "IBLOCK_ID", "PROPERTY_EMAIL_FIRST", "PROPERTY_EMAIL_SECOND",
                        "PROPERTY_EMAIL_THIRD");
                    $arFil = array("IBLOCK_ID" => 16, "~NAME" => $arUser['UF_INSURANCE_COMPANY'],
                        "SECTION_ID" => $db_list['ID']);
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

                    $message .= 'ФИО ' . $arFields['PROPERTY_FULL_NAME_VALUE'] . '<br>';
                    $message .= 'Период получения помощи: ' . $nameYear . '<br>';
                    $message .= 'Обращение: ' . $arFields['PROPERTY_APPEAL_VALUE'] . '<br>';
                    $message .= 'Регион: ' . $arFields['PROPERTY_ADDRESS_VALUE'] . '<br>';
                    $message .= 'Больница: ' . htmlspecialchars_decode($arFields['PROPERTY_HOSPITAL_VALUE']) . '<br>';
                    $message .= 'Класс: ' . $arFields['PROPERTY_CLASS_VALUE'] . '<br>';
                    $message .= 'Группа: ' . $arFields['PROPERTY_GROUP_VALUE'] . '<br>';
                    $message .= 'Подгруппа: ' . $arFields['PROPERTY_SUBGROUP_VALUE'] . '<br>';
                    $message .= 'Диагноз: ' . $arFields['PROPERTY_DIAGNOZ_VALUE'] . '<br>';
                    $message .= 'Полис: ' . $arFields['PROPERTY_POLICY_VALUE'] . '<br>';
                    $message .= 'Дата посещения больницы: ' . $arFields['PROPERTY_VISIT_DATE_VALUE'] . '<br>';
                    $message .= 'Загруженные документы пользователем:<br>';
                    $message .= 'Картинка №1: <img src="http://' . $_SERVER['SERVER_NAME'] . CFile::GetPath($arFields['PREVIEW_PICTURE']) . '"><br>';

                    if (!empty($email)) {
                        CEvent::Send('SEND_MESSAGE', 's1', array('MESSAGE' => $message, 'EMAIL' => $email));
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



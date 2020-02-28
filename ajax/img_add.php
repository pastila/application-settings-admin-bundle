<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("iblock");

if (isset($_FILES['import_file']['tmp_name'])) {
    // проверяем, можно ли загружать изображение
    $check = can_upload($_FILES['import_file']);
    if ($check === true) {

        $arSelect = array("ID", "IBLOCK_ID", "PROPERTY_IMG_1", "PROPERTY_IMG_2", "PROPERTY_IMG_3", "PROPERTY_IMG_4",
            "PROPERTY_IMG_5");
        $arFilter = array("IBLOCK_ID" => 11, "ID" => $_POST['id_elem']);
        $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        if ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
        }


        // загружаем изображение на сервер

        $el = new CIBlockElement;

        if (empty($arFields['PROPERTY_IMG_1_VALUE'])) {
            $key = 'IMG_1';
            $i = 1;
        } elseif (empty($arFields['PROPERTY_IMG_2_VALUE'])) {
            $key = 'IMG_2';
            $i = 2;
        } elseif (empty($arFields['PROPERTY_IMG_3_VALUE'])) {
            $key = 'IMG_3';
            $i = 3;
        } elseif (empty($arFields['PROPERTY_IMG_4_VALUE'])) {
            $key = 'IMG_4';
            $i = 4;
        } elseif (empty($arFields['PROPERTY_IMG_5_VALUE'])) {
            $key = 'IMG_5';
            $i = 5;
        }

        if ($key) {
            $result[$key] = true;
            CIBlockElement::SetPropertyValuesEx(
                $_POST['id_elem'],
                11,
                array($key => $_FILES['import_file'])
            );

            $db_props = CIBlockElement::GetProperty(11, $_POST['id_elem'], array(), array("CODE" => $key));
            if ($ar_props = $db_props->Fetch()) {
                $arFile = CFile::GetFileArray($ar_props['VALUE']);
            }
        }
        $result["PDF_src"] = 1;
        $result['SRC'] = $arFile["SRC"];
        $result['ID'] = $_POST['id_elem'] . '_img_' . $i;
        $result['RES'] = $res;
        $result['SUCCESS'] = "Файл успешно загружен!";
    } else {
        // выводим сообщение об ошибке
        $result['ID'] = $_POST['id_elem'];
        $result['ERROR'] = $check;
    }
    echo json_encode($result);
}


function can_upload($file){
    // если имя пустое, значит файл не выбран
    if($file['name'] == '')
        return 'Вы не выбрали файл.';

    /* если размер файла 0, значит его не пропустили настройки
    сервера из-за того, что он слишком большой */
    if($file['size'] == 0)
        return 'Файл слишком большой.';

    // разбиваем имя файла по точке и получаем массив
    $getMime = explode('.', $file['name']);
    // нас интересует последний элемент массива - расширение
    $mime = strtolower(end($getMime));
    // объявим массив допустимых расширений
    $types = array('jpg', 'png', 'gif', 'bmp', 'jpeg','pdf');

    // если расширение не входит в список допустимых - return
    if(!in_array($mime, $types))
        return 'Недопустимый тип файла. Доступные форматы: jpg, png, bmp, jpeg, pdf';

    return true;
}
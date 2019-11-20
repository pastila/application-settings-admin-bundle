<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("iblock");

if (isset($_FILES['import_file']['tmp_name'])) {
    // проверяем, можно ли загружать изображение
    $check = can_upload($_FILES['import_file']);
    if ($check === true) {

        $arSelect = array("ID", "IBLOCK_ID", "PREVIEW_PICTURE", "PROPERTY_IMG_2", "PROPERTY_IMG_3", "PROPERTY_IMG_4",
            "PROPERTY_IMG_5");
        $arFilter = array("IBLOCK_ID" => 11, "ID" => $_POST['id_elem']);
        $res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
        if ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
        }


        // загружаем изображение на сервер
        if (empty($arFields['PREVIEW_PICTURE'])) {
            $picture = 'PREVIEW_PICTURE';
        } elseif (empty($arFields['PROPERTY_IMG_2'])) {
            $key = 'IMG_2';
        } elseif (empty($arFields['PROPERTY_IMG_3'])) {
            $key = 'IMG_3';
        } elseif (empty($arFields['PROPERTY_IMG_4'])) {
            $key = 'IMG_4';
        } elseif (empty($arFields['PROPERTY_IMG_5'])) {
            $key = 'IMG_5';
        }
        if ($picture) {
            $el = new CIBlockElement;
            $res = $el->Update($_POST['id_elem'], array("PREVIEW_PICTURE" => $_FILES['import_file']));
        }
        if ($key) {
            CIBlockElement::SetPropertyValuesEx(
                $_POST['ID'],
                11,
                array($key => $_FILES['import_file'])
            );
        }
        if ($res > 0) {
            if ($key) {
                $db_props = CIBlockElement::GetProperty(11, $_POST['id_elem'], array(), array("CODE" => $key));
                if ($ar_props = $db_props->Fetch()) {
                    $result['ELEMMMM'] = $ar_props;
                }
            }

            if ($picture) {
                $rs = CIBlockElement::GetByID($_POST['id_elem']);
                if ($arElem = $rs->GetNext()) {
                    $arFile = CFile::GetFileArray($arElem['PREVIEW_PICTURE']);
                }
            }

            $result['SRC'] = $arFile["SRC"];
//            $result['ELEM_KEY'] = $arElem;
//            $result['ELEM_KEYgen'] = $key;
        }
        $result['ID'] = $_POST['id_elem'];
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
    $types = array('jpg', 'png', 'gif', 'bmp', 'jpeg');

    // если расширение не входит в список допустимых - return
    if(!in_array($mime, $types))
        return 'Недопустимый тип файла.';

    return true;
}
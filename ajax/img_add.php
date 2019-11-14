<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("iblock");

if (isset($_FILES['import_file']['tmp_name'])) {
    // проверяем, можно ли загружать изображение
    $check = can_upload($_FILES['import_file']);

    if($check === true){
        // загружаем изображение на сервер
        $el = new CIBlockElement;
        $res = $el->Update($_POST['id_elem'], array("PREVIEW_PICTURE" => $_FILES['import_file']));
        if ($res > 0) {
            $rs = CIBlockElement::GetByID($_POST['id_elem']);
            if ($arElem = $rs->GetNext()) {
                $arFile = CFile::GetFileArray($arElem["PREVIEW_PICTURE"]);
            }
            $result['SRC'] = $arFile["SRC"];
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
<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

require_once dirname(__FILE__).'/../vendor/autoload.php';
require_once($_SERVER["DOCUMENT_ROOT"]."/symfony-integration/rabbitmq.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/symfony-integration/config_obrashcheniya.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/symfony-integration/obrashcheniya_helper.php");

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

        $array = [];
        $rsUser = $USER->GetByLogin($USER->GetLogin());
        if ($arUser = $rsUser->Fetch())
        {
          $array = getAppealFromSymfony($_POST['id_elem'], $arUser['LOGIN']);
        }

        // загружаем изображение на сервер

        $el = new CIBlockElement;

        if (empty($arFields['PROPERTY_IMG_1_VALUE']) && !findExistFile($array, 1)) {
            $key = 'IMG_1';
            $i = 1;
        } elseif (empty($arFields['PROPERTY_IMG_2_VALUE']) && !findExistFile($array, 2)) {
            $key = 'IMG_2';
            $i = 2;
        } elseif (empty($arFields['PROPERTY_IMG_3_VALUE']) && !findExistFile($array, 3)) {
            $key = 'IMG_3';
            $i = 3;
        } elseif (empty($arFields['PROPERTY_IMG_4_VALUE']) && !findExistFile($array, 4)) {
            $key = 'IMG_4';
            $i = 4;
        } elseif (empty($arFields['PROPERTY_IMG_5_VALUE']) && !findExistFile($array, 5)) {
            $key = 'IMG_5';
            $i = 5;
        }

        $full_name_file = '';
        if ($key) {
            $result[$key] = true;

            if (!file_exists(obrashcheniya_report_attached_path)) {
              mkdir(obrashcheniya_report_attached_path, 0777, true);
            }
            $array = explode(".", $_FILES['import_file']['name']);
            $name_dir = obrashcheniya_report_attached_path;
            $name_file =  $key;
            $name_file .= "_" . $_POST['id_elem'] . "_";
            $name_file .= 'file.' . end($array) ;
            $full_name_file = $name_dir . $name_file;

            if (move_uploaded_file($_FILES['import_file']['tmp_name'], $full_name_file)) {
              $arFile = CFile::MakeFileArray($full_name_file);
              $arProperty = Array(
                 $key => $arFile,
              );

              /**
               * Подготовка и отправка данных о принадлежности файла пользователю
               */
              $rsUser = $USER->GetByLogin($USER->GetLogin());
              if ($arUser = $rsUser->Fetch())
              {
                try {
                  sendAppealToSymfony(obrashcheniya_appeal_files_api, API_TOKEN, json_encode([
                    'user_id' => $arUser['ID'],
                    'user_login' => $arUser['LOGIN'],
                    'file_type' => obrashcheniya_file_type_attach,
                    'file_name' => $full_name_file,
                    'obrashcheniya_id' => $_POST["id_elem"],
                    'imageNumber' => $i,
                  ]));

                  $url_load = sprintf(obrashcheniya_report_url_download, $_POST["id_elem"]);
                  $url_load = $url_load . '?image_number=' . $i;

                  $result['EXTENSION'] = end($array);
                  $result['SRC'] = $url_load;
                  $result['ID'] = $_POST['id_elem'] . '_img_' . $i;
                  $result['RES'] = $res;
                  $result['SUCCESS'] = "Файл успешно загружен!";
                  $result['FILE_NAME'] = !empty($arFile["FILE_NAME"]) ? $arFile["FILE_NAME"] : '';
                } catch (ErrorException $exception)
                {
                  $result['ID'] = $_POST['id_elem'];
                  $result['ERROR'] = 'Ошибка загрузки файла';
                }
              } else {
                $result['ID'] = $_POST['id_elem'];
                $result['ERROR'] = 'Ошибка загрузки файла: пользователь не авторизован';
              }
            }
        }
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
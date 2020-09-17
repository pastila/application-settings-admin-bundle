<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
global $USER;
$result = array();
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
//echo "<pre>";
//print_r($_FILES);
//echo "</pre>";

if(!$APPLICATION->CaptchaCheckCode($_POST["captcha_word"],$_POST["captcha_code"])){
    $result['captcha'] = '1';
    echo json_encode($result);
    die();
}else {

  if(!empty($_FILES)) {
      foreach ($_FILES as $item) {

          $file_result = can_upload($item);

          if ($file_result != 1) {
              if ($file_result == 'Файл слишком большой') {
                  $result['size'] = '1';
              }
              if ($file_result == 'Недопустимый тип файла.') {
                  $result['format'] = '1';
              }
          }
      }
  }

    if (!empty($result)) {

        echo json_encode($result);
    } else {
    $array_img = array();
    $array_img_url = array();
    foreach ($_FILES as $item) {
        $uploads_dir = '/var/www/upload/logo_users/';
        $name = basename($item["name"]);
        $data = date('Y-m-d-h:i:s');
        $data .= $name;
        $uploads_dir .= $data;
     $clear_uploads_dir =  str_ireplace(" ","",$uploads_dir);
        move_uploaded_file($item["tmp_name"], $clear_uploads_dir);

        $arFile = CFile::MakeFileArray($clear_uploads_dir);

        $array_img[] = $arFile;
        $new_uploads_dir = LIVE_SITE_URL;
        $new_uploads_dir .= str_ireplace("/var/www","",$clear_uploads_dir);

        $array_img_url[]= $new_uploads_dir;
    }
    $html_img = "";
    foreach ($array_img_url as $item){
        $html_img .= '<div><img src="'.$item.'" alt=""></div>';
    }


    $data = date('Y-m-d-h:i:s');
    $code = str_ireplace("@", "", $_POST["email"]);
    $arFields_el = array(
        "ACTIVE" => "Y",
        "IBLOCK_ID" => 22,
        "NAME" => $_POST["email"],
        "CODE" => $code . $data,
        "PROPERTY_VALUES" => array(
            "NAME" => $_POST["name"],
            "EMAIL" => $_POST["email"],
            "TEXT" => $_POST["text"],
            "IMG" => $array_img,
        )
    );

    $oElement = new CIBlockElement();

    $idElement = $oElement->Add($arFields_el);

    $array_send= array(
        "NAME" => $_POST["name"],
        "EMAIL" => $_POST["email"],
        "TEXT" => $_POST["text"],
        "IMG" => $html_img,
    );
    CEvent::Send("FOR_ADMIN_WRITE_TO_US", "s1", $array_send);
        $result['suc'] = '1';
        echo json_encode($result);

    }



}

function can_upload($file)
{
    // если имя пустое, значит файл не выбран


    /* если размер файла 0, значит его не пропустили настройки
    сервера из-за того, что он слишком большой */
    if ($file['size'] == 0)
        return 'Файл слишком большой.';

    // разбиваем имя файла по точке и получаем массив
    $getMime = explode('.', $file['name']);
    // нас интересует последний элемент массива - расширение
    $mime = strtolower(end($getMime));
    // объявим массив допустимых расширений
    $types = array('jpg', 'png', 'bmp', 'jpeg');

    // если расширение не входит в список допустимых - return
    if (!in_array($mime, $types))
        return 'Недопустимый тип файла.';

    return true;
}


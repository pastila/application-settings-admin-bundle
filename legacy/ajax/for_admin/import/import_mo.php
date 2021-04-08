<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");


if ($_FILES['import_file']) {
    $uploads_dir = '/var/www/web/upload/import/';
    $name = basename($_FILES["import_file"]["name"]);

    $uploads_dir .= $name;
    if(move_uploaded_file($_FILES["import_file"]["tmp_name"], $uploads_dir) === true){
        echo $uploads_dir;
    }else{
        echo 0;
    }


}


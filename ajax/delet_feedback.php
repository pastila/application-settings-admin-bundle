<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
if($_POST){
    CModule::IncludeModule("iblock");


    if($_POST["case"]== "cation"){
      $result =   CIBlockElement::Delete($_POST["id"]);
        if($result == true){
            echo 1;
        }
    }
    if($_POST["case"]== "comment"){
        $result =     CIBlockElement::Delete($_POST["id"]);
        if($result == true){
            echo 1;
        }
    }
    if($_POST["case"]== "review"){
        $result =     CIBlockElement::Delete($_POST["id"]);
        if($result == true){
            echo 1;
        }

    }

}
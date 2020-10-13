<?php
$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_SF_REMOTE_ADDR'];

use Bitrix\Main\Config as Config;
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

$all_bcc = Config\Option::get("main", "all_bcc", "");

  header('Content-Type: applicaion/json');
  echo json_encode([
    'MAIN_EMAIL' => $all_bcc
  ]);
  exit;
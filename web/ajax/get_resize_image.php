<?php
$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_SF_REMOTE_ADDR'];

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

$logo_id = !empty($_GET['logo_id']) ? $_GET['logo_id'] : null;

$file = CFile::ResizeImageGet($logo_id, array('width' => 120, 'height' => 80),
  BX_RESIZE_IMAGE_PROPORTIONAL, true);

  header('Content-Type: applicaion/json');
  echo json_encode([
    'src' => !empty($file["src"]) ? $file["src"] : null,
  ]);
  exit;



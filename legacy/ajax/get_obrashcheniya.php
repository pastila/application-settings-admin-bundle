<?php
$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_SF_REMOTE_ADDR'];

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

global $USER;
$ID_USER = $USER->GetID();

$countAppeals = null;
$count_reviews = null;

  //Количество обращений
  if (CModule::IncludeModule("iblock")) {
    $arFilterSect = array('IBLOCK_ID' => 11, "UF_USER_ID" => $ID_USER);
    $rsSect = CIBlockSection::GetList(array(), $arFilterSect);
    if ($arSect = $rsSect->GetNext()) {
      $arSelect = array("ID", "NAME", "DATE_ACTIVE_FROM");
      $arFilter = array("IBLOCK_ID" => 11, "!PROPERTY_SEND_REVIEW" => 3,
        "IBLOCK_SECTION_ID" => $arSect["ID"], "ACTIVE" => "Y");
      $res = CIBlockElement::GetList(
        array(),
        $arFilter,
        false,
        false,
        $arSelect
      );
      while ($ob = $res->GetNextElement()) {
        $arFields[] = $ob->GetFields();
      }
      $countAppeals = count($arFields);
    }
  }

$arFilter = array("IBLOCK_ID" => 11, "UF_USER_ID" => $ID_USER);
$section = CIBlockSection::GetList(
  array(),
  $arFilter,
  false,
  false,
  false
);  // получили секцию по айди юзера
if ($Section = $section->GetNext()) {
  $arFilter = array("IBLOCK_ID" => 11,
    "SECTION_ID" => $Section["ID"],"PROPERTY_SEND_REVIEW_VALUE" => 1);
  $Element = CIBlockElement::GetList(
    array(),
    $arFilter,
    false,
    false,
    false
  ); //получили обращения юзера
  $obElement = $Element->SelectedRowsCount();

}

  header('Content-Type: applicaion/json');
  echo json_encode([
    'nbAppeals' => $countAppeals,
    'nbSent' => $obElement
  ]);
  exit;



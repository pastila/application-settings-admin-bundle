<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */


define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if (!isset($_SERVER['HTTP_X_SF_SECRET'])
    || $_SERVER['HTTP_X_SF_SECRET'] !== '2851f0ae-9dc7-4a22-9283-b86abfa44900')
{
  http_response_code(404);
  die;
}

/** @var $USER CAllUser */
if ($USER->IsAuthorized())
{
  $rsUser = $USER->GetByLogin($USER->GetLogin());
  if ($arUser = $rsUser->Fetch())
  {
    http_response_code(200);
    header('Content-Type:application/json');
    echo json_encode([
      'id' => $arUser['ID'],
      'fullName' => $USER->GetFullName(),
      'email' =>  $arUser['EMAIL'],
      'region' => $arUser['PERSONAL_STATE']
    ]);

    exit;
  }
}

http_response_code(401);
exit;





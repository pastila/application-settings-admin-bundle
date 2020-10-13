<?php
/**
 * @author Denis N. Ragozin <dragozin@accurateweb.ru>
 */

if (!isset($_SERVER['HTTP_X_SF_SECRET'])
  || $_SERVER['HTTP_X_SF_SECRET'] !== '2851f0ae-9dc7-4a22-9283-b86abfa44900')
{
  http_response_code(404);
  die;
}

$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_X_SF_REMOTE_ADDR'];

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("NEED_AUTH", true);


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

/** @var $USER CAllUser */
if ($USER->IsAuthorized())
{
  $rsUser = $USER->GetByLogin($USER->GetLogin());
  if ($arUser = $rsUser->Fetch())
  {
    $id_user = $USER->GetID();
    $email_user = $USER->GetEmail();
    $rsUser = CUser::GetByID($id_user);
    $person = $rsUser->Fetch();

    http_response_code(200);
    header('Content-Type:application/json');
    echo json_encode([
      'id' => $arUser['ID'],
      'fullName' => $USER->GetFullName(),
      'firstName' => $USER->GetFirstName(),
      'lastName' => $USER->GetLastName(),
      'middleName' => $USER->GetSecondName(),
      'email' => $arUser['EMAIL'],
      'isAdmin' => $USER->IsAdmin(),
      'representative' => $person["UF_REPRESENTATIVE"],
      'branch' => $person['UF_INSURANCE_COMPANY'],
      'region' => $arUser['UF_REGION'],
    ]);

    exit;
  }
}

http_response_code(401);
header('Content-Type:application/json');
echo json_encode([
  'is_script' => true,
]);
exit;





<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");


global $USER;
$filter = array("PERSONAL_PHONE" => $_POST['phone']);
$rsUsers = CUser::GetList(($by="name"), ($order="ASC"), $filter);
while ($arUser = $rsUsers->Fetch()) {
    $arSpecUser = $arUser;
}
if ($arSpecUser) {
    echo 'error';
} else {
    session_start();
    $currentTime = new \DateTime();
    if (!empty($_SESSION['SMS_DATETIME']))
    {
      /**
       * Если в сессии есть запись, о уже отправленной смс
       * Проверям, что она была отправлена не раньше 30 сек
       */
      $sessionTime = new \DateTime($_SESSION['SMS_DATETIME']);
      $sessionTime->add(new DateInterval('PT30S'));
      if ($sessionTime > $currentTime)
      {
        http_response_code(429);
        exit;
      }
    }
    $apiSms = new \Kdteam\ApiSms();
    $permitted_chars = '0123456789';

    function generate_string($input, $strength) {
        $input_length = strlen($input);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }

        return $random_string;
    }
    $sms_code = generate_string($permitted_chars, 5);

    $_SESSION['SMS_CODE'] = $sms_code;
    $_SESSION['SMS_DATETIME'] = $currentTime->format('Y-m-d H:i:s');

    $result = $apiSms->execCommad('registerSender', array('name' => "bezbahil", 'country' => "ru"));
    $result = $apiSms->execCommad("sendSMS", array(
        'sender' => "bezbahil",
        'text' => "Ваш код - $sms_code",
        'phone' => $_POST['phone'],
        'datetime' => "",
        'sms_lifetime' => "0"
    ));
}

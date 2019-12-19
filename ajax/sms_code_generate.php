<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

session_start();
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
echo $sms_code;
$_SESSION['SMS_CODE'] = $sms_code;
//$result = $apiSms->execCommad('registerSender', array('name' => "GAZDA", 'country' => "ua"));
//$result = $apiSms->execCommad("sendSMS", array(
//    'sender' => "OMS",
//    'text' => "Ваш код - $sms_code",
//    'phone' => '79312100362',
//    'datetime' => "",
//    'sms_lifetime' => "0"
//));
//
//print_r($result)
?>
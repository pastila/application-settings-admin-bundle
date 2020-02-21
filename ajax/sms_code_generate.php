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

    $result = $apiSms->execCommad('registerSender', array('name' => "bezbahil", 'country' => "ru"));
    $result = $apiSms->execCommad("sendSMS", array(
        'sender' => "bezbahil",
        'text' => "Ваш код - $sms_code",
        'phone' => $_POST['phone'],
        'datetime' => "",
        'sms_lifetime' => "0"
    ));

}

?>
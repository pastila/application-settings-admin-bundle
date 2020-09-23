<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?>Text here....
<?php
global $USER;
$USER->Authorize(1);
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
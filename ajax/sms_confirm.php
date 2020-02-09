<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
session_start();
if ($_POST['ID'] === $_SESSION['SMS_CODE']) {
    echo 'success';
} else {
    echo 'error';
}
?>
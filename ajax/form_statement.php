<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");?>

<div class="modal">
    <div class="close-modal">
        <img src="/local/templates/kdteam/images/svg/close_modal.svg" alt="">
    </div>

    <div class="title">
        Обращение находится в вашем личном кабинете
    </div>

    <p>Напоминаем вам, что перед отправкой к обращению необходимо присоединить сканкопию или фотографию имеющихся
        документов об оплате медицинской помощи. Также перед отправкой мы рекомендуем поверить правильность данных в
        обращении. Обращение будет направлено в адрес страховой компании, которую вы указали при регистрации на сайте.
    </p>
</div>

<?php
CModule::IncludeModule("iblock");

global $USER;
$arFilter = array("IBLOCK_ID" => 11, "UF_USER_ID" => $USER->GetID());
$arSelect = array("ID");
$rsSections = CIBlockSection::GetList(array(), $arFilter, false, $arSelect, false);

$arName = $USER->GetFullName();
$arAppeal = $_SESSION["APPEAL"];


$res = CIBlockElement::GetByID($_SESSION["HOSPITAL"]);
if ($ar_res = $res->GetNext()) {
    $arHospital = $ar_res;
}

$res = CIBlockSection::GetByID($_SESSION["REGION"]);
if ($ar_res = $res->GetNext()) {
    $arRegion = $ar_res;
}

$res = CIBlockSection::GetByID($_SESSION["CLASS"]);
if ($ar_res = $res->GetNext()) {
    $arClass = $ar_res;
}

$res = CIBlockSection::GetByID($_SESSION["GROUP"]);
if ($ar_res = $res->GetNext()) {
    $arGroup = $ar_res;
}

$res = CIBlockSection::GetByID($_SESSION["SUBGROUP"]);
if ($ar_res = $res->GetNext()) {
    $arSubGroup = $ar_res;
}

$res = CIBlockElement::GetByID($_SESSION["DIAGNOZ"]);
if ($ar_res = $res->GetNext()) {
    $arDiagnoz = $ar_res;
}

if ($arSection = $rsSections->GetNext()) {
    $el = new CIBlockElement;
    $arFields_el = array(
        "ACTIVE" => "Y",
        "IBLOCK_ID" => 11,
        "IBLOCK_SECTION_ID" => $arSection["ID"],
        "NAME" => "Обращение",
        "PROPERTY_VALUES" => array(
            "FULL_NAME" => $arName,
            "HOSPITAL" => $arHospital["NAME"],
            "ADDRESS" => $arRegion["NAME"],
            "CLASS" => $arClass["NAME"],
            "GROUP" => $arGroup["NAME"],
            "SUBGROUP" => $arSubGroup["NAME"],
            "DIAGNOZ" => $arDiagnoz["NAME"],
            "APPEAL" => $arAppeal,
        )
    );
    foreach ($_SESSION['YEARS'] as $year) {
        $arFields_el["PROPERTY_VALUES"]["YEARS"][] = $year;
    }
    $oElement = new CIBlockElement();
    $idElement = $oElement->Add($arFields_el);
} else {
    $arFields = array("IBLOCK_ID" => 11, "NAME" => $USER->GetLogin(), "UF_USER_ID" => $USER->GetID());
    $bs = new CIBlockSection;
    $ID = $bs->Add($arFields);
    if ($ID > 0) {
        $el = new CIBlockElement;
        $arFields_el = array(
            "ACTIVE" => "Y",
            "IBLOCK_ID" => 11,
            "IBLOCK_SECTION_ID" => $ID,
            "NAME" => "Обращение",
            "PROPERTY_VALUES" => array(
                "FULL_NAME" => $arName,
                "HOSPITAL" => $arHospital["NAME"],
                "ADDRESS" => $arRegion["NAME"],
                "CLASS" => $arClass["NAME"],
                "GROUP" => $arGroup["NAME"],
                "SUBGROUP" => $arSubGroup["NAME"],
                "DIAGNOZ" => $arDiagnoz["NAME"],
                "APPEAL" => $arAppeal,
            )
        );
        foreach ($_SESSION['YEARS'] as $year) {
            $arFields_el["PROPERTY_VALUES"]["YEARS"][] = $year;
        }
        $oElement = new CIBlockElement();
        $idElement = $oElement->Add($arFields_el);
        $oElement = new CIBlockElement();
        $idElement = $oElement->Add($arFields_el);
    }
}


?>

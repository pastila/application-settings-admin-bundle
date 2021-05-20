<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");?>


<?php
CModule::IncludeModule("iblock");

global $USER;
$arFilter = array("IBLOCK_ID" => 11, "UF_USER_ID" => $USER->GetID());
$arSelect = array("ID");
$rsSections = CIBlockSection::GetList(array(), $arFilter, false, $arSelect, false);


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
$phone ="";
if($USER->IsAuthorized()) {


    $rsUser = CUser::GetByID($USER->GetID());
    $arUser = $rsUser->Fetch();
    $phone = $arUser['PERSONAL_PHONE'];

    $arName = $arUser['LAST_NAME'] . ' ' . $arUser['NAME'] . ' ' . $arUser['SECOND_NAME'];

    if ($arSection = $rsSections->GetNext()) {

        $el = new CIBlockElement;
        $arFields_el = array(
            "ACTIVE" => "Y",
            "IBLOCK_ID" => 11,
            "IBLOCK_SECTION_ID" => $arSection["ID"],
            "NAME" => "Обращение",
            "PROPERTY_VALUES" => array(
                "FULL_NAME" => $arName,
                "MOBAIL_PHONE" => $phone,
                "HOSPITAL" => $arHospital["NAME"],
                "ADDRESS" => $arRegion["NAME"],
                "CLASS" => $arClass["NAME"],
                "GROUP" => $arGroup["NAME"],
                "SUBGROUP" => $arSubGroup["NAME"],
                "DIAGNOZ" => $arDiagnoz["NAME"],
                "APPEAL" => $arAppeal,
                "POLICY" => $arUser['UF_INSURANCE_POLICY']
            )
        );
        foreach ($_SESSION['YEARS'] as $year) {
            $arFields_el["PROPERTY_VALUES"]["YEARS"][] = $year;
        }
        $oElement = new CIBlockElement();
        $idElement = $oElement->Add($arFields_el);
    } else {

        $arFields = array("IBLOCK_ID" => 11, "NAME" => $USER->GetEmail(), "UF_USER_ID" => $USER->GetID());
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
                    "MOBAIL_PHONE" => $phone,
                    "HOSPITAL" => $arHospital["NAME"],
                    "ADDRESS" => $arRegion["NAME"],
                    "CLASS" => $arClass["NAME"],
                    "GROUP" => $arGroup["NAME"],
                    "SUBGROUP" => $arSubGroup["NAME"],
                    "DIAGNOZ" => $arDiagnoz["NAME"],
                    "APPEAL" => $arAppeal,
                    "POLICY" => $arUser['UF_INSURANCE_POLICY']
                )
            );
            foreach ($_SESSION['YEARS'] as $year) {
                $arFields_el["PROPERTY_VALUES"]["YEARS"][] = $year;
            }
            $oElement = new CIBlockElement();
            $idElement = $oElement->Add($arFields_el);

        }
    }
}


?>
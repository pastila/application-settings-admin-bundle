<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
$result = array();
if($_POST) {

    $name = $_POST["name"];
    $familia = $_POST["famaly-name"];
    $otchestvo = $_POST["last-name"];
    $email = $_POST["email"];
    $tel = $_POST["phone"];
    $nomer_polica = $_POST["number_polic"];
    $id_company = $_POST["company"];
    $parol = $_POST["password"];

    global $USER;
    global $DB;

    $filter = Array(
        "EMAIL" => $email,
    );
    $order = array('sort' => 'asc');
    $tmp = 'sort';
    $rsUser = CUser::GetList($order, $tmp, $filter);


    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM");
    $arFilter = Array("IBLOCK_ID"=>16,"ID"=> $id_company);
    $res_company = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    if ($res_company->SelectedRowsCount() == 0){
        $msg = array("company"=> "Нет компании");
        $result =  array_merge($result, $msg);

    } elseif ($rsUser->SelectedRowsCount() > 0) {
        $msg = array("user"=> "Уже существует");
        $result =  array_merge($result, $msg);
    } else {
        $arFields = Array(
            "NAME" => $name,
            "LAST_NAME" => $familia,
            "SECOND_NAME" => $otchestvo,
            "EMAIL" => $email,
            "LOGIN" => $email,
            "ACTIVE" => "Y",
            "GROUP_ID" => array(3, 4, 5),
            "PASSWORD" => $parol,
            "CONFIRM_PASSWORD" => $parol,
            "PERSONAL_MOBILE" => $tel,
            "UF_INSURANCE_POLICY" => $nomer_polica,
            "UF_INSURANCE_COMPANY" => $id_company
        );
        $ID = $USER->Add($arFields);
        $ID_user = array("user"=> $ID);
        $result =  array_merge($result, $ID_user);
        $USER->Authorize($ID);
        if($_POST["review"]){

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
            $arName = $USER->GetFullName();
            $arAppeal = $_SESSION["APPEAL"];
            $arFields = array("IBLOCK_ID" => 11, "NAME" => $name, "UF_USER_ID" => $ID);
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

            }
           $msg= array("review"=>"register_with_review");
            $result =  array_merge($result, $msg);
        }


//        if ($ID) {
//
//            $Send = Array(
//                "NAME" => $name,
//                "LAST_NAME" => $familia,
//                "SECOND_NAME" => $otchestvo,
//                "LOGIN" => $email,
//                "UF_INSURANCE_POLICY" => $nomer_polica,
//                "UF_INSURANCE_COMPANY" => $id_company
//            );
//            CEvent::SendImmediate("NEW_USER", "s1", $Send);
//        }

    }


echo json_encode($result,true);
}
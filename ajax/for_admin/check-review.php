<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
global $USER;
if ($_POST) {

    if ($USER->IsAdmin()) {
        $VERIFIED = "";
        $REJECTED = "";
        $ID_VERIFIED = "";
        $ID_REJECTED = "";

        $property_enums = CIBlockPropertyEnum::GetList(Array("DEF" => "DESC", "SORT" => "ASC"),
            Array("IBLOCK_ID" => 13, "CODE" => array("VERIFIED", "REJECTED")));
        while ($enum_fields = $property_enums->GetNext()) {


            if ($enum_fields["PROPERTY_CODE"] == "VERIFIED") {
                $ID_VERIFIED = $enum_fields["ID"];
            }
            if ($enum_fields["PROPERTY_CODE"] == "REJECTED") {
                $ID_REJECTED = $enum_fields["ID"];
            }


        }


        if ($_POST["accepted"] != "") {
            $VERIFIED = $ID_VERIFIED;
        }
        if ($_POST["reject"] != "") {
            $REJECTED = $ID_REJECTED;
        }


        $arField = array(
            'VERIFIED' => $VERIFIED,
            'REJECTED' => $REJECTED,

        );

        \CIBlockElement::SetPropertyValuesEx($_POST["id"], 13, $arField);


    }
}

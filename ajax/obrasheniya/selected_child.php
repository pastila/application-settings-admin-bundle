<?php require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

$arSelect = array("ID", "NAME", "PROPERTY_SURNAME", "PROPERTY_PARTONYMIC", "PROPERTY_POLICY" , "PROPERTY_COMPANY");
$arFilter = array("IBLOCK_ID" => 21, 'ID' => $_POST['ID']);



$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
if ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $resSec = CIBlockElement::GetByID($arFields["PROPERTY_COMPANY_VALUE"]);
    if ($ar_res = $resSec->GetNext()) {
        $hospital = $ar_res['~NAME'];
    }
    ?>
    <div class="obrashcheniya__content_left_center_item">
        <div class="obrashcheniya__content_left_center_item_text">
            ФИО
        </div>

        <p class="obrashcheniya__content_left_center_item_text-full fio-js">
            <?php echo $arFields['PROPERTY_SURNAME_VALUE']?> <?php echo $arFields['NAME']?> <?php echo $arFields['PROPERTY_PARTONYMIC_VALUE']?>
        </p>
    </div>
    <div class="obrashcheniya__content_left_center_item">
        <div class="obrashcheniya__content_left_center_item_text">
            Cтраховой полис
        </div>

        <p class="obrashcheniya__content_left_center__item_text-full">
            <?php echo $arFields['PROPERTY_POLICY_VALUE']?>
        </p>
    </div>

    <!-- Item -->
    <div class="obrashcheniya__content_left_center_item">
        <div class="obrashcheniya__content_left_center_item_text">
            Cтраховая компания:
        </div>

        <p class="obrashcheniya__content_left_center_item_text-full"><?php echo $hospital?></p>
    </div>
<?php } ?>

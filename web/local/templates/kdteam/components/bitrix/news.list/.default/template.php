<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

/**
 * Сортировка ассоциативного массива $arResult по полю NAME, которое является годами
 */
$arResultSort = array();
foreach ($arResult["ITEMS"] as $key => $row) {
  $arResultSort[$key] = $row['NAME'];
}
array_multisort($arResultSort, SORT_ASC, $arResult["ITEMS"]);
?>
<div id="years" class="wrap-chrckbox">
    <?php foreach ($arResult["ITEMS"] as $arItem) { ?>
        <label class="check-label years" data-year="<?php echo $arItem["NAME"] ?>">
            <?php echo $arItem["NAME"]?>
            <input name="years" type="radio" data-value="<?php echo $arItem["NAME"]; ?>"  value="<?=$arItem['ID']?>" />
            <span class="check-img"></span>
        </label>
    <?php }?>
    <input type="hidden"  id="selected_year" value="">
</div>


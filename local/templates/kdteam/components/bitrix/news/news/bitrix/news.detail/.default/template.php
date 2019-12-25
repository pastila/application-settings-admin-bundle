<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
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
?>
<div class="news-detail">
    <?php if ($arParams["DISPLAY_PICTURE"] != "N" && is_array($arResult["DETAIL_PICTURE"])) {?>
        <img
            class="detail_picture"
            border="0"
            src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
            width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>"
            height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>"
            alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
            title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>"
            />
    <?php }?>
    <?php if ($arParams["DISPLAY_DATE"] != "N" && $arResult["DISPLAY_ACTIVE_FROM"]) {?>
        <span class="news-date-time"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></span>
    <?php }?>
    <?php if ($arParams["DISPLAY_NAME"] != "N" && $arResult["NAME"]) {?>
        <h3><?php echo $arResult["NAME"]?></h3>
    <?php }?>
    <?php if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arResult["FIELDS"]["PREVIEW_TEXT"]) {?>
        <p><?php echo $arResult["FIELDS"]["PREVIEW_TEXT"];
        unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?></p>
    <?php }?>
    <?php if ($arResult["NAV_RESULT"]) {?>
        <?php if ($arParams["DISPLAY_TOP_PAGER"]) {?>
            <?php echo $arResult["NAV_STRING"]?>
        <?php }?>
        <?php echo $arResult["NAV_TEXT"]?>
        <?php if ($arParams["DISPLAY_BOTTOM_PAGER"]) {?>
            <?php echo $arResult["NAV_STRING"]?>
        <?php }?>
    <?php } elseif (strlen($arResult["DETAIL_TEXT"]) > 0) {?>
        <?php echo $arResult["DETAIL_TEXT"]?>
    <?php } else {?>
        <?php echo $arResult["PREVIEW_TEXT"]?>
    <?php }?>
</div>
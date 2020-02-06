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

<?php foreach ($arResult["ITEMS"] as $arItem) {
    if ($arItem['PREVIEW_PICTURE']['ID']) {
        $file = CFile::ResizeImageGet(
            $arItem['PREVIEW_PICTURE']['ID'],
            array('width' => 511, 'height' => 375),
            BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
            false
        )['src'];

    } else {
        $file = SITE_TEMPLATE_PATH . '/images/png/no-image.png';
    }

    ?>
<div class="col-1">
    <div class="news_item white_block">
        <div class="news_item_image">
            <img src="<?=$file?>" alt="">
        </div>

        <div class="news_item_content">
            <a class="news_item_content_title" href="<?=$arItem['DETAIL_PAGE_URL']?>">
                <h3><?php echo $arItem['NAME']?></h3>
            </a>

            <?php if ($arItem['PROPERTIES']['ADD_NAME']['VALUE']) { ?>
            <div class="news_item_content_text">
                <?php echo $arItem['PROPERTIES']['ADD_NAME']['VALUE']?>
            </div>
            <?php }?>
            <div class="news_item_content_date"><?php echo $arItem['ACTIVE_FROM']?></div>
            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="news_item_content_readmore">Продолжить чтение</a>
        </div>
    </div>
</div>
<?php }?>
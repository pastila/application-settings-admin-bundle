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
            array('width' => 250, 'height' => 250),
            BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
            false
        )['src'];

    } else {
        $file = SITE_TEMPLATE_PATH . '/images/png/no-image.png';
    }

    ?>
    <div class="news_item white_block">
        <a class="block_image" href="<?=$arItem['DETAIL_PAGE_URL']?>">
            <img src="<?=$file?>" alt="">
        </a>
        <?php if ($arItem['PROPERTIES']['ADD_NAME']['VALUE']) { ?>
            <div class="top_label">
                <a href="<?=$arItem['DETAIL_PAGE_URL']?>">
                    <?php echo $arItem['PROPERTIES']['ADD_NAME']['VALUE']?></a>
            </div>
        <?php }?>
        <div class="name_news">
            <a href="<?=$arItem['DETAIL_PAGE_URL']?>"><?php echo $arItem['NAME']?></a>
        </div>
        <div class="date_news"><?php echo $arItem['ACTIVE_FROM']?></div>
        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="readmore_news">Продолжить чтение</a>
    </div>
<?php }?>


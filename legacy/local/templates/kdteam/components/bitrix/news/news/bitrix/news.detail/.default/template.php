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

<meta property="og:type" content="article"/>
<?php if ($arParams["DISPLAY_NAME"] != "N" && $arResult["NAME"]) {?>
    <meta property="og:title" content="<?php echo $arResult["NAME"]?>"/>
<?php }?>

<?php $newsUrl =  (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>
<meta property="og:url" content="<?= $newsUrl ?>"/>
<?php if ($arParams["DISPLAY_PICTURE"] != "N" && is_array($arResult["DETAIL_PICTURE"])) {?>
    <meta property="og:image" content="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"/>
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
<?php }?>
<?php if ($arParams["DISPLAY_NAME"] != "N" && $arResult["NAME"]) {?>
    <meta property="article:section" content="<?php echo $arResult["NAME"]?>">
<?php }?>
<?php if ($arParams["DISPLAY_DATE"] != "N" && $arResult["DISPLAY_ACTIVE_FROM"]) {?>
    <meta property="article:published_time" content="<?=$arResult["DISPLAY_ACTIVE_FROM"]?>">
<?php }?>

<div class="white_block">
    <div class="news-detail flex-detail-news" itemscope itemtype="http://schema.org/NewsArticle">
        <link itemprop="mainEntityOfPage" href="<?= ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>" />
        <meta itemprop="author" content="Без Бахил">
        <meta itemprop="datePublished" content="<?= date('Y-m-d', strtotime($arResult['TIMESTAMP_X'])) ?>">
        <meta itemprop="dateModified" content="<?= date('Y-m-d', strtotime($arResult['TIMESTAMP_X'])) ?>">
        <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
            <link itemprop="url" href="<?= ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" ?>">
            <meta itemprop="name" content="Безбахил">
            <div itemprop="logo" itemscope itemtype="https://www.schema.org/ImageObject">
                <link itemprop="url" href="<?= "/local/templates/kdteam/images/favicon.png" ?>">
                <link itemprop="contentUrl" href="<?= ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]"."/local/templates/kdteam/images/favicon.png" ?>" >
            </div>
        </div>
        <meta itemprop="url" content="<?= ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" ?>" />

        <div class="news-detail_image" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
            <link itemprop="url" href="<?= "/local/templates/kdteam/images/favicon.png" ?>">
            <?php if ($arParams["DISPLAY_PICTURE"] == "Y" && is_array($arResult["DETAIL_PICTURE"])) {?>
            <meta itemprop="height" content="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>">
            <meta itemprop="width" content="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>">
            <meta itemprop="url" content="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>">
            <img src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
                width="<?=$arResult["DETAIL_PICTURE"]["WIDTH"]?>" height="<?=$arResult["DETAIL_PICTURE"]["HEIGHT"]?>"
                alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>" title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>" />
            <?php }?>
        </div>

        <div class="content-news">
            <?php if ($arParams["DISPLAY_DATE"] != "N" && $arResult["DISPLAY_ACTIVE_FROM"]) {?>
            <span class="news-date-time date_news" itemprop="datePublished" ><?=$arResult["DISPLAY_ACTIVE_FROM"]?></span>
            <?php }?>

            <?php if ($arParams["DISPLAY_NAME"] != "N" && $arResult["NAME"]) {?>
            <h2 class="content-news_title" itemprop="headline"><?php echo $arResult["NAME"]?></h2>
            <?php }?>

            <?php if ($arParams["DISPLAY_PREVIEW_TEXT"] != "N" && $arResult["FIELDS"]["PREVIEW_TEXT"]) {?>
            <p itemprop="description"><?php echo $arResult["FIELDS"]["PREVIEW_TEXT"];
                    unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?></p>
            <?php }?>

            <p itemprop="articleBody">
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
            </p>
        </div>

    </div>
</div>
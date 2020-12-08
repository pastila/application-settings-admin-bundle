<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/symfony-integration/config_obrashcheniya.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/symfony-integration/obrashcheniya_helper.php");

use Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/otpravlennyye/otpravlennyye.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/otpravlennyye/otpravlennyye.min.js");
CModule::IncludeModule("iblock");
?>


<!-- Breadcrumbs -->
<ul class="breadcrumbs">
    <? if ($detect->isTablet() || $detect->isMobile()) { ?>
    <li><a href="/" class="active-breadcrumbs">Отправленные обращения</a></li>
    <? } else { ?>
    <li><a href="/">Главная</a></li>
    <li class="active-breadcrumbs">Отправленные обращения</li>
    <? } ?>

</ul>

<!-- Pages Title -->
<h1 class="page-title">Отправленные обращения</h1>


<?
global $USER;
$ID_USER = $USER->GetID();
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM");
$arFilter = Array("IBLOCK_ID" => 11, "UF_USER_ID" => $ID_USER);
$section = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect, false);  // получили секцию по айди юзера
if ($Section = $section->GetNext()) {
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "CREATED_DATE", "PROPERTY_IMG_1", "PROPERTY_SEND_MESSAGE",
        "PROPERTY_PDF", "PROPERTY_IMG_2", "PROPERTY_IMG_3", "PROPERTY_IMG_4", "PROPERTY_IMG_5");
    $arFilter = Array("IBLOCK_ID" => 11, "SECTION_ID" => $Section["ID"], "PROPERTY_SEND_REVIEW_VALUE"=> 1);
    $Element = CIBlockElement::GetList(Array("created" => "desc"), $arFilter, false, false, $arSelect); //получили обращения юзера
    while ($obElement = $Element->GetNextElement()) {
        $arFields = $obElement->GetFields();
        $PDF = CFile::GetPath($arFields['PROPERTY_PDF_VALUE']);




        if (!empty($arFields['PROPERTY_IMG_1_VALUE'])) {
            $pdf_1 = false;
            preg_match("/^\/.*(.pdf)/", mb_strtolower(CFile::GetPath($arFields['PROPERTY_IMG_1_VALUE'])), $file);
            if ($file[1] == ".pdf") {
                $pdf_1 = true;
            }
        }
        if (!empty($arFields['PROPERTY_IMG_2_VALUE'])) {
            $pdf_2 = false;
            preg_match("/^\/.*(.pdf)/", mb_strtolower(CFile::GetPath($arFields['PROPERTY_IMG_2_VALUE'])), $file);
            if ($file[1] == ".pdf") {
                $pdf_2 = true;
            }
        }
        if (!empty($arFields['PROPERTY_IMG_3_VALUE'])) {
            $pdf_3 = false;
            preg_match("/^\/.*(.pdf)/", mb_strtolower(CFile::GetPath($arFields['PROPERTY_IMG_3_VALUE'])), $file);
            if ($file[1] == ".pdf") {
                $pdf_3 = true;
            }
        }
        if (!empty($arFields['PROPERTY_IMG_4_VALUE'])) {
            $pdf_4 = false;
            preg_match("/^\/.*(.pdf)/", mb_strtolower(CFile::GetPath($arFields['PROPERTY_IMG_4_VALUE'])), $file);
            if ($file[1] == ".pdf") {
                $pdf_4 = true;
            }
        }
        if (!empty($arFields['PROPERTY_IMG_5_VALUE'])) {
            $pdf_5 = false;
            preg_match("/^\/.*(.pdf)/", mb_strtolower(CFile::GetPath($arFields['PROPERTY_IMG_5_VALUE'])), $file);
            if ($file[1] == ".pdf") {
                $pdf_5 = true;
            }
        }
      $array = [];
      $rsUser = $USER->GetByLogin($USER->GetLogin());
      if ($arUser = $rsUser->Fetch())
      {
        try {
          $dbh = new \PDO('mysql:host=' . PERCONA_HOST. ';dbname='.PERCONA_DATABASE, PERCONA_USER, PERCONA_PASSWORD);
          $sql = 'SELECT 
                    sof.bitrix_id,
                    sof.user_id,
                    sof.image_number,
                    su.login
                    FROM s_obrashcheniya_files as sof
                    JOIN s_users su ON su.id = sof.user_id AND su.login = :login
                    WHERE sof.bitrix_id = :bitrix_id';
          $sth = $dbh->prepare($sql);
          $sth->execute(array('bitrix_id' => $arFields["ID"], 'login' => $arUser['LOGIN']));
          $array = $sth->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
          print "Error!: " . $e->getMessage() . "<br/>";
          die();
        }
      }

      $img_first = findExistFile($array, 1) ? sprintf(obrashcheniya_report_url_download, $arFields["ID"]) . '?image_number=1' :
        CFile::GetPath($arFields['PROPERTY_IMG_1_VALUE']);

      $img_second = findExistFile($array, 2) ? sprintf(obrashcheniya_report_url_download, $arFields["ID"]) . '?image_number=2' :
        CFile::GetPath($arFields['PROPERTY_IMG_2_VALUE']);

      $img_third = findExistFile($array, 3) ? sprintf(obrashcheniya_report_url_download, $arFields["ID"]) . '?image_number=3' :
        CFile::GetPath($arFields['PROPERTY_IMG_3_VALUE']);

      $img_fourth = findExistFile($array, 4) ? sprintf(obrashcheniya_report_url_download, $arFields["ID"]) . '?image_number=4' :
        CFile::GetPath($arFields['PROPERTY_IMG_4_VALUE']);

      $img_fifth = findExistFile($array, 5) ? sprintf(obrashcheniya_report_url_download, $arFields["ID"]) . '?image_number=5' :
        CFile::GetPath($arFields['PROPERTY_IMG_5_VALUE']);
        ?>
<!-- Обращения item -->
<div class="otpravlennyye">
    <div class="white_block">
        <div class="otpravlennyye__item">
            <h3 class="otpravlennyye__item_title">
                <?= $arFields["NAME"] . ' № ' . $arFields["ID"]?>
            </h3>

            <h4 class="success">Направлено в страховую компанию</h4>

            <div class="otpravlennyye__item_data">
                дата: <?= $arFields['PROPERTY_SEND_MESSAGE_VALUE'] ?>
            </div>

            <p class="otpravlennyye__item_text">
                В соответствии с действующим законодательством
                в течение 30 дней вам должны предоставить ответ на обращение либо проинформировать о
                продлении срока рассмотрения обращения, если для решения поставленных вопросов нужно
                проведение экспертизы
            </p>
        </div>
    </div>
    <div class="obrashcheniya__content_sidebar">
        <div class="otpravlennyye__item_title">
            Прикрепленные файлы
        </div>
        <!-- Item Sidebar -->
        <div class="block__items_flex">
            <?php if ($img_first) { ?>
                <div class="obrashcheniya__content_sidebar_blocks">
                    <div class="obrashcheniya__content_sidebar_blocks_img">

                        <?php if ($pdf_1) { ?>
                            <img src="/local/templates/kdteam/images/svg/pdf_icon.svg" alt="" style="height: 45px;">
                        <? } else { ?>
                            <img src="<?=$img_first?>">
                        <?php } ?>

                    </div>
                    <div class="obrashcheniya__content_sidebar_blocks_text">
                        <div class="obrashcheniya__content_sidebar_blocks_text_title">Загруженный документ</div>
                        <a href="<?=$img_first?>" class="obrashcheniya__content_sidebar_blocks_text_link">скачать</a>
                    </div>
                </div>
            <?php } ?>
            <?php if ($img_second) { ?>
                <div class="obrashcheniya__content_sidebar_blocks">
                    <div class="obrashcheniya__content_sidebar_blocks_img">
                        <?php if ($pdf_2) { ?>
                            <img src="/local/templates/kdteam/images/svg/pdf_icon.svg" alt="" style="height: 45px;">
                        <? } else { ?>
                            <img src="<?=$img_second?>">
                        <?php } ?>
                    </div>
                    <div class="obrashcheniya__content_sidebar_blocks_text">
                        <div class="obrashcheniya__content_sidebar_blocks_text_title">Загруженный документ</div>
                        <a href="<?=$img_second?>" class="obrashcheniya__content_sidebar_blocks_text_link">скачать</a>
                    </div>
                </div>
            <?php } ?>
            <?php if ($img_third) { ?>
                <div class="obrashcheniya__content_sidebar_blocks">
                    <div class="obrashcheniya__content_sidebar_blocks_img">
                        <?php if ($pdf_3) { ?>
                            <img src="/local/templates/kdteam/images/svg/pdf_icon.svg" alt="" style="height: 45px;">
                        <? } else { ?>
                            <img src="<?=$img_third?>">
                        <?php } ?>
                    </div>
                    <div class="obrashcheniya__content_sidebar_blocks_text">
                        <div class="obrashcheniya__content_sidebar_blocks_text_title">Загруженный документ</div>
                        <a href="<?=$img_third?>" class="obrashcheniya__content_sidebar_blocks_text_link">скачать</a>
                    </div>
                </div>
            <?php } ?>
            <?php if ($img_fourth) { ?>
                <div class="obrashcheniya__content_sidebar_blocks">
                    <div class="obrashcheniya__content_sidebar_blocks_img">
                        <?php if ($pdf_4) { ?>
                            <img src="/local/templates/kdteam/images/svg/pdf_icon.svg" alt="" style="height: 45px;">
                        <? } else { ?>
                            <img src="<?=$img_fourth?>">
                        <?php } ?>
                    </div>
                    <div class="obrashcheniya__content_sidebar_blocks_text">
                        <div class="obrashcheniya__content_sidebar_blocks_text_title">Загруженный документ</div>
                        <a href="<?=$img_third?>" class="obrashcheniya__content_sidebar_blocks_text_link">скачать</a>
                    </div>
                </div>
            <?php } ?>
            <?php if ($img_fifth) { ?>
                <div class="obrashcheniya__content_sidebar_blocks">
                    <div class="obrashcheniya__content_sidebar_blocks_img">
                        <?php if ($pdf_5) { ?>
                            <img src="/local/templates/kdteam/images/svg/pdf_icon.svg" alt="" style="height: 45px;">
                        <? } else { ?>
                            <img src="<?=$img_fifth?>">
                        <?php } ?>
                    </div>
                    <div class="obrashcheniya__content_sidebar_blocks_text">
                        <div class="obrashcheniya__content_sidebar_blocks_text_title">Загруженный документ</div>
                        <a href="<?=$img_fifth?>" class="obrashcheniya__content_sidebar_blocks_text_link">скачать</a>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="obrashcheniya__content_sidebar_blocks">
            <div class="obrashcheniya__content_sidebar_blocks_img">
                <img src="/local/templates/kdteam/images/svg/pdf_icon.svg" alt="">
            </div>
            <div class="obrashcheniya__content_sidebar_blocks_text">
                <div class="obrashcheniya__content_sidebar_blocks_text_title">
                    Заявление на возврат
                </div>
                <a target="_blank" class="obrashcheniya__content_sidebar_blocks_text_link " href="<?= sprintf(obrashcheniya_report_url_download, $arFields["ID"]) ?>">
                    просмотреть
                </a>
            </div>
        </div>
    </div>
</div>
<?
    }
} ?>




<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
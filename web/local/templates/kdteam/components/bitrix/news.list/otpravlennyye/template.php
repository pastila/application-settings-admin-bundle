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
require_once($_SERVER["DOCUMENT_ROOT"]."/symfony-integration/config_obrashcheniya.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/symfony-integration/obrashcheniya_helper.php");

?>
    <!-- Pages Title -->
    <h1 class="page-title">Ваши обращения</h1>
<?php
global $USER;

$arSelect = array("ID", "NAME", "PROPERTY_SURNAME", "PROPERTY_PARTONYMIC", "PROPERTY_POLICY", "PROPERTY_COMPANY");
$arFilter = array("IBLOCK_ID" => 21, 'SECTION_CODE' => $USER->GetID());

$res = CIBlockElement::GetList(array(), $arFilter, false, false, $arSelect);
while ($ob = $res->GetNextElement()) {
    $arFields[] = $ob->GetFields();
}
?>


<?php
if (count($arResult["ITEMS"]) > 0) {
    foreach ($arResult["ITEMS"] as $arItem) {
          $date = explode(" ", $arItem['TIMESTAMP_X']);
          $hospital = htmlspecialchars_decode($arItem["PROPERTIES"]["HOSPITAL"]["VALUE"]);

          $array = [];
          $rsUser = $USER->GetByLogin($USER->GetLogin());
          if ($arUser = $rsUser->Fetch())
          {
            $array = getAppealFromSymfony($arItem["ID"], $arUser['LOGIN']);
          }
        ?>

        <!-- Обращение -->
        <div id="appeal_<?= $arItem["ID"] ?>" class="obrashcheniya">
            <div id="card_<?= $arItem["ID"] ?>" class="white_block">


                <p id="error_<?= $arItem["ID"] ?>" class="semi-bold error mb-2"></p>
                <?php if ($arItem["PROPERTIES"]["VISIT_DATE"]["VALUE"] == "") { ?>
                    <p id="error_<?= $arItem["ID"] ?>"
                       class="semi-bold error mb-2 datepicker-here_<?= $arItem["ID"] ?>">Введите
                        дату оплаты</p>
                <?php } ?>

                <?php $photo_sum = (int)$arItem["PROPERTIES"]['IMG_1']["VALUE"] + (int)$arItem["PROPERTIES"]['IMG_2']["VALUE"] + (int)$arItem["PROPERTIES"]['IMG_3']["VALUE"] + (int)$arItem["PROPERTIES"]['IMG_4']["VALUE"] + (int)$arItem["PROPERTIES"]['IMG_5']["VALUE"]; ?>

                <?php if ($photo_sum == 0 && countExistFile($array) === 0) { ?>
                    <p class="semi-bold error mb-2 photo_empty_all_<?= $arItem["ID"] ?>">Нужно добавить скан
                        документа/изображения для отправки обращения страховой компании</p>
                <?php } ?>

                <p id="success_<?= $arItem["ID"] ?>" class="semi-bold success mb-2"></p>
                <p class="semi-bold obrashcheniya__content_left_center_item_text-full hidden with_out_pdf error mb-2">
                    Без
                    сформированного документа PDF нельзя отрпавить обращение</p>

                <!-- Контент -->
                <div class="obrashcheniya__content">
                    <!-- Контент левая сторона -->
                    <div class="obrashcheniya__content_left">
                        <div class="tumbler_users-tab">
                            <p class="tumbler_users-tab__item add-user__js current" data_el="<?= $arItem["ID"] ?>"
                               id="remove_child-button">Медицинская помощь оказана мне</p>
                            <p class="tumbler_users-tab__item add-child__js" data_el="<?= $arItem["ID"] ?>"
                               id="add_child-button">Медицинская помощь оказана другому лицу</p>
                            <input value="my" id="selected_sender_<?= $arItem["ID"] ?>" type="hidden">
                        </div>
                        <!-- Внутри контента Верхняя часть -->
                        <div class="obrashcheniya__content_left_top">
                            <div class="obrashcheniya__content_left_top_text">
                                Обращение № <?php echo $arItem['ID'] ?>
                            </div>

                            <div class="obrashcheniya__content_left_top_data">
                                дата: <?php echo $date[0] ?>
                            </div>

                            <div class="obrashcheniya__content_left_top_link">
                                <a title="Редактировать обращение" class="obrashcheniya__content_left_top_link__icon"
                                   onclick="edit(this)" id="edit_<?= $arItem["ID"] ?>"><img
                                            src="<?= SITE_TEMPLATE_PATH ?>/images/svg/edit.svg" alt=""></a>
                                <a title="Сохранить обращение" class="obrashcheniya__content_left_top_link__icon"
                                   style="display: none" onclick="save(this)" id="save_<?= $arItem["ID"] ?>">
                                    <img src="<?= SITE_TEMPLATE_PATH ?>/images/svg/save.svg" alt="">
                                    <!--                                    <img src="/images/gif/loading.gif"  style="display: none" alt="" id="gif_save_-->
                                    <?//= $arItem['ID'] ?>
                                    <!--">-->
                                </a>
                                <a title="Удалить обращение" class="obrashcheniya__content_left_top_link__icon"
                                   onclick="delete_el(this)" id="delete_el_<?= $arItem["ID"] ?>"><img
                                            src="<?= SITE_TEMPLATE_PATH ?>/images/svg/remove_file.svg" alt=""></a>
                            </div>
                        </div>

                        <!-- Внутри контента центральная часть -->
                        <div class="obrashcheniya__content_left_center">

                            <!-- Item ы -->
                            <div class="obrashcheniya__content_left_center_item">
                                <div class="obrashcheniya__content_left_center_item_text">
                                    ФИО
                                </div>

                                <p id="usrname_<?= $arItem['ID'] ?>"
                                   class="obrashcheniya__content_left_center_item_text-full">
                                    <?php echo $arItem["PROPERTIES"]["FULL_NAME"]["VALUE"] ?></p>
                                <div class="input__wrap no_margin input__wrap_width">
                                    <input style="display: none" type="text" name="usrname"
                                           value="<?= $arItem["PROPERTIES"]["FULL_NAME"]["VALUE"] ?>">
                                </div>
                            </div>
                            <div class="obrashcheniya__content_left_center_item">
                                <div class="obrashcheniya__content_left_center_item_text">
                                    Мобильный номер
                                </div>

                                <p id="phone_<?= $arItem['ID'] ?>"
                                   class="obrashcheniya__content_left_center__item_text-full">
                                    <?php echo $arItem["PROPERTIES"]["MOBAIL_PHONE"]["VALUE"] ?></p>
                                <div class="input__wrap no_margin input__wrap_width">
                                    <input style="display: none" type="text" name="phone"
                                           value="<?= $arItem["PROPERTIES"]["MOBAIL_PHONE"]["VALUE"] ?>">
                                </div>
                            </div>

                            <!-- Item ы -->
                            <div class="obrashcheniya__content_left_center_item">
                                <div class="obrashcheniya__content_left_center_item_text">
                                    Больница:
                                </div>

                                <p id="hospitl_<?= $arItem['ID'] ?>"
                                   class="obrashcheniya__content_left_center_item_text-full">
                                    <?php echo $hospital ?></p>
                            </div>

                            <!-- Item ы -->
                            <div class="obrashcheniya__content_left_center_item">
                                <div class="obrashcheniya__content_left_center_item_text">
                                    Адрес:
                                </div>

                                <p class="obrashcheniya__content_left_center__item_text-full">
                                    <?php echo $arItem["PROPERTIES"]["ADDRESS"]["VALUE"] ?>
                                </p>
                            </div>

                            <div class="obrashcheniya__content_left_center_item">
                                <div class="obrashcheniya__content_left_center_item_text">
                                    Полис:
                                </div>

                                <p id="policy_<?= $arItem['ID'] ?>"
                                   class="obrashcheniya__content_left_center__item_text-full">
                                    <?php echo $arItem["PROPERTIES"]["POLICY"]["VALUE"] ?></p>
                                <div class="input__wrap no_margin input__wrap_width">
                                    <input style="display: none" type="text" name="policy" maxlength="16" minlength="16"
                                           class="numberInput" value="<?= $arItem["PROPERTIES"]["POLICY"]["VALUE"] ?>">
                                </div>
                            </div>

                            <div class="obrashcheniya__content_left_center_item">
                                <div class="obrashcheniya__content_left_center_item_text blue">
                            <span class="obrashcheniya__content_left_center_item_date-block">Дата оплаты медицинских
                                услуг:
                                
                            </span>
                                </div>

                                <div class="input__wrap no_margin input__wrap_width obrashcheniya__content_left_wrap">

                                    <p id="time_<?= $arItem['ID'] ?>"
                                       class="obrashcheniya__content_left_center__item_text-full">
                                        <?php echo $arItem["PROPERTIES"]["VISIT_DATE"]["VALUE"] ?>
                                    </p>

                                    <div class="obrashcheniya__content_left_center_item_date-block_edit"
                                         data-id-date_picker_edit="<?= $arItem['ID'] ?>" onclick="edit_date(this)">
<?if ($arItem["PROPERTIES"]["VISIT_DATE"]["VALUE"]):?>
                                        <img src="<?= SITE_TEMPLATE_PATH ?>/images/svg/edit.svg" alt=""
                                             id="svg_edit_<?= $arItem['ID'] ?>">
<?else:?>
                                        <img src="<?= SITE_TEMPLATE_PATH ?>/images/svg/edit-r.svg" alt=""
                                             id="svg_edit_<?= $arItem['ID'] ?>">
<?endif?>
                                    </div>

                                    <div class="obrashcheniya__content_left_center_item_date-block_edit"
                                         data-id-date_picker_save="<?= $arItem['ID'] ?>" onclick="save_date(this)"
                                         style="display: none">
                                        <img src="<?= SITE_TEMPLATE_PATH ?>/images/svg/save-r.svg" alt=""
                                             id="svg_save_<?= $arItem['ID'] ?>">
                                        <img src="/images/gif/loading.gif" style="display: none" alt=""
                                             id="gif_<?= $arItem['ID'] ?>">
                                    </div>

                                    <?php
                                    $year = "";
                                    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PROPERTY_*");
                                    $arFilter = Array(
                                        "IBLOCK_ID" => 17,
                                        "ACTIVE" => "Y",
                                        "ID" => $arItem["PROPERTIES"]["YEARS"]["VALUE"][0]
                                    );
                                    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                                    while ($ob = $res->GetNextElement()) {
                                        $arProps = $ob->GetFields();
                                        $year = $arProps["NAME"];
                                    }


                                    ?>
                                    <input style="display: none"
                                           class="datepicker-here_obrashcheniya_<?= $arItem['ID'] ?>"
                                           data-date="<?= $year; ?>" type="text" name="time"
                                           value="<?= $arItem["PROPERTIES"]["VISIT_DATE"]["VALUE"] ?>"
                                           id="date_picker_<?= $arItem['ID'] ?>" readonly>

                                </div>

                            </div>
                        </div>
                    </div>


                    <!-- Контент левая сторона c данными ребёнка -->
                    <div class="obrashcheniya__content_left hidden_child-block">
                        <?php if (count($arFields) > 0) { ?>
                            <div class="input-with-search">
                                <label class="title-select" for="user_pass">Укажите лицо, получавшее медицинсую помощь
                                    (ребенка, опекаемый)</label>
                                <div class="input__wrap">
                                    <div class="input__ico">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255"
                                             viewBox="0 0 255 255">
                                            <path d="M0 63.75l127.5 127.5L255 63.75z"/>
                                        </svg>
                                    </div>
                                    <input id="children_input_<?= $arItem['ID'] ?>" class="children_input"
                                           data-value="<?= $arItem['ID'] ?>" data-id_child="" name="region_input"
                                           type="text"
                                           placeholder="Не выбрано" autocomplete="off"/>
                                    <!--                                <span style="display: none" class="error_search-js">Выберете опекаемого человека</span>-->
                                    <ul style="cursor: pointer;" class="custom-serach__items"
                                        id="searchul_<?= $arItem['ID'] ?>">
                                        <?php
                                        foreach ($arFields as $arSection) { ?>
                                            <li value="<?= $arSection["ID"] ?>"
                                                class="custom-serach__items_item childrenjs_<?= $arItem['ID'] ?>"><?php echo $arSection['PROPERTY_SURNAME_VALUE'] .' ' ?><?php echo $arSection['NAME']  .' '?><?php echo $arSection['PROPERTY_PARTONYMIC_VALUE'] ?></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        <?php } else { ?>
                            <a href="/personal-cabinet/">Для добавления ребенка перейдите в личный кабинет</a>
                        <?php } ?>
                        <!-- Внутри контента центральная часть с данными ребёнка -->
                        <div id="selected-child_<?= $arItem['ID'] ?>"
                             class="obrashcheniya__content_left_center child_block-items">
                        </div>
                    </div>

                    <!-- Контент правая сторона -->
                    <div class="obrashcheniya__content_sidebar" data-obrashenie-id="<?= $arItem["ID"] ?>">
                        <div class="obrashcheniya__content_sidebar_title">
                            Прикрепленные файлы
                        </div>

                        <!-- Item Sidebar -->
                        <div class="js-img-add block__items_flex" data-block_img="<?= $arItem["ID"] ?>">
                            <?php if (!empty($arItem["PROPERTIES"]["IMG_1"]['VALUE']) || findExistFile($array, 1)) {
                                $pdf = false;
                                $file = CFile::GetFileArray($arItem["PROPERTIES"]["IMG_1"]['VALUE']);
                                $url_load = findExistFile($array, 1) ?
                                  sprintf(obrashcheniya_report_url_download, $arItem["ID"]) . '?image_number=1' :
                                  $file['SRC'];
                                if ($file["CONTENT_TYPE"] == "application/pdf" || fileIsPdf($array, 1)) {
                                    $pdf = true;
                                }
                                ?>
                                <div id="img_block_<?= $arItem['ID'] ?>_img_1"
                                     class="obrashcheniya__content_sidebar_blocks">
                                    <div class="obrashcheniya__content_sidebar_blocks_img">
                                        <?php if ($pdf) { ?>
                                            <img src="/local/templates/kdteam/images/svg/pdf_icon.svg" alt="">
                                        <? } else { ?>
                                            <img src="<?= $url_load ?>"
                                                 alt="">
                                        <?php } ?>
                                    </div>

                                    <div class="obrashcheniya__content_sidebar_blocks_text">
                                        <div class="obrashcheniya__content_sidebar_blocks_text_title">
                                            <?= CFile::GetFileArray($arItem["PROPERTIES"]["IMG_1"]['VALUE'])["FILE_NAME"] ?>
                                        </div>
                                        <a id="download_img" download
                                           href="<?= $url_load ?>"
                                           class="obrashcheniya__content_sidebar_blocks_text_link">
                                            скачать
                                        </a>
                                        <a rel="nofollow" id="delete_<?= $arItem['ID'] ?>_img_1" onclick="del(this)"
                                           class="delete_img_js">удалить
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arItem["PROPERTIES"]["IMG_2"]['VALUE']) || findExistFile($array, 2)) {
                                $pdf = false;

                                $file = CFile::GetFileArray($arItem["PROPERTIES"]["IMG_2"]['VALUE']);
                                $url_load = findExistFile($array, 2) ?
                                    sprintf(obrashcheniya_report_url_download, $arItem["ID"]) . '?image_number=2' :
                                    $file['SRC'];
                                if ($file["CONTENT_TYPE"] == "application/pdf" || fileIsPdf($array, 2)) {
                                    $pdf = true;
                                }?>
                                <div id="img_block_<?= $arItem['ID'] ?>_img_2"
                                     class="obrashcheniya__content_sidebar_blocks">
                                    <div class="obrashcheniya__content_sidebar_blocks_img">
                                        <?php if ($pdf) { ?>
                                            <img src="/local/templates/kdteam/images/svg/pdf_icon.svg" alt="">
                                        <? } else { ?>
                                            <img src="<?= $url_load ?>"
                                                 alt="">
                                        <?php } ?>
                                    </div>

                                    <div class="obrashcheniya__content_sidebar_blocks_text">
                                        <div class="obrashcheniya__content_sidebar_blocks_text_title">
                                            <?= CFile::GetFileArray($arItem["PROPERTIES"]["IMG_2"]['VALUE'])["FILE_NAME"] ?>
                                        </div>
                                        <a id="download_img" download
                                           href="<?= $url_load ?>"
                                           class="obrashcheniya__content_sidebar_blocks_text_link">
                                            скачать
                                        </a>
                                        <a rel="nofollow" id="delete_<?= $arItem['ID'] ?>_img_2" onclick="del(this)"
                                           class="delete_img_js">удалить
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arItem["PROPERTIES"]["IMG_3"]['VALUE']) || findExistFile($array, 3)) {
                                $pdf = false;

                                $file = CFile::GetFileArray($arItem["PROPERTIES"]["IMG_3"]['VALUE']);
                                $url_load = findExistFile($array, 3) ?
                                    sprintf(obrashcheniya_report_url_download, $arItem["ID"]) . '?image_number=3' :
                                    $file['SRC'];
                                if ($file["CONTENT_TYPE"] == "application/pdf" || fileIsPdf($array, 3)) {
                                    $pdf = true;
                                }?>
                                <div id="img_block_<?= $arItem['ID'] ?>_img_3"
                                     class="obrashcheniya__content_sidebar_blocks">
                                    <div class="obrashcheniya__content_sidebar_blocks_img">
                                        <?php if ($pdf) { ?>
                                            <img src="/local/templates/kdteam/images/svg/pdf_icon.svg" alt="">
                                        <? } else { ?>
                                            <img src="<?= $url_load ?>"
                                                 alt="">
                                        <?php } ?>
                                    </div>

                                    <div class="obrashcheniya__content_sidebar_blocks_text">
                                        <div class="obrashcheniya__content_sidebar_blocks_text_title">
                                            <?= CFile::GetFileArray($arItem["PROPERTIES"]["IMG_3"]['VALUE'])["FILE_NAME"] ?>
                                        </div>
                                        <a id="download_img" download
                                           href="<?= $url_load ?>"
                                           class="obrashcheniya__content_sidebar_blocks_text_link">
                                            скачать
                                        </a>
                                        <a rel="nofollow" id="delete_<?= $arItem['ID'] ?>_img_3" onclick="del(this)"
                                           class="delete_img_js">удалить
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arItem["PROPERTIES"]["IMG_4"]['VALUE']) || fileIsPdf($array, 4)) {
                                $pdf = false;
                                $file = CFile::GetFileArray($arItem["PROPERTIES"]["IMG_4"]['VALUE']);
                                $url_load = findExistFile($array, 4) ?
                                    sprintf(obrashcheniya_report_url_download, $arItem["ID"]) . '?image_number=4' :
                                    $file['SRC'];
                                if ($file["CONTENT_TYPE"] == "application/pdf" || (findExistFile($array, 4) && getTypeByExt($array[3]['file']) === 'pdf')) {
                                    $pdf = true;
                                }?>
                                <div id="img_block_<?= $arItem['ID'] ?>_img_4"
                                     class="obrashcheniya__content_sidebar_blocks">
                                    <div class="obrashcheniya__content_sidebar_blocks_img">
                                        <?php if ($pdf) { ?>
                                            <img src="/local/templates/kdteam/images/svg/pdf_icon.svg" alt="">
                                        <? } else { ?>
                                            <img src="<?= $url_load ?>"
                                                 alt="">
                                        <?php } ?>
                                    </div>

                                    <div class="obrashcheniya__content_sidebar_blocks_text">
                                        <div class="obrashcheniya__content_sidebar_blocks_text_title">
                                            <?= CFile::GetFileArray($arItem["PROPERTIES"]["IMG_4"]['VALUE'])["FILE_NAME"] ?>
                                        </div>
                                        <a id="download_img" download
                                           href="<?= $url_load ?>"
                                           class="obrashcheniya__content_sidebar_blocks_text_link">
                                            скачать
                                        </a>
                                        <a rel="nofollow" id="delete_<?= $arItem['ID'] ?>_img_4" onclick="del(this)"
                                           class="delete_img_js">удалить
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arItem["PROPERTIES"]["IMG_5"]['VALUE']) || findExistFile($array, 5)) {
                                $pdf = false;
                                $file = CFile::GetFileArray($arItem["PROPERTIES"]["IMG_5"]['VALUE']);
                                $url_load = findExistFile($array, 5) ?
                                    sprintf(obrashcheniya_report_url_download, $arItem["ID"]) . '?image_number=5' :
                                    $file['SRC'];
                                if ($file["CONTENT_TYPE"] == "application/pdf" || fileIsPdf($array, 5)) {
                                    $pdf = true;
                                }?>
                                <div id="img_block_<?= $arItem['ID'] ?>_img_5"
                                     class="obrashcheniya__content_sidebar_blocks">
                                    <div class="obrashcheniya__content_sidebar_blocks_img">
                                        <?php if ($pdf) { ?>
                                            <img src="/local/templates/kdteam/images/svg/pdf_icon.svg" alt="">
                                        <? } else { ?>
                                            <img src="<?= $url_load ?>"
                                                 alt="">
                                        <?php } ?>
                                    </div>

                                    <div class="obrashcheniya__content_sidebar_blocks_text">
                                        <div class="obrashcheniya__content_sidebar_blocks_text_title">
                                            <?= CFile::GetFileArray($arItem["PROPERTIES"]["IMG_5"]['VALUE'])["FILE_NAME"] ?>
                                        </div>
                                        <a id="download_img" download
                                           href="<?= $url_load ?>"
                                           class="obrashcheniya__content_sidebar_blocks_text_link">
                                            скачать
                                        </a>
                                        <a rel="nofollow" id="delete_<?= $arItem['ID'] ?>_img_5" onclick="del(this)"
                                           class="delete_img_js">удалить
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="obrashcheniya__content_sidebar_blocks" data-pdf-id="<?= $arItem["ID"] ?>">

                            <div class="obrashcheniya__content_sidebar_blocks_img">
                                <img src="/local/templates/kdteam/images/svg/pdf_icon.svg" alt="">
                            </div>

                            <div class="obrashcheniya__content_sidebar_blocks_text">
                                <div class="obrashcheniya__content_sidebar_blocks_text_title">
                                    Заявление на возврат
                                </div>

                                <?php
                                $url_pdf = CFile::GetPath($arItem["PROPERTIES"]["PDF"]["VALUE"]);
                                $url_pdf = existAppeal($array) > 0 ?
                                  sprintf(obrashcheniya_report_url_download, $arItem["ID"]) :
                                  "";
                                ?>
                                <a target="_blank" class=" pdf
                                    <?php if ($url_pdf == "") { ?>success<?} ?>"
                                    href="<?= sprintf(obrashcheniya_report_url_download, $arItem["ID"])?>">
                                    <?php if ($url_pdf != "") { ?> просмотреть
                                    <? } else { ?>
                                        После формирования обращения Ваш файл будет доступен для скачивания по этой ссылке
                                      <?
                                    } ?>
                                </a>
                                <div class="hidden ready_pdf success"> Файл пдф сформирован</div>
                                <div class="hidden updata_pdf success"> Файл пдф обновлен</div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="obrashcheniya__btns">
                    <div class="obrashcheniya__btns_btn">
                        <input class="smallMainBtn hidden_browse_input" id="<?= $arItem["ID"] ?>" type="file" multiple
                               name="file">
                        <label for="<?= $arItem["ID"] ?>" class="btn btn-tertiary js-labelFile mainBtn">
                            Прикрепить скан или фото</label>
                    </div>
                    <a class="obrashcheniya__btns_btn accentBtn" onclick="send_ms(this)"
                       id="send_<?= $arItem["ID"] ?>">Отправить</a>
                </div>
            </div>
        </div>
    <?php }
} else { ?>
    <div class="obrashcheniya">
        <p> У вас нет готовых обращений.</p>
    </div>
    <a href="/forma-obrashenija/" class="mainBtn">Сформировать обращение</a>
<?php } ?>

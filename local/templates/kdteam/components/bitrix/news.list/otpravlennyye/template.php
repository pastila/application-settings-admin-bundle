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

<!-- Pages Title -->
<h2 class="page-title">Ваши обращения</h2>


<?php
if (count($arResult["ITEMS"]) > 0) {
    foreach ($arResult["ITEMS"] as $arItem) {
        $date = explode(" ", $arItem['TIMESTAMP_X']);
        $hospital = htmlspecialchars_decode($arItem["PROPERTIES"]["HOSPITAL"]["VALUE"]);
        ?>
        <!-- Обращение -->
        <div id="appeal_<?= $arItem["ID"] ?>" class="obrashcheniya">
            <div class="obrashcheniya__btns">
                <div class="block__button_send">
                    <input class="smallMainBtn hidden_browse_input" id="<?= $arItem["ID"] ?>" type="file" multiple
                           name="file">
                    <label for="<?= $arItem["ID"] ?>" class="btn btn-tertiary js-labelFile smallMainBtn">
                        Прикрепить скан или фото</label>
                </div>
                <a class="smallMainBtn block_button_mob" onclick="send_ms(this)" id="send_<?= $arItem["ID"] ?>">Отправить</a>
            </div>
            <p id="error_<?= $arItem["ID"] ?>" class="error"></p>
            <p id="success_<?= $arItem["ID"] ?>" class="success"></p>
            <div class="obrashcheniya__content_left_center_item_text-full hidden with_out_pdf error">Без  сформированного документа PDF нельзя отрпавить обращение</div>
            <div id="card_<?= $arItem["ID"] ?>" class="card">

                <!-- Контент -->
                <div class="obrashcheniya__content">
                    <!-- Контент левая сторона -->
                    <div class="obrashcheniya__content_left">

                        <!-- Внутри контента Верхняя часть -->
                        <div class="obrashcheniya__content_left_top">
                            <div class="obrashcheniya__content_left_top_text">
                                Обращение № <?php echo $arItem['ID'] ?>
                            </div>

                            <div class="obrashcheniya__content_left_top_data">
                                дата: <?php echo $date[0] ?>
                            </div>

                            <div class="obrashcheniya__content_left_top_link">
                                <a onclick="edit(this)" id="edit_<?= $arItem["ID"] ?>">Редактировать</a>
                                <a style="display: none" onclick="save(this)"
                                   id="save_<?= $arItem["ID"] ?>">Сохранить</a>

                                <a onclick="delete_el(this)" id="delete_el_<?= $arItem["ID"] ?>">Удалить</a>
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

                                <input style="display: none" type="text" name="usrname"
                                       value="<?= $arItem["PROPERTIES"]["FULL_NAME"]["VALUE"] ?>">
                            </div>

                            <!-- Item ы -->
                            <div class="obrashcheniya__content_left_center_item">
                                <div class="obrashcheniya__content_left_center_item_text">
                                    Больница:
                                </div>

                                <p id="hospitl_<?= $arItem['ID'] ?>" class="obrashcheniya__content_left_center_item_text-full"><?php echo $hospital ?></p>
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
                                <input style="display: none"
                                       type="text"
                                       name="policy"
                                       value="<?= $arItem["PROPERTIES"]["POLICY"]["VALUE"] ?>">
                            </div>

                            <div class="obrashcheniya__content_left_center_item">
                                <div class="obrashcheniya__content_left_center_item_text">
                                    Дата оплаты медицинских услуг:
                                </div>

                                <p id="time_<?= $arItem['ID'] ?>"
                                   class="obrashcheniya__content_left_center__item_text-full">
                                    <?php echo $arItem["PROPERTIES"]["VISIT_DATE"]["VALUE"] ?></p>
                                <input style="display: none" class="datepicker-here"
                                       type="text"
                                       name="time"
                                       value="<?= $arItem["PROPERTIES"]["VISIT_DATE"]["VALUE"] ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Контент правая сторона -->
                    <div class="obrashcheniya__content_sidebar" data-obrashenie-id="<?= $arItem["ID"] ?>">
                        <div class="obrashcheniya__content_sidebar_title">
                            Прикрепленные файлы
                        </div>

                        <!-- Item Sidebar -->
                        <div class="js-img-add block__items_flex">
                            <?php if (!empty($arItem["PREVIEW_PICTURE"]["SRC"])) { ?>
                                <div id="img_block_<?= $arItem['ID'] ?>_img_1"
                                     class="obrashcheniya__content_sidebar_blocks">
                                    <div class="obrashcheniya__content_sidebar_blocks_img">
                                        <img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>" alt="">
                                    </div>

                                    <div class="obrashcheniya__content_sidebar_blocks_text">
                                        <div class="obrashcheniya__content_sidebar_blocks_text_title">
                                            Загруженный документ
                                        </div>
                                        <a id="download_img" download href="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                                           class="obrashcheniya__content_sidebar_blocks_text_link">
                                            скачать
                                        </a>
                                        <a rel="nofollow" id="delete_<?= $arItem['ID'] ?>_img_1" onclick="del(this)"
                                           class="delete_img_js">удалить
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arItem["PROPERTIES"]["IMG_2"]['VALUE'])) { ?>
                                <div id="img_block_<?= $arItem['ID'] ?>_img_2"
                                     class="obrashcheniya__content_sidebar_blocks">
                                    <div class="obrashcheniya__content_sidebar_blocks_img">
                                        <img src="<?= CFile::GetFileArray($arItem["PROPERTIES"]["IMG_2"]['VALUE'])["SRC"] ?>"
                                             alt="">
                                    </div>

                                    <div class="obrashcheniya__content_sidebar_blocks_text">
                                        <div class="obrashcheniya__content_sidebar_blocks_text_title">
                                            Загруженный документ
                                        </div>
                                        <a id="download_img" download
                                           href="<?= CFile::GetFileArray($arItem["PROPERTIES"]["IMG_2"]['VALUE'])["SRC"] ?>"
                                           class="obrashcheniya__content_sidebar_blocks_text_link">
                                            скачать
                                        </a>
                                        <a rel="nofollow" id="delete_<?= $arItem['ID'] ?>_img_2" onclick="del(this)"
                                           class="delete_img_js">удалить
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arItem["PROPERTIES"]["IMG_3"]['VALUE'])) { ?>
                                <div id="img_block_<?= $arItem['ID'] ?>_img_3"
                                     class="obrashcheniya__content_sidebar_blocks">
                                    <div class="obrashcheniya__content_sidebar_blocks_img">
                                        <img src="<?= CFile::GetFileArray($arItem["PROPERTIES"]["IMG_3"]['VALUE'])["SRC"] ?>"
                                             alt="">
                                    </div>

                                    <div class="obrashcheniya__content_sidebar_blocks_text">
                                        <div class="obrashcheniya__content_sidebar_blocks_text_title">
                                            Загруженный документ
                                        </div>
                                        <a id="download_img" download
                                           href="<?= CFile::GetFileArray($arItem["PROPERTIES"]["IMG_3"]['VALUE'])["SRC"] ?>"
                                           class="obrashcheniya__content_sidebar_blocks_text_link">
                                            скачать
                                        </a>
                                        <a rel="nofollow" id="delete_<?= $arItem['ID'] ?>_img_3" onclick="del(this)"
                                           class="delete_img_js">удалить
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arItem["PROPERTIES"]["IMG_4"]['VALUE'])) { ?>
                                <div id="img_block_<?= $arItem['ID'] ?>_img_4"
                                     class="obrashcheniya__content_sidebar_blocks">
                                    <div class="obrashcheniya__content_sidebar_blocks_img">
                                        <img src="<?= CFile::GetFileArray($arItem["PROPERTIES"]["IMG_4"]['VALUE'])["SRC"] ?>"
                                             alt="">
                                    </div>

                                    <div class="obrashcheniya__content_sidebar_blocks_text">
                                        <div class="obrashcheniya__content_sidebar_blocks_text_title">
                                            Загруженный документ
                                        </div>
                                        <a id="download_img" download
                                           href="<?= CFile::GetFileArray($arItem["PROPERTIES"]["IMG_4"]['VALUE'])["SRC"] ?>"
                                           class="obrashcheniya__content_sidebar_blocks_text_link">
                                            скачать
                                        </a>
                                        <a rel="nofollow" id="delete_<?= $arItem['ID'] ?>_img_4" onclick="del(this)"
                                           class="delete_img_js">удалить
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arItem["PROPERTIES"]["IMG_5"]['VALUE'])) { ?>
                                <div id="img_block_<?= $arItem['ID'] ?>_img_5"
                                     class="obrashcheniya__content_sidebar_blocks">
                                    <div class="obrashcheniya__content_sidebar_blocks_img">
                                        <img src="<?= CFile::GetFileArray($arItem["PROPERTIES"]["IMG_5"]['VALUE'])["SRC"] ?>"
                                             alt="">
                                    </div>

                                    <div class="obrashcheniya__content_sidebar_blocks_text">
                                        <div class="obrashcheniya__content_sidebar_blocks_text_title">
                                            Загруженный документ
                                        </div>
                                        <a id="download_img" download
                                           href="<?= CFile::GetFileArray($arItem["PROPERTIES"]["IMG_5"]['VALUE'])["SRC"] ?>"
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

                        <div class="obrashcheniya__content_sidebar_blocks">

                            <div class="obrashcheniya__content_sidebar_blocks_img">
                                <img src="/local/templates/kdteam/images/svg/pdf_icon.svg" alt="">
                            </div>

                            <div class="obrashcheniya__content_sidebar_blocks_text">
                                <div class="obrashcheniya__content_sidebar_blocks_text_title">
                                    Заявление на возврат
                                </div>

                                <?php
                                $url_pdf = CFile::GetPath($arItem["PROPERTIES"]["PDF"]["VALUE"]); ?>
                                <a class="obrashcheniya__content_sidebar_blocks_text_link pdf <?php if ($url_pdf == "") { ?>error<?}?>" <?php if ($url_pdf != "") { ?> href="<?= $url_pdf ?>"<? } ?> >
                                  <?php if ($url_pdf != "") { ?>  просмотреть <? }else{ ?> Заполните все поля для формирования pdf файла<?}?>
                                </a>
                                <div class="hidden ready_pdf success"> Файл пдф сформирован</div>
                                <div class="hidden updata_pdf success"> Файл пдф обновлен</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }
} else { ?>
    <div class="obrashcheniya">
        <p> У вас нету обращений. Пройдите проверку своего диагноза
            <a class="link-underline" href="/forma-obrashenija/">здесь</a>.</p>
    </div>
<?php } ?>


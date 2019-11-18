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

?>

<!-- Pages Title -->
<h2 class="page-title">Ваши обращения</h2>


<?php
$i = 0;
foreach ($arResult["ITEMS"] as $arItem) {
    $i++;
    $date = explode(" ", $arItem['TIMESTAMP_X']);
    $hospital = htmlspecialchars_decode($arItem["PROPERTIES"]["HOSPITAL"]["VALUE"]);
    ?>
    <!-- Обращение -->
    <div id="appeal_<?=$arItem["ID"]?>" class="obrashcheniya">
        <div class="obrashcheniya__btns">
            <input class="smallMainBtn" id="<?=$arItem["ID"]?>" type="file" multiple name="file">
            <label for="<?=$arItem["ID"]?>" class="btn btn-tertiary js-labelFile">
                <?php if (empty($arItem["PREVIEW_PICTURE"]["SRC"])) {?>
                      <span class="js-fileName">Прикрепить скан или фото</span>
                <?php } else { ?>
                      <span class="js-fileName">Изменить загруженный файл</span>
                <?php }?>
            </label>
            <a class="smallMainBtn" href="#">Отправить</a>
        </div>
        <p id="error_<?=$arItem["ID"]?>" class="error"></p>
        <p id="success_<?=$arItem["ID"]?>" class="success"></p>
        <div id="card_<?=$arItem["ID"]?>" class="card">
            <!-- Контент -->
            <div class="obrashcheniya__content">
                <!-- Контент левая сторона -->
                <div class="obrashcheniya__content_left">

                    <!-- Внутри контента Верхняя часть -->
                    <div class="obrashcheniya__content_left_top">
                        <div class="obrashcheniya__content_left_top_text">
                            Обращение № <?php echo $i?>
                        </div>

                        <div class="obrashcheniya__content_left_top_data">
                            дата: <?php echo $date[0]?>
                        </div>

                        <div class="obrashcheniya__content_left_top_link">
                            <a href="#" onclick="edit(this)" id="edit_<?=$arItem["ID"]?>">Редактировать</a>
                            <a href="#" onclick="save(this)" id="save_<?=$arItem["ID"]?>">Сохранить</a>

                            <a href="#" onclick="delete_el(this)" id="delete_el_<?=$arItem["ID"]?>">Удалить</a>
                        </div>
                    </div>

                    <!-- Внутри контента центральная часть -->
                    <div class="obrashcheniya__content_left_center">

                        <!-- Item ы -->
                        <div class="obrashcheniya__content_left_center_item">
                            <div class="obrashcheniya__content_left_center_item_text">
                                ФИО
                            </div>

                            <p id="usrname" class="obrashcheniya__content_left_center_item_text-full">
                                <?php echo $arItem["PROPERTIES"]["FULL_NAME"]["VALUE"]?>
                            </p>
                            <input style="display: none" type="text" name="usrname"
                                   value="<?=$arItem["PROPERTIES"]["FULL_NAME"]["VALUE"]?>">
                        </div>

                        <!-- Item ы -->
                        <div class="obrashcheniya__content_left_center_item">
                            <div class="obrashcheniya__content_left_center_item_text">
                                Больница:
                            </div>

                            <p class="obrashcheniya__content_left_center_item_text-full">
                                <?php echo $hospital?>
                            </p>
                        </div>

                        <!-- Item ы -->
                        <div class="obrashcheniya__content_left_center_item">
                            <div class="obrashcheniya__content_left_center_item_text">
                                Адрес:
                            </div>

                            <p class="obrashcheniya__content_left_center__item_text-full">
                                <?php echo $arItem["PROPERTIES"]["ADDRESS"]["VALUE"]?>
                            </p>
                        </div>

                        <div  class="obrashcheniya__content_left_center_item">
                            <div class="obrashcheniya__content_left_center_item_text">
                                Полис:
                            </div>

                            <p id="policy" class="obrashcheniya__content_left_center__item_text-full">
                                <?php echo $arItem["PROPERTIES"]["POLICY"]["VALUE"]?>
                            </p>
                            <input style="display: none"
                                   type="text"
                                   name="policy"
                                   value="<?=$arItem["PROPERTIES"]["POLICY"]["VALUE"]?>">
                        </div>

                        <div  class="obrashcheniya__content_left_center_item">
                            <div class="obrashcheniya__content_left_center_item_text">
                                Дата посещения больницы:
                            </div>

                            <p id="time" class="obrashcheniya__content_left_center__item_text-full">
                                <?php echo $arItem["PROPERTIES"]["VISIT_DATE"]["VALUE"]?>
                            </p>
                            <input style="display: none"
                                   type="date"
                                   name="time"
                                   value="<?=$arItem["PROPERTIES"]["VISIT_DATE"]["VALUE"]?>">
                        </div>
                    </div>
                </div>

                <!-- Контент правая сторона -->
                <div class="obrashcheniya__content_sidebar">
                    <div class="obrashcheniya__content_sidebar_title">
                        Прикрепленные файлы
                    </div>

                    <!-- Item Sidebar -->
                    <div class="js-img-add">
                        <?php if (!empty($arItem["PREVIEW_PICTURE"]["SRC"])) {?>
                            <div id="img_block_<?=$arItem['ID']?>" class="obrashcheniya__content_sidebar_blocks">
                                <div class="obrashcheniya__content_sidebar_blocks_img">
                                    <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="">
                                </div>

                                <div class="obrashcheniya__content_sidebar_blocks_text">
                                    <div class="obrashcheniya__content_sidebar_blocks_text_title">
                                        Загруженный документ
                                    </div>
                                    <a id="download_img" download href="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"
                                       class="obrashcheniya__content_sidebar_blocks_text_link">
                                        скачать
                                    </a>
                                    <button id="delete_<?=$arItem['ID']?>" onclick="del(this)"
                                            class="delete_img_js" >Удалить</button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <div class="obrashcheniya__content_sidebar_blocks">

                        <div class="obrashcheniya__content_sidebar_blocks_img">
<!--                            <img src="./local/templates/kdteam/images/svg/image_block_two.svg" alt="">-->
                        </div>

                        <div class="obrashcheniya__content_sidebar_blocks_text">
                            <div class="obrashcheniya__content_sidebar_blocks_text_title">
                                Заявление на возврат
                            </div>

                            <a class="obrashcheniya__content_sidebar_blocks_text_link">
                                скачать
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }?>


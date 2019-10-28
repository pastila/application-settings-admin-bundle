<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;
$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/obrashcheniya/main.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/obrashcheniya/main.min.js");
?>
<!-- Breadcrumbs -->
<ul class="breadcrumbs">
    <li><a href="/index.html">Главная</a></li>
    <li>обращения</li>
</ul>

<!-- Pages Title -->
<h2 class="page-title">Ваши обращения</h2>

<!-- Обращение -->
<div class="obrashcheniya">

    <div class="obrashcheniya__btns">
        <a class="smallMainBtn" href="#">Прикрепить скан или фото</a>

        <a class="smallMainBtn" href="#">Отправить</a>
    </div>

    <!-- Уведомления оь отправке -->
    <p class="success">Обращение успешно отправлено в страховую компанию. Ваше обращение находится в личном
        кабинете «Отправленные»</p>

    <div class="card">
        <!-- Контент -->
        <div class="obrashcheniya__content">
            <!-- Контент левая сторона -->
            <div class="obrashcheniya__content_left">

                <!-- Внутри контента Верхняя часть -->
                <div class="obrashcheniya__content_left_top">
                    <div class="obrashcheniya__content_left_top_text">
                        Обращение № 1
                    </div>

                    <div class="obrashcheniya__content_left_top_data">
                        дата: 20.09.2019
                    </div>

                    <div class="obrashcheniya__content_left_top_link">
                        <a href="#">Редактировать</a>

                        <a href="#">удалить</a>
                    </div>
                </div>

                <!-- Внутри контента центральная часть -->
                <div class="obrashcheniya__content_left_center">

                    <!-- Item ы -->
                    <div class="obrashcheniya__content_left_center_item">
                        <div class="obrashcheniya__content_left_center_item_text">
                            Фио
                        </div>

                        <p class="obrashcheniya__content_left_center_item_text-full">
                            Феофаний Николай Николаевич
                        </p>
                    </div>

                    <!-- Item ы -->
                    <div class="obrashcheniya__content_left_center_item">
                        <div class="obrashcheniya__content_left_center_item_text">
                            Больница:
                        </div>

                        <p class="obrashcheniya__content_left_center_item_text-full">
                            ГОСУДАРСТВЕННОЕ БЮДЖЕТНОЕ УЧРЕЖДЕНИЕ ЗДРАВООХРАНЕНИЯ РЕСПУБЛИКИ АДЫГЕЯ
                            АДЫГЕЙСКАЯ РЕСПУБЛИКАНСКАЯ КЛИНИЧЕСКАЯ БОЛЬНИЦА
                        </p>
                    </div>

                    <!-- Item ы -->
                    <div class="obrashcheniya__content_left_center_item">
                        <div class="obrashcheniya__content_left_center_item_text">
                            Адрес:
                        </div>

                        <p class="obrashcheniya__content_left_center__item_text-full">
                            385000, РЕСПУБЛИКА АДЫГЕЯ, Г.МАЙКОП, УЛ.ЖУКОВСКОГО, 4
                        </p>
                    </div>

                </div>
            </div>

            <!-- Контент правая сторона -->
            <div class="obrashcheniya__content_sidebar">
                <div class="obrashcheniya__content_sidebar_title">
                    Прикрепленные файлы
                </div>

                <!-- Item Sidebar -->
                <div class="obrashcheniya__content_sidebar_blocks">

                    <div class="obrashcheniya__content_sidebar_blocks_img">
                        <img src="./local/templates/kdteam/images/svg/image_block_two.svg" alt="">
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

                <div class="obrashcheniya__content_sidebar_blocks">

                    <div class="obrashcheniya__content_sidebar_blocks_img">
                        <img src="./local/templates/kdteam/images/svg/image_block_two.svg" alt="">
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

<!-- Обращение -->
<div class="obrashcheniya">

    <div class="obrashcheniya__btns">
        <a class="smallMainBtn" href="#">Прикрепить скан или фото</a>

        <a class="smallMainBtn" href="#">Отправить</a>
    </div>

    <!-- Уведомления об отправке -->
    <p class="danger">Обращение не может быть отправлено так как Вы не прикрепили файлы скана чека или фото
    </p>

    <div class="card">
        <!-- Контент -->
        <div class="obrashcheniya__content">
            <!-- Контент левая сторона -->
            <div class="obrashcheniya__content_left">

                <!-- Внутри контента Верхняя часть -->
                <div class="obrashcheniya__content_left_top">
                    <div class="obrashcheniya__content_left_top_text">
                        Обращение № 1
                    </div>

                    <div class="obrashcheniya__content_left_top_data">
                        дата: 20.09.2019
                    </div>

                    <div class="obrashcheniya__content_left_top_link">
                        <a href="#">Редактировать</a>

                        <a href="#">удалить</a>
                    </div>
                </div>

                <!-- Внутри контента центральная часть -->
                <div class="obrashcheniya__content_left_center">

                    <!-- Item ы -->
                    <div class="obrashcheniya__content_left_center_item">
                        <div class="obrashcheniya__content_left_center_item_text">
                            Фио
                        </div>

                        <p class="obrashcheniya__content_left_center_item_text-full">
                            Феофаний Николай Николаевич
                        </p>
                    </div>

                    <!-- Item ы -->
                    <div class="obrashcheniya__content_left_center_item">
                        <div class="obrashcheniya__content_left_center_item_text">
                            Больница:
                        </div>

                        <p class="obrashcheniya__content_left_center_item_text-full">
                            ГОСУДАРСТВЕННОЕ БЮДЖЕТНОЕ УЧРЕЖДЕНИЕ ЗДРАВООХРАНЕНИЯ РЕСПУБЛИКИ АДЫГЕЯ
                            АДЫГЕЙСКАЯ РЕСПУБЛИКАНСКАЯ КЛИНИЧЕСКАЯ БОЛЬНИЦА
                        </p>
                    </div>

                    <!-- Item ы -->
                    <div class="obrashcheniya__content_left_center_item">
                        <div class="obrashcheniya__content_left_center_item_text">
                            Адрес:
                        </div>

                        <p class="obrashcheniya__content_left_center__item_text-full">
                            385000, РЕСПУБЛИКА АДЫГЕЯ, Г.МАЙКОП, УЛ.ЖУКОВСКОГО, 4
                        </p>
                    </div>

                </div>
            </div>

            <!-- Контент правая сторона -->
            <div class="obrashcheniya__content_sidebar">
                <div class="obrashcheniya__content_sidebar_title">
                    Прикрепленные файлы
                </div>

                <!-- Item Sidebar -->
                <div class="obrashcheniya__content_sidebar_blocks">

                    <div class="obrashcheniya__content_sidebar_blocks_img">
                        <img src="./local/templates/kdteam/images/svg/image_block_two.svg" alt="">
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

                <div class="obrashcheniya__content_sidebar_blocks">

                    <div class="obrashcheniya__content_sidebar_blocks_img">
                        <img src="./local/templates/kdteam/images/svg/image_block_two.svg" alt="">
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

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>

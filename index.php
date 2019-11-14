<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;
$asset = Asset::getInstance();
//Home Page Style Mobile
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/home/home.min.css");
$APPLICATION->SetPageProperty("title", "OMS");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
?>
    <!-- Slider -->
    <div class="slider">
        <div class="slider__l">
            <picture>
                <source srcset="./local/templates/kdteam/images/jpg/home/slider/slider_img.jpg"
                        class="slider__l_img">
                <source srcset="./local/templates/kdteam/images/jpg/home/slider/slider_img.jpg"
                        class="slider__l_img" type="image/webp">
                <img src="./local/templates/kdteam/images/jpg/home/slider/slider_img.jpg" class="slider__l_img"
                     alt="OMS">
            </picture>
        </div>

        <div class="slider__r">
            <div class="slider__r_title">
                OMS заголовок для теста
            </div>

            <div class="slider__r_descr">
                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Quibusdam architecto tempore itaque
                doloremque, porro, dolore distinctio consequatur iste, ipsum quo dolor! Cumque a obcaecati,
                aliquam rem est accusamus eligendi deserunt?
            </div>
        </div>
    </div>

    <!-- Block buttons links to pages -->
    <div class="buttons__items">
        <!-- Item -->
        <a href="/forma-obrashenija/" class="buttons__items_item">
            <img class="buttons__items_item_img"
                 src="./local/templates/kdteam/images/jpg/home/buttons_menu/item1/img.jpg" alt="">

            <h3 class="buttons__items_item_title">
                Страховой случай в ОМС
            </h3>
        </a>

        <!-- Item -->
        <a href="/feedback/" class="buttons__items_item">
            <img class="buttons__items_item_img"
                 src="./local/templates/kdteam/images/jpg/home/buttons_menu/item2/img.jpg" alt="">

            <h3 class="buttons__items_item_title">
                Отзывы о представителях
            </h3>
        </a>

        <!-- Item -->
        <a href="#" class="buttons__items_item">
            <img class="buttons__items_item_img"
                 src="./local/templates/kdteam/images/jpg/home/buttons_menu/item3/img.jpg" alt="">

            <h3 class="buttons__items_item_title">
                Читать про ОМС
            </h3>
        </a>
    </div>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
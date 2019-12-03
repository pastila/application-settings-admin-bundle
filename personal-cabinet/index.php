<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;
$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/personal-cabinet/personal-cabinet.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/personal-cabinet/personal-cabinet.min.js");
?>

<div class="personal_cabinet">
    <h1 class="page-title">Мои данные</h1>
   <div class="flex_personal">
       <div class="personal_data">
            <div class="flex_data">
                <div class="item_data">
                    <p>Имя</p>
                </div>
                <div class="item_data">
                     <p>Иван</p>
                </div>
            </div>
            <div class="flex_data">
                <div class="item_data">
                    <p>Фамилия</p>
                </div>
                <div class="item_data">
                    <p>Иванов</p>
                </div>
            </div>
            <div class="flex_data">
                <div class="item_data">
                    <p>Отчество</p>
                </div>
                <div class="item_data">
                    <p>Иванович</p>
                </div>
            </div>
            <div class="flex_data">
                <div class="item_data">
                    <p>Ваш e-mail</p>
                </div>
                <div class="item_data">
                    <p>ivanov.ivan.ivanovich@mail.ru</p>
                </div>
            </div>
            <div class="flex_data">
                <div class="item_data">
                    <p>Ваш e-mail</p>
                </div>
                <div class="item_data">
                    <p>ivanov.ivan.ivanovich@mail.ru</p>
                </div>
            </div>
            <div class="flex_data">
                <div class="item_data">
                    <p>Ваш e-mail</p>
                </div>
                <div class="item_data">
                    <p>ivanov.ivan.ivanovich@mail.ru</p>
                </div>
            </div>
       </div>
       <div class="edit_block">
           <a href="#" class="mainBtn">Редактировать данные</a>
           <div class="photo_user">
               <img src="https://ru.seaicons.com/wp-content/uploads/2015/07/user-icon1.png" alt="">
           </div>
       </div>
   </div>
</div>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");?>
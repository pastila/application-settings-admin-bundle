<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Page\Asset;

$asset = Asset::getInstance();
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/style/main.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/js/main.min.js");
$asset->addCss(SITE_TEMPLATE_PATH . "/pages/feedback/add/main.min.css");
$asset->addJs(SITE_TEMPLATE_PATH . "/pages/feedback/add/main.min.js");


CModule::IncludeModule("iblock");
global $USER;?>

    <div class="wrap">



        <!-- Main content -->
        <main class="main">

            <!-- Breadcrumbs -->
            <ul class="breadcrumbs">
                <li>Главная</li>
                <li>Отзывы</li>
            </ul>

            <!-- Pages Title -->
            <?if($USER->IsAuthorized()){?>
                <h2 class="page-title">Добавить отзыв о компании</h2>

                <!-- Wrap -->
                <form id="form-comments">
                    <div class="white_block">

                        <div class="feedback__top">

                            <label class="title-select" for="user_pass">Выбор региона: </label>
                            <div class="input__wrap">
                                <div class="input__ico">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                                </div>
                                <input id="referal" type="text" placeholder="Поиск по региону" autocomplete="off"/>
                                <ul style="cursor: pointer;" class="custom-serach__items" id="search_result">
                                    <?
                                    $arOrder = Array("name"=>"asc");
                                    $arFilter = Array("IBLOCK_ID"=>9);
                                    $res = CIBlockSection::GetList($arOrder, $arFilter, false );
                                    while($ob = $res->GetNext()){

                                        ?>
                                        <li value="<?=$ob["ID"]?>" class="custom-serach__items_item region " data-id-city="<?=$ob["ID"]?>"><?=$ob["NAME"]?></li>

                                    <?  }?>
                                </ul>
                            </div>
                            <label class="title-select" for="user_pass">Список больниц : </label>
                            <div class="input__wrap">
                                <div class="input__ico">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                                </div>
                                <input id="referal_two" type="text" placeholder="Поиск по региону" autocomplete="off"/>
                                <ul style="cursor: pointer;" class="custom-serach__items" id="search_result_hospital">

                                </ul>
                            </div>
                        </div>

                        <div class="feedback__title">
                            Расскажите о вашей ситуации
                        </div>

                        <div class="input__wrap">
                            <label for="name" class="input__wrap_label">Заголовок коротко</label>
                            <input id="name" type="name" name="name" required="">
                        </div>

                        <div class="input__wrap">
                            <label for="name" class="input__wrap_label">Описание</label>
                            <textarea rows="10" name="description" id="description" required></textarea>
                        </div>

                        <div class="feedback__bottom">
                            <div class="feedback__bottom_star">
                                <!-- Rating Stars Box -->
                                <div class='rating-stars text-center' data-select="star">
                                    <ul id='stars'>
                                        <li class='star' title='Poor' data-value='1'>
                                            <i class='fa fa-star fa-fw'></i>
                                        </li>
                                        <li class='star' title='Fair' data-value='2'>
                                            <i class='fa fa-star fa-fw'></i>
                                        </li>
                                        <li class='star' title='Good' data-value='3'>
                                            <i class='fa fa-star fa-fw'></i>
                                        </li>
                                        <li class='star' title='Excellent' data-value='4'>
                                            <i class='fa fa-star fa-fw'></i>
                                        </li>
                                        <li class='star' title='WOW!!!' data-value='5'>
                                            <i class='fa fa-star fa-fw'></i>
                                        </li>
                                    </ul>
                                </div>

                                <div class="danger" style="display: none" >Дайте оценку компании</div>

                                <div class='success-box'>
                                    <div class='text-message'></div>
                                    <div class='clearfix'></div>
                                </div>
                            </div>

                            <div class="feedback__bottom_r">
                                <!-- Checkbox -->
                                <div class="wrap-chrckbox">
                                    <!--                                <label class="check-label">-->
                                    <!--                                    Согласен с правилами публикации отзывов на сайте-->
                                    <!--                                    <input type="checkbox" value="" />-->
                                    <!--                                    <span class="check-img"></span>-->
                                </div>

                                <button type="submit" class="smallMainBtn" id="submit">Отправить</button>
                            </div>
                        </div>
                </form>

            <? }else{?>
                <div>Отзывы можно оставить только авторезированным пользователям</div>
         <?   }?>
        </main>



    </div><!-- END Wrap -->



<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
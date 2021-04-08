<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!is_array($arResult["arMap"]) || count($arResult["arMap"]) < 1)
	return;

$arRootNode = Array();
foreach($arResult["arMap"] as $index => $arItem)
{
	if ($arItem["LEVEL"] == 0)
		$arRootNode[] = $index;
}

$allNum = count($arRootNode);
$colNum = ceil($allNum / $arParams["COL_NUM"]);

//title
$APPLICATION->SetTitle("404 страница, сожалеем, по Вашему запросу ничего не найдено.");

?>
<div class="page-404">
    <h3 class="page-404_sub-title">Ошибка 404</h3>
    <h1 class="page-404_title">Страница не найдена!</h1>

    <p class="page-404_text">Неправильно набран адрес или такой страницы на сайте больше не существует.</p>
    <p class="page-404_text">Вы можете перейти на <a class="page-404_link" href="/">главную страницу </a>.</p>
</div>
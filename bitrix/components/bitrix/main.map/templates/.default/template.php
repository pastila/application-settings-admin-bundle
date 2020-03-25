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
    <div class="page-404_half-text">
        <h3 class="page-404_sub-title">Ощибка 404</h3>
        <h1 class="page-404_title">Страница не найдена!</h1>

        <p class="page-404_text">Неправильно набран адрес или такой страницы на сайте больше не существует.</p>
        <p class="page-404_text">Вы можете перейти на <a class="page-404_link" href="/">главную страницу </a>.</p>
    </div>
    <div class="page-404_half-image">
        <svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 564 564">
            <g id="surface1">
                <path d="M 9.398438 535.800781 C 9.398438 546.183594 17.816406 554.601562 28.199219 554.601562 L 535.800781 554.601562 C 546.183594 554.601562 554.601562 546.183594 554.601562 535.800781 L 554.601562 65.800781 L 9.398438 65.800781 Z M 394.800781 319.601562 L 470 319.601562 Z M 319.601562 235 L 319.601562 385.398438 C 319.601562 395.78125 311.183594 404.199219 300.800781 404.199219 L 263.199219 404.199219 C 252.816406 404.199219 244.398438 395.78125 244.398438 385.398438 L 244.398438 235 C 244.398438 224.617188 252.816406 216.199219 263.199219 216.199219 L 300.800781 216.199219 C 311.183594 216.199219 319.601562 224.617188 319.601562 235 Z M 94 319.601562 L 169.199219 319.601562 Z M 94 319.601562 " style="stroke:none;fill-rule:nonzero;fill: #00abd8;fill-opacity:1;"></path>
                <path d="M 535.800781 9.398438 L 28.199219 9.398438 C 17.816406 9.398438 9.398438 17.816406 9.398438 28.199219 L 9.398438 65.800781 L 554.601562 65.800781 L 554.601562 28.199219 C 554.601562 17.816406 546.183594 9.398438 535.800781 9.398438 Z M 535.800781 9.398438 " style="stroke:none;fill-rule:nonzero;fill: #00abd8;fill-opacity:1;"></path>
                <path d="M 263.199219 216.199219 L 300.800781 216.199219 C 311.183594 216.199219 319.601562 224.617188 319.601562 235 L 319.601562 385.398438 C 319.601562 395.78125 311.183594 404.199219 300.800781 404.199219 L 263.199219 404.199219 C 252.816406 404.199219 244.398438 395.78125 244.398438 385.398438 L 244.398438 235 C 244.398438 224.617188 252.816406 216.199219 263.199219 216.199219 Z M 263.199219 216.199219 " style="stroke:none;fill-rule:nonzero;fill: #00abd8;fill-opacity:1;"></path>
                <path d="M 535.800781 0 L 28.199219 0 C 12.625 0 0 12.625 0 28.199219 L 0 535.800781 C 0 551.375 12.625 564 28.199219 564 L 535.800781 564 C 551.375 564 564 551.375 564 535.800781 L 564 28.199219 C 564 12.625 551.375 0 535.800781 0 Z M 28.199219 18.800781 L 535.800781 18.800781 C 540.992188 18.800781 545.199219 23.007812 545.199219 28.199219 L 545.199219 56.398438 L 18.800781 56.398438 L 18.800781 28.199219 C 18.800781 23.007812 23.007812 18.800781 28.199219 18.800781 Z M 535.800781 545.199219 L 28.199219 545.199219 C 23.007812 545.199219 18.800781 540.992188 18.800781 535.800781 L 18.800781 75.199219 L 545.199219 75.199219 L 545.199219 535.800781 C 545.199219 540.992188 540.992188 545.199219 535.800781 545.199219 Z M 535.800781 545.199219 " style="stroke:none;fill-rule:nonzero;fill: #1b3954;fill-opacity:1;"></path>
                <path d="M 37.601562 28.199219 L 56.398438 28.199219 L 56.398438 47 L 37.601562 47 Z M 37.601562 28.199219 " style="stroke:none;fill-rule:nonzero;fill: #fff;fill-opacity:1;"></path>
                <path d="M 84.601562 28.199219 L 103.398438 28.199219 L 103.398438 47 L 84.601562 47 Z M 84.601562 28.199219 " style="stroke:none;fill-rule:nonzero;fill: #fff;fill-opacity:1;"></path>
                <path d="M 131.601562 28.199219 L 150.398438 28.199219 L 150.398438 47 L 131.601562 47 Z M 131.601562 28.199219 " style="stroke:none;fill-rule:nonzero;fill: #fff;fill-opacity:1;"></path>
                <path d="M 84.601562 206.800781 L 84.601562 319.601562 C 84.601562 324.789062 88.808594 329 94 329 L 159.800781 329 L 159.800781 413.601562 L 178.601562 413.601562 L 178.601562 329 L 197.398438 329 L 197.398438 310.199219 L 178.601562 310.199219 L 178.601562 206.800781 L 159.800781 206.800781 L 159.800781 310.199219 L 103.398438 310.199219 L 103.398438 206.800781 Z M 84.601562 206.800781 " style="stroke:none;fill-rule:nonzero;fill: #fff;fill-opacity:1;"></path>
                <path d="M 479.398438 206.800781 L 460.601562 206.800781 L 460.601562 310.199219 L 404.199219 310.199219 L 404.199219 206.800781 L 385.398438 206.800781 L 385.398438 319.601562 C 385.398438 324.789062 389.609375 329 394.800781 329 L 460.601562 329 L 460.601562 413.601562 L 479.398438 413.601562 L 479.398438 329 L 498.199219 329 L 498.199219 310.199219 L 479.398438 310.199219 Z M 479.398438 206.800781 " style="stroke:none;fill-rule:nonzero;fill: #fff;fill-opacity:1;"></path>
                <path d="M 300.800781 413.601562 C 316.375 413.601562 329 400.972656 329 385.398438 L 329 235 C 329 219.425781 316.375 206.800781 300.800781 206.800781 L 263.199219 206.800781 C 247.625 206.800781 235 219.425781 235 235 L 235 385.398438 C 235 400.972656 247.625 413.601562 263.199219 413.601562 Z M 253.800781 385.398438 L 253.800781 235 C 253.800781 229.808594 258.007812 225.601562 263.199219 225.601562 L 300.800781 225.601562 C 305.992188 225.601562 310.199219 229.808594 310.199219 235 L 310.199219 385.398438 C 310.199219 390.589844 305.992188 394.800781 300.800781 394.800781 L 263.199219 394.800781 C 258.007812 394.800781 253.800781 390.589844 253.800781 385.398438 Z M 253.800781 385.398438 " style="stroke:none;fill-rule:nonzero;fill: #fff;fill-opacity:1;"></path>
                <path d="M 37.601562 122.199219 L 56.398438 122.199219 L 56.398438 141 L 37.601562 141 Z M 37.601562 122.199219 " style="stroke:none;fill-rule:nonzero;fill: #fff;fill-opacity:1;"></path>
                <path d="M 507.601562 122.199219 L 526.398438 122.199219 L 526.398438 141 L 507.601562 141 Z M 507.601562 122.199219 " style="stroke:none;fill-rule:nonzero;fill: #fff;fill-opacity:1;"></path>
                <path d="M 65.800781 122.199219 L 498.199219 122.199219 L 498.199219 141 L 65.800781 141 Z M 65.800781 122.199219 " style="stroke:none;fill-rule:nonzero;fill: #fff;fill-opacity:1;"></path>
                <path d="M 37.601562 479.398438 L 56.398438 479.398438 L 56.398438 498.199219 L 37.601562 498.199219 Z M 37.601562 479.398438 " style="stroke:none;fill-rule:nonzero;fill: #fff;fill-opacity:1;"></path>
                <path d="M 507.601562 479.398438 L 526.398438 479.398438 L 526.398438 498.199219 L 507.601562 498.199219 Z M 507.601562 479.398438 " style="stroke:none;fill-rule:nonzero;fill: #fff;fill-opacity:1;"></path>
                <path d="M 65.800781 479.398438 L 498.199219 479.398438 L 498.199219 498.199219 L 65.800781 498.199219 Z M 65.800781 479.398438 " style="stroke:none;fill-rule:nonzero;fill: #fff;fill-opacity:1;"></path>
            </g>
        </svg>
    </div>
</div>
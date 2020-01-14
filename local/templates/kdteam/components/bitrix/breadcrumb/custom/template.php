<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @global CMain $APPLICATION
 */

global $APPLICATION;
$detect = new Mobile_Detect();
//delayed function must return a string
if (empty($arResult)) {
    return "";
}

$strReturn = '';

//we can't use $APPLICATION->SetAdditionalCSS() here because we are inside the buffered function GetNavChain()

$strReturn .= '<ul class="breadcrumbs">';

$itemSize = count($arResult);
$itemSize_for_Microdata = (int)0;
for ($index = 0; $index < $itemSize; $index++) {
    $title = htmlspecialcharsex($arResult[$index]["TITLE"]);

     if ($detect->isTablet() || $detect->isMobile()) {
         if ($arResult[$index]["LINK"] <> "" && $index != $itemSize - 1) {

         } else {
             $strReturn .= '55
        
			<li>
				<a itemprop="item"><span itemprop="name" class="">'.$title.'</span></a>

				<meta itemprop="position" content="'.$itemSize.'" />
			</li>';
         }
        } else {
         if ($arResult[$index]["LINK"] <> "" && $index != $itemSize - 1) {
             $itemSize_for_Microdata += 1;

             $strReturn .= '
			<li>
				<a  itemprop="item" href="' . $arResult[$index]["LINK"] . '" title="' . $title . '" itemprop="url">
					<span itemprop="name">'.$title.'</span>
				</a>
				<meta itemprop="position" content="'.$itemSize_for_Microdata.'" />
			</li>';
         } else {
             $strReturn .= '
        
			<li>
				<a itemprop="item"><span itemprop="name">'.$title.'</span></a>

				<meta itemprop="position" content="'.$itemSize.'" />
			</li>';
         }

         }


}

$strReturn .= '</ul>';

return $strReturn;

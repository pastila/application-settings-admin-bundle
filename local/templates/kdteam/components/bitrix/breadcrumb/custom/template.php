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

$strReturn .= '<ul class="breadcrumbs" itemscope="" itemtype="http://schema.org/BreadcrumbList">';

$itemSize = count($arResult);
$itemSize_for_Microdata = (int)1;
for ($index = 0; $index < $itemSize; $index++) {
    $title = htmlspecialcharsex($arResult[$index]["TITLE"]);

    if ($detect->isTablet() || $detect->isMobile()) {

        if ($arResult[$index]["LINK"] <> "" && $index != $itemSize - 1) {

        } else {
            if(count($arResult) == 2){
                $strReturn .= '
        
			<li class="active-breadcrumbs" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
				<a itemprop="item" href="/"> '.$title.'
				    <meta itemprop="name" content="'.$title.'">
                    <meta property="position" content="'.$itemSize_for_Microdata.'">
				</a>
			</li>';
            }else{
                $strReturn .= '
        
			<li class="active-breadcrumbs" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
				<a itemprop="item" href="/news/">
				    <span itemprop="name" class="active-breadcrumbs">'.$title.'</span>
                    <meta property="position" content="'.$itemSize_for_Microdata.'">				
                </a>
			</li>';
            }

        }
    } else {
        if ($arResult[$index]["LINK"] <> "" && $index != $itemSize - 1) {
            $itemSize_for_Microdata += 1;

            $strReturn .= '
			<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
				<a  itemprop="item" href="' . $arResult[$index]["LINK"] . '" title="' . $title . '" itemprop="url">
					<span itemprop="name">'.$title.'</span>
                    <meta property="position" content="'.$itemSize_for_Microdata.'">  
				</a>
				
			</li>';
        } else {
            $strReturn .= '
        
			<li class="active-breadcrumbs" itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
				<a itemprop="item"> '.$title.'
				    <meta itemprop="name" content="'.$title.'">
                    <meta property="position" content="'.$itemSize_for_Microdata.'">
				</a>
			</li>';
        }

    }


}

$strReturn .= '</ul>';

return $strReturn;

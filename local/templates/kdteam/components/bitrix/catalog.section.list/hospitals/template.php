<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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


CModule::IncludeModule("iblock");
$arSelect = Array("ID", "IBLOCK_ID", "NAME","CODE", "DATE_ACTIVE_FROM","PROPERTY_*");
$arFilter = Array("IBLOCK_ID"=>19 ,"CODE"=>"shag");
$arResult_block= array();
$res = CIBlockElement::GetList(Array(Array("SORT"=>"asc")), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement()){
    $arProps = $ob->GetProperties();


        $STEP_3_TITLE = $arProps["STEP_3_TITLE"]["VALUE"];



}

$arViewModeList = $arResult['VIEW_MODE_LIST'];

$arViewStyles = array(
	'LIST' => array(
		'CONT' => 'bx_sitemap',
		'TITLE' => 'bx_sitemap_title',
		'LIST' => 'bx_sitemap_ul',
	),
	'LINE' => array(
		'CONT' => 'bx_catalog_line',
		'TITLE' => 'bx_catalog_line_category_title',
		'LIST' => 'bx_catalog_line_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/line-empty.png'
	),
	'TEXT' => array(
		'CONT' => 'bx_catalog_text',
		'TITLE' => 'bx_catalog_text_category_title',
		'LIST' => 'bx_catalog_text_ul'
	),
	'TILE' => array(
		'CONT' => 'bx_catalog_tile',
		'TITLE' => 'bx_catalog_tile_category_title',
		'LIST' => 'bx_catalog_tile_ul',
		'EMPTY_IMG' => $this->GetFolder().'/images/tile-empty.png'
	)
);
$arCurView = $arViewStyles[$arParams['VIEW_MODE']];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));


//$arCODE_region =array();
//$arsSections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => 9), false,
//    array("UF_CODE_REGION"));
//while ($arSection = $arsSections->GetNext()) {
//
//    $arCODE_region[] = [$arSection["ID"] => $arSection["UF_CODE_REGION"]];
//}
//$arItems = array();
//foreach ($arResult['SECTIONS'] as $item) {
//    foreach ($arCODE_region as $item_code) {
//        foreach ($item_code as $key_1 => $item_code1) {
//            if ($key_1 == $item["ID"]) {
//                $item["UF_CODE_REGION"] = $item_code1;
//                $arItems []= $item;
//            }
//        }
//    }
//}
//
//$arResult["SECTIONS"] = $arItems;

?>
<?php
	switch ($arParams['VIEW_MODE'])
	{
		case 'LINE':
			foreach ($arResult['SECTIONS'] as &$arSection)
			{
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

				if (false === $arSection['PICTURE'])
					$arSection['PICTURE'] = array(
						'SRC' => $arCurView['EMPTY_IMG'],
						'ALT' => (
							'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
							? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
							: $arSection["NAME"]
						),
						'TITLE' => (
							'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
							? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
							: $arSection["NAME"]
						)
					);
				?><li id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
				<a
					href="<? echo $arSection['SECTION_PAGE_URL']; ?>"
					class="bx_catalog_line_img"
					style="background-image: url('<? echo $arSection['PICTURE']['SRC']; ?>');"
					title="<? echo $arSection['PICTURE']['TITLE']; ?>"
				></a>
				<h2 class="bx_catalog_line_title"><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
				if ($arParams["COUNT_ELEMENTS"])
				{
					?> <span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span><?
				}
				?></h2><?
				if ('' != $arSection['DESCRIPTION'])
				{
					?><p class="bx_catalog_line_description"><? echo $arSection['DESCRIPTION']; ?></p><?
				}
				?><div style="clear: both;"></div>
				</li><?
			}
			unset($arSection);
			break;
		case 'TEXT':
			foreach ($arResult['SECTIONS'] as &$arSection)
			{
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

				?><li id="<? echo $this->GetEditAreaId($arSection['ID']); ?>"><h2 class="bx_catalog_text_title"><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
				if ($arParams["COUNT_ELEMENTS"])
				{
					?> <span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span><?
				}
				?></h2></li><?
			}
			unset($arSection);
			break;
		case 'TILE':
			foreach ($arResult['SECTIONS'] as &$arSection)
			{
				$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
				$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

				if (false === $arSection['PICTURE'])
					$arSection['PICTURE'] = array(
						'SRC' => $arCurView['EMPTY_IMG'],
						'ALT' => (
							'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
							? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"]
							: $arSection["NAME"]
						),
						'TITLE' => (
							'' != $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
							? $arSection["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"]
							: $arSection["NAME"]
						)
					);
				?><li id="<? echo $this->GetEditAreaId($arSection['ID']); ?>">
				<a
					href="<? echo $arSection['SECTION_PAGE_URL']; ?>"
					class="bx_catalog_tile_img"
					style="background-image:url('<? echo $arSection['PICTURE']['SRC']; ?>');"
					title="<? echo $arSection['PICTURE']['TITLE']; ?>"
					> </a><?
				if ('Y' != $arParams['HIDE_SECTION_NAME'])
				{
					?><h2 class="bx_catalog_tile_title"><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
					if ($arParams["COUNT_ELEMENTS"])
					{
						?> <span>(<? echo $arSection['ELEMENT_CNT']; ?>)</span><?
					}
				?></h2><?
				}
				?></li><?
			}
			unset($arSection);
			break;
		case 'LIST':?>
                <?php if($STEP_3_TITLE !=""){ ?>
            <p class="form-obrashcheniya__step_three_l_text"><?php echo $STEP_3_TITLE;?></p>
                <?php } ?>
        <?php

        if ($arResult['SECTION']['ID'] > 0) { ?>
            <?php
            if ($arResult['SECTION']['DEPTH_LEVEL'] == 1) {
                ?>
                <div class="input-with-search">

                    <div class="input__wrap">
                        <div class="input__ico">
                            <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                        </div>
                        <input id="referal_forma" type="text" data-region_check="check" data-id_region="<?=$arResult['SECTION']['ID']?>" placeholder="Поиск по региону" value="<?php echo $arResult['SECTION']['NAME']?>" autocomplete="off"/>
                        <ul style="cursor: pointer;" class="custom-serach__items" id="search_result">
                <?php
                $db_list = CIBlockSection::GetList(
                    array("name" => "asc"),
                    array('IBLOCK_ID' => 9, "GLOBAL_ACTIVE" => "Y", "DEPTH_LEVEL" => 1,
                        "!ID" => $arResult['SECTION']['ID']),
                    false,
                    array('ID','NAME'),
                    false
                );
                while ($ar_result = $db_list->GetNext()) { ?>

                                    <li value="<?=$ar_result['ID']?>" class="custom-serach__items_item region"><?php echo  $ar_result['NAME']?></li>
                                <?php }?>

                        </ul>
                    </div>
                </div>



                <?php
                $arFields = array();
                $arFilter = array("IBLOCK_ID" => 9, "ACTIVE"=>"Y", "IBLOCK_SECTION_ID" => $arResult['SECTION']['ID'],"PROPERTY_YEAR"=> $arParams["YEAR"]);
                $arSelect = Array("ID", "NAME", "IBLOCK_ID","PROPERTY_SECOND_NAME","PROPERTY_LOCATION","PROPERTY_PERSON_NAME","PROPERTY_MIDDLE_NAME","PROPERTY_FULL_NAME");
                $res = CIBlockElement::GetList(array("name" => "asc"), $arFilter, false, false, $arSelect);
                while($ob = $res->GetNextElement())
                {
                    $arFields[] = $ob->GetFields();

                }
                ?>
                <div class="input-with-search">

                    <div class="input__wrap">
                        <div class="input__ico">
                            <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                        </div>
                        <input id="referal_two_forma" type="text" placeholder="Выберите медицинскую организацию" autocomplete="off"/>
                        <ul style="cursor: pointer;" class="custom-serach__items" id="search_result_hospital">
                            <?


                                    if(isset($arFields[0]) != 0){?>
                                        <li value="" id="hospital" class="custom-serach__items_item hospital-empty">Здесь нет моей больницы</li>
                        <?    foreach ($arFields as &$arItem) {?>


                                    <li value="<?=$arItem['ID']?>" class="custom-serach__items_item  hospital" data-name-boss="<?=$arItem["PROPERTY_SECOND_NAME_VALUE"] ." ". $arItem["PROPERTY_PERSON_NAME_VALUE"] ." ". $arItem["PROPERTY_MIDDLE_NAME_VALUE"]?>" data-street="<?=$arItem["PROPERTY_LOCATION_VALUE"]?>"><?php echo $arItem['PROPERTY_FULL_NAME_VALUE']?></li>
                                <?php }?>

                            <?php
                                    }else{?>
                                        <li value="" id="hospital" class="custom-serach__items_item hospital-empty">Нет медицинских организаций, работающих по ОМС</li>
                                        <?php } ?>
                        </ul>
                    </div>
                </div>


                <?php
            }?>
        <?php } else { ?>

            <div class="input-with-search">
                <!-- <label class="title-select" for="user_pass">Выбор региона: </label> -->
                <div class="input__wrap">
                    <div class="input__ico">
                        <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                    </div>
                    <input id="referal_forma" type="text" placeholder="Выберите регион" autocomplete="off"/>
                    <ul style="cursor: pointer;" class="custom-serach__items" id="search_result">
                        <?php
                        foreach ($arResult['SECTIONS'] as &$arSection) {

                        ?>
                            <?php if ($arSection['DEPTH_LEVEL'] == 1) { ?>
                                <li value="<?=$arSection["ID"]?>" class="custom-serach__items_item region "><?php echo  $arSection['NAME']?></li>
                            <?php }?>
                        <?php } ?>
                    </ul>
                </div>
            </div>

            <div class="input-with-search">
                <!-- <label class="title-select" for="user_pass">Список больниц: </label> -->
                <div class="input__wrap" style="pointer-events: none">
                    <div class="input__ico">

                    </div>
                    <input id="" type="text" placeholder="Выберите медицинскую организацию" autocomplete="off"/>
                    <ul style="cursor: pointer;" class="custom-serach__items" id="">



                    </ul>
                </div>
            </div>

        <?php }
        break;
    }
?>

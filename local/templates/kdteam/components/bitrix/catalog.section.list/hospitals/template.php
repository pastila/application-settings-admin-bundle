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
$arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");
$arFilter = Array("IBLOCK_ID"=>19, );
$arResult_block= array();
$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while($ob = $res->GetNextElement()){
    $arProps = $ob->GetProperties();

    $arResult_block[]= $arProps["TEXT_FORMA"]["VALUE"];
    $arResult_block[]= $arProps["FIRST_TEXT"]["VALUE"];

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

            <p class="form-obrashcheniya__step_three_l_text"><?=$arResult_block[6]?></p>

            <a class="link-underline" href="#">Ссылка на статью в блоге</a>


        <?php
        if ($arResult['SECTION']['ID'] > 0) { ?>
            <?php
            if ($arResult['SECTION']['DEPTH_LEVEL'] == 1) {
                ?>
                <div class="grid-item">
                    <div class="title-select">Выбор региона:</div>
                    <div id="choose_location" class="custom-select">
                        <select>
                            <option value="<?=$arResult['SECTION']['ID']?>"><?php echo $arResult['SECTION']['NAME']?></option>
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
                                <option id="location" value="<?=$ar_result['ID']?>"><?php echo $ar_result['NAME']?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <?php
                $arFilter = array("IBLOCK_ID" => 9, "ACTIVE"=>"Y", "IBLOCK_SECTION_ID" => $arResult['SECTION']['ID']);
                $arSelect = Array("ID", "NAME", "IBLOCK_ID");
                $res = CIBlockElement::GetList(array("name" => "asc"), $arFilter, false, false, $arSelect);
                while($ob = $res->GetNextElement())
                {
                    $arFields[] = $ob->GetFields();
                }
                ?>
                <div class="title-select">Список больниц:</div>
                <div id="choose_hospital" class="custom-select">
                    <select>
                        <option>Не выбрано</option>

                        <?php
                        $i = 1;
                        foreach ($arFields as $arItem) {
                            if ($i == 1) {
                                ?>
                                <option id="hospital" value="">Здесь нет моей больницы</option>
                            <?php } else {?>
                                <option id="hospital" value="<?=$arItem['ID']?>"><?php echo $arItem['NAME']?></option>
                            <? }
                            ++$i;
                        }?>
                    </select>
                </div>
                <?php
            }?>
        <?php } else { ?>
            <div class="input-with-search">
                <label class="title-select" for="user_pass">Выбор региона: </label>
                <div class="input__wrap">
					<div class="input__ico">
						<svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
					</div>
                    <input id="referal" type="text" placeholder="Поиск по региону" onkeyup="smart_search()" autocomplete="off"/>
                    <ul style="cursor: pointer;" class="custom-serach__items" id="search_result">
                        <?php
                        foreach ($arResult['SECTIONS'] as &$arSection) {?>
                            <?php if ($arSection['DEPTH_LEVEL'] == 1) { ?>
                                <li class="custom-serach__items_item"><?php echo $arSection['NAME']?></li>
                            <?php }?>
                        <?php } ?>
                    </ul>
                </div>
            </div>
<!--            <div id="choose_location" class="custom-select">-->
<!--                <select>-->
<!--                    <option>Не выбрано</option>-->
<!--                    --><?php
//                    foreach ($arResult['SECTIONS'] as &$arSection) {?>
<!--                        --><?php //if ($arSection['DEPTH_LEVEL'] == 1) { ?>
<!--                            <option id="location" value="--><?//=$arSection['ID']?><!--">--><?php //echo $arSection['NAME']?><!--</option>-->
<!--                        --><?php //}?>
<!--                    --><?php //} ?>
<!--                </select>-->
<!--            </div>-->

            <div class="title-select">Список больниц:</div>
            <div id="choose_hospital" class="custom-select" style="pointer-events: none">
                <select>
                    <option>Список больниц</option>
                </select>
            </div>

        <?php }
        break;
    }
?>

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
		case 'LIST':
        if ($arResult['SECTION']['ID'] > 0) { ?>
            <?php
            if ($arResult['SECTION']['DEPTH_LEVEL'] == 1) {
                ?>
                <div class="grid-item">
                    <div class="title-select">Выберите класс</div>
                    <div class="custom-select">
                        <select>
                            <option><?php echo $arResult['SECTION']['NAME']?></option>
                            <?php
                            $db_list = CIBlockSection::GetList(
                                array("name" => "asc"),
                                array('IBLOCK_ID' => 8, "GLOBAL_ACTIVE" => "Y", "DEPTH_LEVEL" => 1,
                                    "!ID" => $arResult['SECTION']['ID']),
                                false,
                                array('ID','NAME'),
                                false
                            );
                            while ($ar_result = $db_list->GetNext()) { ?>
                                <option value="<?=$ar_result['ID']?>"><?php echo $ar_result['NAME']?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="title-select">Выберите группу</div>
                    <div class="custom-select">
                        <select>
                            <option>Не выбрано</option>
                            <?php
                            foreach ($arResult['SECTIONS'] as &$arSection) {?>
                                <?php if ($arSection['DEPTH_LEVEL'] == 2) { ?>
                                    <option value="<?=$arSection['ID']?>"><?php echo $arSection['NAME']?></option>
                                <?php }?>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="title-select">Выберите подгруппу</div>
                    <div class="custom-select" style="pointer-events: none">
                        <select>
                            <option>Подгруппа</option>
                        </select>
                    </div>
                </div>
            <?php
            } elseif ($arResult['SECTION']['DEPTH_LEVEL'] == 2) { ?>
                <?php
                $class = CIBlockSection::GetByID($arResult['SECTION']['IBLOCK_SECTION_ID']);
                if($ar_cur_class = $class->GetNext()) {
                    $db_list = CIBlockSection::GetList(
                        array("name" => "asc"),
                        array('IBLOCK_ID' => 8, "GLOBAL_ACTIVE" => "Y", "DEPTH_LEVEL" => 1,
                            "!ID" => $ar_cur_class['ID']),
                        false,
                        array('ID','NAME'),
                        false
                    );
                    $db_list_lvl2 = CIBlockSection::GetList(
                        array("name" => "asc"),
                        array('IBLOCK_ID' => 8, "GLOBAL_ACTIVE" => "Y", "DEPTH_LEVEL" => 2,
                            "SECTION_ID" => $ar_cur_class['ID']),
                        false,
                        array('ID','NAME'),
                        false
                    );
                    ?>
                    <div class="grid-item">
                        <div class="title-select">Выберите класс</div>
                        <div class="custom-select">
                            <select>
                                <option><?php echo $ar_cur_class['NAME']?></option>
                                <?php
                                while ($ar_result = $db_list->GetNext()) { ?>
                                    <option value="<?=$ar_result['ID']?>"><?php echo $ar_result['NAME']?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="title-select">Выберите группу</div>
                        <div class="custom-select">
                            <select>
                                <option><?php echo $arResult['SECTION']['NAME']?></option>
                                <?php
                                while ($ar_result = $db_list_lvl2->GetNext()) { ?>
                                    <option value="<?=$ar_result['ID']?>"><?php echo $ar_result['NAME']?></option>
                                    <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="grid-item">
                        <div class="title-select">Выберите подгруппу</div>
                        <div class="custom-select">
                            <select>
                                <option>Не выбрано</option>
                                <?php
                                foreach ($arResult['SECTIONS'] as &$arSection) {?>
                                    <?php if ($arSection['DEPTH_LEVEL'] == 3) { ?>
                                        <option value="<?=$arSection['ID']?>"><?php echo $arSection['NAME']?></option>
                                    <?php }?>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                <?php
                } ?>
            <?php
            } else if ($arResult['SECTION']['DEPTH_LEVEL'] == 3) { ?>
                <?php
                $rsGroup = CIBlockSection::GetByID($arResult['SECTION']['IBLOCK_SECTION_ID']);
                if($arGroup = $rsGroup->GetNext()) {
                    $rsClass = CIBlockSection::GetByID($arGroup['IBLOCK_SECTION_ID']);
                    if ($arClass = $rsClass->GetNext()) {
                        $db_list = CIBlockSection::GetList(
                            array("name" => "asc"),
                            array('IBLOCK_ID' => 8, "GLOBAL_ACTIVE" => "Y", "DEPTH_LEVEL" => 1,
                                "!ID" => $arClass['ID']),
                            false,
                            array('ID','NAME'),
                            false
                        );
                        $db_list_lvl2 = CIBlockSection::GetList(
                            array("name" => "asc"),
                            array('IBLOCK_ID' => 8, "GLOBAL_ACTIVE" => "Y", "DEPTH_LEVEL" => 2,
                                "SECTION_ID" => $arClass['ID']),
                            false,
                            array('ID','NAME'),
                            false
                        );
                        $db_list_lvl3 = CIBlockSection::GetList(
                            array("name" => "asc"),
                            array('IBLOCK_ID' => 8, "GLOBAL_ACTIVE" => "Y", "DEPTH_LEVEL" => 3,
                                "SECTION_ID" => $arGroup['ID']),
                            false,
                            array('ID','NAME'),
                            false
                        );
                        ?>
                        <div class="grid-item">
                            <div class="title-select">Выберите класс</div>
                            <div class="custom-select">
                                <select>
                                    <option><?php echo $arClass['NAME']?></option>
                                    <?php
                                    while ($ar_result = $db_list->GetNext()) { ?>
                                        <option value="<?=$ar_result['ID']?>"><?php echo $ar_result['NAME']?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="grid-item">
                            <div class="title-select">Выберите группу</div>
                            <div class="custom-select">
                                <select>
                                    <option><?php echo $arGroup['NAME']?></option>
                                    <?php
                                    while ($ar_result = $db_list_lvl2->GetNext()) { ?>
                                        <option value="<?=$ar_result['ID']?>"><?php echo $ar_result['NAME']?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="grid-item">
                            <div class="title-select">Выберите подгруппу</div>
                            <div class="custom-select">
                                <select>
                                    <option><?php echo $arResult['SECTION']['NAME']?></option>
                                    <?php
                                    while ($ar_result = $db_list_lvl3->GetNext()) { ?>
                                        <option value="<?=$ar_result['ID']?>"><?php echo $ar_result['NAME']?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    <?php
                    }
                } ?>
            <?php
            }

            if ($arResult['SECTION']['DEPTH_LEVEL'] == 3) {
                $arFilter = array("IBLOCK_ID" => 8, "ACTIVE"=>"Y", "IBLOCK_SECTION_ID" => $arResult['SECTION']['ID']);
                $arSelect = Array("ID", "NAME", "IBLOCK_ID");
                $res = CIBlockElement::GetList(array("name" => "asc"), $arFilter, false, false, $arSelect);
                while($ob = $res->GetNextElement())
                {
                    $arFields[] = $ob->GetFields();
                }
                ?>
                <div class="grid-item">
                    <div class="title-select">Выберите диагноз</div>
                    <div class="custom-select">
                        <select>
                            <option>Не выбрано</option>
                            <?php foreach ($arFields as $arItem) {?>
                                <option id="element" value="<?=$arItem['ID']?>"><?php echo $arItem['NAME']?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
            <?php
            } else { ?>
                <div class="grid-item">
                    <div class="title-select">Выберите диагноз</div>
                    <div class="custom-select" style="pointer-events: none">
                        <select disabled>
                            <option>Диагноз</option>
                        </select>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="grid-item">
                <div class="title-select">Выберите класс</div>
                <div class="custom-select">
                    <select id="cur_class">
                        <option>Не выбрано</option>
                        <?php
                        foreach ($arResult['SECTIONS'] as &$arSection) {?>
                            <?php if ($arSection['DEPTH_LEVEL'] == 1) { ?>
                                <option value="<?=$arSection['ID']?>"><?php echo $arSection['NAME']?></option>
                            <?php }?>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="grid-item">
                <div class="title-select">Выберите группу</div>
                <div class="custom-select" style="pointer-events: none">
                    <select>
                        <option>Группа</option>
                    </select>
                </div>
            </div>
            <div class="grid-item">
                <div class="title-select">Выберите подгруппу</div>
                <div class="custom-select" style="pointer-events: none">
                    <select>
                        <option>Подгруппа</option>
                    </select>
                </div>
            </div>
            <div class="grid-item">
                <div class="title-select">Выберите диагноз</div>
                <div class="custom-select" style="pointer-events: none">
                    <select>
                        <option>Диагноз</option>
                    </select>
                </div>
            </div>
        <?php }
        break;
    }
?>

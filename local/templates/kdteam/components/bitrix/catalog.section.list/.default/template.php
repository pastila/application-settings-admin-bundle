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
                <div class="input-with-search">
                    
                    <div class="input__wrap">
                        <div class="input__ico">
                            <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                        </div>
                        <input id="class_input" type="text" data-id_class="<?=$arResult['SECTION']['ID']?>"
                               placeholder="Выберите класс" value="<?php echo $arResult['SECTION']['NAME']?>"
                               autocomplete="off"/>
                        <ul style="cursor: pointer;" class="custom-serach__items" id="search_result_class">
                            <?php
                            $db_list = CIBlockSection::GetList(
                                array("name" => "asc"),
                                array('IBLOCK_ID' => 8, "GLOBAL_ACTIVE" => "Y", "DEPTH_LEVEL" => 1,
                                    "!ID" => $arResult['SECTION']['ID']),
                                false,
                                array('ID','NAME'),
                                false
                            );
                            $i = 1;
                            while ($ar_result = $db_list->GetNext()) {
                                if ($i == 1) {?>
                                    <li value="" id="empty_class" class="custom-serach__items_item ">Здесь нет моего класса</li>
                                <? } else {?>
                                    <li value="<?=$ar_result['ID']?>" class="custom-serach__items_item  class-js"><?php echo $ar_result['NAME']?></li>
                                    <?php
                                }
                                ++$i;
                            }
                            ?>
                        </ul>
                    </div>
                </div>
                <div class="input-with-search">
                    <div class="input__wrap">
                        <div class="input__ico">
                            <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                        </div>
                        <input id="group_input" type="text" placeholder="Выберите группу" autocomplete="off"/>
                        <ul style="cursor: pointer;" class="custom-serach__items" id="search_result_group">
                            <li value="" id="empty_group" class="custom-serach__items_item group-js">Здесь нет моей группы</li>
                            <?php
                            foreach ($arResult['SECTIONS'] as &$arSection) { ?>
                                <?php if ($arSection['DEPTH_LEVEL'] == 2) { ?>
                                        <li value="<?=$arSection['ID']?>" class="custom-serach__items_item  group-js"><?php echo $arSection['NAME']?></li>
                                        <?php
                                } ?>
                                <?php
                                ++$i;
                            } ?>
                        </ul>
                    </div>
                </div>
                <div style="pointer-events: none" class="input-with-search">
                    <div class="input__wrap">
                        <div class="input__ico">
                            <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                        </div>
                        <input  id="subgroup_input"  type="text" placeholder="Выберите подгруппу" autocomplete="off" value=""/>
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
                    <div class="input-with-search">
                        <div class="input__wrap">
                            <div class="input__ico">
                                <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                            </div>
                            <input id="class_input" type="text" data-id_class="<?=$ar_cur_class['ID']?>"
                                   placeholder="Выберите класс" value="<?php echo $ar_cur_class['NAME']?>"
                                   autocomplete="off"/>
                            <ul style="cursor: pointer;" class="custom-serach__items" id="search_result_class">
                                <?php
                                $i = 0;
                                while ($ar_result = $db_list->GetNext()) {
                                    if($i == 1){?>
                                        <li value="" id="empty_class" class="custom-serach__items_item ">Здесь нет моего класса</li>
                                    <?  }else {?>
                                        <li value="<?=$ar_result['ID']?>" class="custom-serach__items_item  class-js"><?php echo $ar_result['NAME']?></li>
                                        <?php
                                    }
                                    ++$i;
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="input-with-search">
                        <div class="input__wrap">
                            <div class="input__ico">
                                <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                            </div>
                            <input data-id_group="<?=$arResult['SECTION']['ID']?>" id="group_input"
                                   value="<?php echo $arResult['SECTION']['NAME']?>" type="text"
                                   placeholder="Выберите группу" autocomplete="off"/>
                            <ul style="cursor: pointer;" class="custom-serach__items" id="search_result_group">
                                <li value="" id="empty_group" class="custom-serach__items_item group-js">Здесь нет моей группы</li>
                                <?php
                                while ($ar_result = $db_list_lvl2->GetNext()) { ?>
                                    <li value="<?=$ar_result['ID']?>" class="custom-serach__items_item  group-js"><?php echo $ar_result['NAME']?></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div class="input-with-search">
                        <div class="input__wrap">
                            <div class="input__ico">
                                <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                            </div>
                            <input id="subgroup_input" type="text" placeholder="Выберите подгруппу" autocomplete="off"/>
                            <ul style="cursor: pointer;" class="custom-serach__items" id="search_result_subgroup">
                                <li value="" id="empty_subgroup" class="custom-serach__items_item subgroup-js">Здесь нет моей подгруппы</li>
                                <?php
                                foreach ($arResult['SECTIONS'] as &$arSection) { ?>
                                    <?php if ($arSection['DEPTH_LEVEL'] == 3) { ?>
                                        <li value="<?=$arSection['ID']?>" class="custom-serach__items_item  subgroup-js"><?php echo $arSection['NAME']?></li>
                                    <?php } ?>
                                    <?php
                                } ?>
                            </ul>
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


                        <div class="input-with-search">
                            <div class="input__wrap">
                                <div class="input__ico">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                                </div>
                                <input id="class_input" type="text" data-id_class="<?=$arClass['ID']?>"
                                       placeholder="Выберите класс" value="<?php echo $arClass['NAME']?>"
                                       autocomplete="off"/>
                                <ul style="cursor: pointer;" class="custom-serach__items" id="search_result_class">
                                    <li value="" id="empty_class" class="custom-serach__items_item ">Здесь нет моего класса</li>
                                    <?php
                                    while ($ar_result = $db_list->GetNext()) { ?>
                                        <li value="<?=$ar_result['ID']?>" class="custom-serach__items_item  class-js"><?php echo $ar_result['NAME']?></li>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <div class="input-with-search">
                            <div class="input__wrap">
                                <div class="input__ico">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                                </div>
                                <input data-id_group="<?=$arGroup['ID']?>" id="group_input"
                                       value="<?php echo $arGroup['NAME']?>" type="text"
                                       placeholder="Выберите группу" autocomplete="off"/>
                                <ul style="cursor: pointer;" class="custom-serach__items" id="search_result_group">
                                    <li value="" id="empty_group" class="custom-serach__items_item group-js">Здесь нет моей группы</li>
                                    <?php
                                    while ($ar_result = $db_list_lvl2->GetNext()) { ?>
                                        <li value="<?=$ar_result['ID']?>" class="custom-serach__items_item  group-js"><?php echo $ar_result['NAME']?></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <div class="input-with-search">
                            <div class="input__wrap">
                                <div class="input__ico">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                                </div>
                                <input data-id_subgroup="<?=$arResult['SECTION']['ID']?>" id="subgroup_input" type="text"
                                       placeholder="Выберите подгруппу" autocomplete="off" value="<?=$arResult['SECTION']['NAME']?>"/>
                                <ul style="cursor: pointer;" class="custom-serach__items" id="search_result_subgroup">
                                    <li value="" id="empty_subgroup" class="custom-serach__items_item subgroup-js">Здесь нет моей подгруппы</li>
                                    <?php
                                    while ($ar_result = $db_list_lvl3->GetNext()) { ?>
                                        <li value="<?=$ar_result['ID']?>" class="custom-serach__items_item  subgroup-js"><?php echo $ar_result['NAME']?></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
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
                <div class="input-with-search">
                    <div class="input__wrap">
                        <div class="input__ico">
                            <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                        </div>
                        <input data-id_diagnoz="" id="diagnoz_input" type="text"
                               placeholder="Выберите диагноз" autocomplete="off" value=""/>
                        <ul style="cursor: pointer;" class="custom-serach__items" id="search_result_diagnoz">
                            <li value="" id="empty_diagnoz" class="custom-serach__items_item diagnoz-js">Здесь нет моего диагноза</li>
                            <?php foreach ($arFields as $arItem) {?>
                                <li value="<?=$arItem['ID']?>" class="custom-serach__items_item  diagnoz-js"><?php echo $arItem['NAME']?></li>
                            <?php }?>
                        </ul>
                    </div>
                </div>
            <?php
            } else { ?>
                <div style="pointer-events: none" class="input-with-search">
                    <div class="input__wrap">
                        <div class="input__ico">
                            <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                        </div>
                        <input data-id_diagnoz="" id="diagnoz_input" type="text"
                               placeholder="Выберите диагноз" autocomplete="off" value=""/>
                    </div>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="input-with-search">
                <div class="input__wrap">
                    <div class="input__ico">
                        <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                    </div>
                    <input id="class_input" type="text" placeholder="Выберите класс" autocomplete="off"/>
                    <ul style="cursor: pointer;" class="custom-serach__items" id="search_result_class">
                        <?php
                        $i= 1;
                        foreach ($arResult['SECTIONS'] as &$arSection) { ?>
                            <?php if ($arSection['DEPTH_LEVEL'] == 1) { ?>
                                <?php if ($i == 1) { ?>
                                    <li value="" id="empty_class" class="custom-serach__items_item ">Здесь нет моего класса</li>
                                <?php } else { ?>
                                    <li value="<?=$arSection['ID']?>" class="custom-serach__items_item  class-js"><?php echo $arSection['NAME']?></li>
                                    <?php
                                }
                            } ?>
                            <?php
                            ++$i;
                        } ?>
                    </ul>
                </div>
            </div>
            <div style="pointer-events: none" class="input-with-search">
                <div class="input__wrap">
                    <div class="input__ico">
                        <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                    </div>
                    <input type="text" placeholder="Выберите группу" autocomplete="off" value=""/>
                </div>
            </div>
            <div style="pointer-events: none" class="input-with-search">
                <div class="input__wrap">
                    <div class="input__ico">
                        <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                    </div>
                    <input type="text" placeholder="Выберите подгруппу" autocomplete="off" value=""/>
                </div>
            </div>
            <div style="pointer-events: none" class="input-with-search">
                <div class="input__wrap">
                    <div class="input__ico">
                        <svg xmlns="http://www.w3.org/2000/svg" width="255" height="255" viewBox="0 0 255 255"><path d="M0 63.75l127.5 127.5L255 63.75z"/></svg>
                    </div>
                    <input type="text" placeholder="Выберите диагноз" autocomplete="off" value=""/>
                </div>
            </div>
        <?php }
        break;
    }

?>

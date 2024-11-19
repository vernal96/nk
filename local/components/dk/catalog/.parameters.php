<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/** @var array $arCurrentValues */

if (!CModule::IncludeModule('iblock')) {
    return;
}
Loader::includeModule('iblock');

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arInfoBlocks = [];

$arFilterInfoBlocks = ['ACTIVE' => 'Y'];

$arOrderInfoBlocks = ['SORT' => 'ASC'];

if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
    $arFilterInfoBlocks['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];
}

$rsIBlock = CIBlock::GetList($arOrderInfoBlocks, $arFilterInfoBlocks);

while ($obIBlock = $rsIBlock->Fetch()) {
    $arInfoBlocks[$obIBlock['ID']] = '[' . $obIBlock['ID'] . '] ' . $obIBlock['NAME'];
}

$arComponentParameters = [
    "GROUPS" => [
        "TREE" => [
            "NAME" => Loc::getMessage("CATALOG_GROUP_TREE"),
            "SORT" => 50
        ]
    ],
    "PARAMETERS" => [
        "VARIABLE_ALIASES" => [
            "ELEMENT_CODE" => [
                "NAME" => Loc::getMessage("CATALOG_ELEMENT_CODE"),
            ],
            "SECTION_CODE" => [
                "NAME" => Loc::getMessage("CATALOG_SECTION_CODE"),
            ],
            "CATALOG_URL" => [
                "NAME" => Loc::getMessage("CATALOG_CATALOG_URL"),
            ]
        ],
        "SEF_MODE" => [
            "section" => [
                "NAME" => Loc::getMessage("CATALOG_SEF_MODE_SECTION"),
                "DEFAULT" => "#SECTION_CODE#/",
                "VARIABLES" => [
                    "SECTION_ID",
                    "SECTION_CODE",
                    "SECTION_CODE_PATH",
                ],
            ],
            "element" => [
                "NAME" => Loc::getMessage("CATALOG_SEF_MODE_ELEMENT"),
                "DEFAULT" => "#SECTION_CODE#/#ELEMENT_CODE#",
                "VARIABLES" => [
                    "ELEMENT_ID",
                    "ELEMENT_CODE",
                    "SECTION_ID",
                    "SECTION_CODE",
                    "SECTION_CODE_PATH",
                ]
            ]
        ],
        'IBLOCK_TYPE' => [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage("CATALOG_IBLOCK_TYPE"),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlockType,
            'REFRESH' => 'Y',
            'DEFAULT' => 'news',
            'MULTIPLE' => 'N',
        ],
        'IBLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage("CATALOG_IBLOCK_ID"),
            'TYPE' => 'LIST',
            'VALUES' => $arInfoBlocks,
            'REFRESH' => 'Y',
            "DEFAULT" => '',
            "ADDITIONAL_VALUES" => "Y",
        ],
        "PRODUCT_COUNT" => [
            "PARENT" => "BASE",
            'NAME' => Loc::getMessage("CATALOG_PRODUCT_COUNT"),
            "TYPE" => "STRING"
        ],
        "CACHE_TIME" => ["DEFAULT" => CACHE_TIME],
        "AJAX_MODE" => []
    ]
];

CIBlockParameters::AddPagerSettings(
    $arComponentParameters,
    GetMessage("T_IBLOCK_DESC_PAGER_CATALOG"),
    true,
    true,
    true,
    ($arCurrentValues['PAGER_BASE_LIENABLE'] ?? 'N') === 'Y'
);

CIBlockParameters::Add404Settings($arComponentParameters, $arCurrentValues);
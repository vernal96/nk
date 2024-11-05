<?php

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
    "PARAMETERS" => [
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
        "CACHE_TIME" => ["DEFAULT" => CACHE_TIME],
    ]
];

CIBlockParameters::Add404Settings($arComponentParameters, $arCurrentValues);
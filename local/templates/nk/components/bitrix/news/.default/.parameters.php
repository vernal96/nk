<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/** @var array $arCurrentValues */

$arTemplateParameters = [
    "DISPLAY_DATE" => [
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_DATE"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "Y",
    ],
    "DISPLAY_PICTURE" => [
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_PICTURE"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "Y",
    ],
    "DISPLAY_PREVIEW_TEXT" => [
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_TEXT"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "Y",
    ],
    "USE_SHARE" => [
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_USE_SHARE"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "N",
        "REFRESH" => "Y",
    ],
];

if (($arCurrentValues['USE_SHARE'] ?? 'N') === 'Y') {
    $arTemplateParameters["SHARE_HIDE"] = [
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_HIDE"),
        "TYPE" => "CHECKBOX",
        "VALUE" => "Y",
        "DEFAULT" => "N",
    ];

    $arTemplateParameters["SHARE_TEMPLATE"] = [
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_TEMPLATE"),
        "DEFAULT" => "",
        "TYPE" => "STRING",
        "MULTIPLE" => "N",
        "COLS" => 25,
        "REFRESH" => "Y",
    ];

    $shareComponentTemplate = (trim((string)($arCurrentValues["SHARE_TEMPLATE"] ?? '')));
    if ($shareComponentTemplate === '') {
        $shareComponentTemplate = false;
    }

    include_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/bitrix/main.share/util.php");

    $arHandlers = __bx_share_get_handlers($shareComponentTemplate);

    $arTemplateParameters["SHARE_HANDLERS"] = [
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_SYSTEM"),
        "TYPE" => "LIST",
        "MULTIPLE" => "Y",
        "VALUES" => $arHandlers["HANDLERS"],
        "DEFAULT" => $arHandlers["HANDLERS_DEFAULT"],
    ];

    $arTemplateParameters["SHARE_SHORTEN_URL_LOGIN"] = [
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_SHORTEN_URL_LOGIN"),
        "TYPE" => "STRING",
        "DEFAULT" => "",
    ];

    $arTemplateParameters["SHARE_SHORTEN_URL_KEY"] = [
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_SHORTEN_URL_KEY"),
        "TYPE" => "STRING",
        "DEFAULT" => "",
    ];
}

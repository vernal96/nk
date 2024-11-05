<?

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = [
    "NAME" => Loc::getMessage("CATALOG_DETAIL_NAME"),
    "DESCRIPTION" => Loc::getMessage("CATALOG_DETAIL_DESCRIPTION"),
    "CACHE_PATH" => "Y",
    "PATH" => [
        "ID" => "nk"
    ]
];
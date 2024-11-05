<?

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = [
    "NAME" => Loc::getMessage("DK_CONTACTS_NAME"),
    "CACHE_PATH" => "Y",
    "PATH" => [
        "ID" => "nk",
        "NAME" => Loc::getMessage("NK_GROUP")
    ]
];
<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = [
    "NAME" => Loc::getMessage("CATALOG_NAME"),
    "CACHE_PATH" => "Y",
    "COMPLEX" => "Y",
    "PATH" => [
        "ID" => "nk"
    ]
];

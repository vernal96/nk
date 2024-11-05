<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = [
    "NAME" => Loc::getMessage("CART_NAME"),
    "CACHE_PATH" => "Y",
    "PATH" => [
        "ID" => "nk"
    ]
];
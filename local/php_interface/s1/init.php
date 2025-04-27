<?php

use Bitrix\Main\Loader;
use Bitrix\Main\SiteTable;
use Bitrix\Main\UI\Extension;

/** @var CMain $APPLICATION */

try {
    Loader::includeModule("iblock");
    Loader::includeModule(NK_MODULE_NAME);
} catch (Throwable $e) {
    addUncaughtExceptionToLog($e);
}

try {
    define("SITE_NAME", SiteTable::getById(SITE_ID)->fetchObject()->getName());
} catch (Throwable $e) {
    addUncaughtExceptionToLog($e);
    define("SITE_NAME", '');
}


CJSCore::Init();
Extension::load([
    "core",
    "dk.main",
    "dk.catalog.product.buttons.sizes",
    "dk.catalog.product.buttons.counter",
    "dk.catalog.sizes",
]);

$IS_MAIN_PAGE = $APPLICATION->GetCurPage() === "/";
$IS_CATALOG_PAGE = str_starts_with($APPLICATION->GetCurPage(), "/catalog/");

const MENU_PARAMS = [
    "ALLOW_MULTI_SELECT" => "N",
    "CHILD_MENU_TYPE" => "left",
    "COMPONENT_TEMPLATE" => "top",
    "COMPOSITE_FRAME_MODE" => "A",
    "COMPOSITE_FRAME_TYPE" => "AUTO",
    "DELAY" => "N",
    "MAX_LEVEL" => "2",
    "MENU_CACHE_GET_VARS" => [],
    "MENU_CACHE_TIME" => CACHE_TIME,
    "MENU_CACHE_TYPE" => "A",
    "MENU_CACHE_USE_GROUPS" => "Y",
    "ROOT_MENU_TYPE" => "top",
    "USE_EXT" => "N",
    "CACHE_SELECTED_ITEMS" => "N"
];
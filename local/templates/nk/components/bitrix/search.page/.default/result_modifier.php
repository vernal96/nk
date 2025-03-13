<?php

use Bitrix\Main\Config\Option;
use DK\NK\Helper\Main;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arResult ; */
$noPhoto = Main::getFileIdBySrc(Option::get(NK_MODULE_NAME, "NOPHOTO"));
foreach ($arResult["SEARCH"] as &$arItem) {
    if (isset($arItem["ITEM_ID"])) {
        if (!str_starts_with($arItem["ITEM_ID"], "S")) {
            $arItem = \DK\NK\Helper\Catalog::getProduct($arItem["ITEM_ID"]);
        } else {
            $arItem = \DK\NK\Helper\Catalog::getSection(preg_replace("/\D/", "", $arItem["ITEM_ID"]));
            $arItem["IS_SECTION"] = true;
        }
        $arItem["NO_PHOTO"] = $noPhoto;
    }
}
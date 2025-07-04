<? use DK\NK\Helper\Iblock;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arResult */
foreach ($arResult["CATEGORIES"] as &$arCategory) {
    foreach ($arCategory["ITEMS"] as &$arItem) {
        if (isset($arItem["ITEM_ID"])) {
            if (!str_starts_with($arItem["ITEM_ID"], "S")) {
                $arItem["ICON"] = Iblock::getIblockPhotoSrc(true, $arItem["ITEM_ID"], [54, 54]);
            } else {
                $arItem["ICON"] = Iblock::getIblockPhotoSrc(false, $arItem["ITEM_ID"], [54, 54]);
            }
        }
    }
}
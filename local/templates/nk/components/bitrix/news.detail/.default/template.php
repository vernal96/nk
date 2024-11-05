<?

use DK\NK\Helper\Main;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="content-blocks">
    <? if (is_array($arResult["DETAIL_PICTURE"])): ?>
        <picture class="content-image">
            <? Main::getPictureSrcSet($arResult["DETAIL_PICTURE"], [1719 => [1468, 300], 1199 => [940, 250], 991 => [720, 300], 767 => [510, 322], 575 => [470, 300]]); ?>
            <img
                    src="<?= CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], ["width" => 1682, "height" => 300], BX_RESIZE_IMAGE_EXACT)["src"]; ?>"
                    alt="<?= $arResult["DETAIL_PICTURE"]["ALT"] ?>"
                    title="<?= $arResult["DETAIL_PICTURE"]["TITLE"] ?>"
            >
        </picture>
    <? endif ?>
    <? if ($arResult["DETAIL_TEXT"] <> ''): ?>
        <div class="text-content white-block">
            <? echo $arResult["DETAIL_TEXT"]; ?>
            <?
            if (array_key_exists("USE_SHARE", $arParams) && $arParams["USE_SHARE"] == "Y") {
                $APPLICATION->IncludeComponent("bitrix:main.share", "", [
                    "HANDLERS" => $arParams["SHARE_HANDLERS"],
                    "PAGE_URL" => $arResult["~DETAIL_PAGE_URL"],
                    "PAGE_TITLE" => $arResult["~NAME"],
                    "SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
                    "SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
                    "HIDE" => $arParams["SHARE_HIDE"],
                ],
                    $component,
                    ["HIDE_ICONS" => "Y"]
                );
            }
            ?>
        </div>
    <? endif; ?>
</div>
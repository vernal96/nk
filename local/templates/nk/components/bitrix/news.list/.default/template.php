<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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

use DK\NK\Helper\Main;

$this->setFrameMode(true);
?>
<div class="news">
    <div class="news__list">
        <? foreach ($arResult["ITEMS"] as $arItem): ?>
            <? Main::include("elements/new", [...$arItem, "template" => $this]); ?>
        <? endforeach; ?>
    </div>
    <? if ($arResult["NAV_STRING"]) : ?>
        <div class="news__footer">
            <?= $arResult["NAV_STRING"]; ?>
        </div>
    <? endif; ?>
</div>
<?

use Bitrix\Main\Localization\Loc;

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
?>
<? if (!empty($arResult["SEARCH"])) : ?>
    <? $this->SetViewTarget("PAGE_DESCRIPTION"); ?>
    <div class="description container">
        <?= (new CTextParser())->convertText(Loc::getMessage("SEARCH_DESCRIPTION", [
            "#QUERY#" => $arResult["REQUEST"]["QUERY"],
        ])); ?>
    </div>
    <? $this->EndViewTarget(); ?>
    <div class="search-page content-blocks">
        <div class="search-page__items">
            <? foreach ($arResult["SEARCH"] as $searchItem) : ?>
                <? \DK\NK\Helper\Main::include("elements/product", [...$searchItem, "template" => $this]); ?>
            <? endforeach; ?>
        </div>
        <?= $arResult["NAV_STRING"]; ?>
    </div>
<? else: ?>
    <div class="white-block">
        <div class="text-content">
            <p>
                <?= (new CTextParser())->convertText(Loc::getMessage("SEARCH_EMPTY", [
                    "#QUERY#" => $arResult["REQUEST"]["QUERY"],
                ])); ?>
            </p>
        </div>
    </div>
<? endif; ?>

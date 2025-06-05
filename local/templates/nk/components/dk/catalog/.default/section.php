<?

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** @var object $component */
/** @var CMain $APPLICATION */
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
?>

<div class="section main-catalog container section--pt0" data-page-loader-container>
    <div class="catalog-wrapper">
        <? $section = $APPLICATION->IncludeComponent(
            "dk:catalog.categories",
            "",
            [
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "COMPOSITE_FRAME_MODE" => $arParams["COMPOSITE_FRAME_MODE"],
                "COMPOSITE_FRAME_TYPE" => $arParams["COMPOSITE_FRAME_TYPE"],
                "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"] ?? false,
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "DEPTH_LEVEL" => $arParams["SECTION_DEPTH_LEVEL"],
                "FULL" => $arParams["SECTION_FULL"],
                "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                "SHOW_404" => $arParams["SHOW_404"],
                "FILE_404" => $arParams["FILE_404"]
            ],
            $component,
            [],
            true
        ); ?>
        <? $this->SetViewTarget("CATALOG_TITLE"); ?>
        <h1 class="container title title--h1 title--page title--min-bottom" id="pagetitle"
            data-page-loader-start><?= $section["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] ?: $APPLICATION->GetTitle("h1"); ?></h1>
        <? $this->EndViewTarget(); ?>
        <div class="catalog-products catalog-main">
            <div class="content-blocks">
                <? $productsResult = $APPLICATION->IncludeComponent(
                    "dk:catalog.products",
                    "",
                    [
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "COMPOSITE_FRAME_MODE" => $arParams["COMPOSITE_FRAME_MODE"],
                        "COMPOSITE_FRAME_TYPE" => "DYNAMIC_WITH_STUB_LOADING",
                        "ACTIVE_SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"] ?? false,
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "SECTION_ID" => $section["SECTION_ID"],
                        "PRODUCT_COUNT" => $arParams["PRODUCT_COUNT"],
                        "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
                        "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                        "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                        "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                        "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                        "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                        "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                        "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                        "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                        "SHOW_404" => $arParams["SHOW_404"],
                        "FILE_404" => $arParams["FILE_404"],
                        "AJAX_MODE" => $arParams["AJAX_MODE"],
                        "AJAX_OPTION_ADDITIONAL" => $arParams["AJAX_OPTION_ADDITIONAL"],
                        "AJAX_OPTION_HISTORY" => $arParams["AJAX_OPTION_HISTORY"],
                        "AJAX_OPTION_JUMP" => $arParams["AJAX_OPTION_JUMP"],
                        "AJAX_OPTION_STYLE" => $arParams["AJAX_OPTION_STYLE"],
                        "PAGE" => $arResult["VARIABLES"]["PAGE"] ?? 1
                    ],
                    $component,
                    [],
                    true
                ); ?>
                <? if ($productsResult["NAV_OBJECT"]->getPageCount() > 1) : ?>
                    <div class="catalog-products__footer">
                        <? $APPLICATION->IncludeComponent("bitrix:main.pagenavigation", "",
                            [
                                "NAV_OBJECT" => $productsResult["NAV_OBJECT"],
                                "SEF_MODE" => "Y"
                            ],
                            $this
                        ); ?>
                    </div>
                <? endif; ?>
                <? if (!$arResult["VARIABLES"]["SECTION_CODE"] && Loc::getMessage("CATALOG_DESCRIPTION")) : ?>
                    <div class="white-block text-content">
                        <?= (new CTextParser())->convertText(Loc::getMessage("CATALOG_DESCRIPTION")); ?>
                    </div>
                <? endif; ?>
                <? if ($section["DESCRIPTION"]) : ?>
                    <div class="white-block text-content">
                        <?= $section["DESCRIPTION"]; ?>
                    </div>
                <? endif; ?>
            </div>
        </div>
    </div>
</div>
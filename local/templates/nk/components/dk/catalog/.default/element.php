<?

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?
/** @var object $component */
/** @var CMain $APPLICATION */
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
        <h2 class="container title title--h1 title--page title--min-bottom catalog-detail-page-header" id="pagetitle"
            data-page-loader-start><?= $section["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]; ?></h2>
        <? $this->EndViewTarget(); ?>

        <div class="catalog-product catalog-main">
            <? $element = $APPLICATION->IncludeComponent(
                "dk:catalog.detail",
                "",
                [
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "COMPOSITE_FRAME_MODE" => $arParams["COMPOSITE_FRAME_MODE"],
                    "COMPOSITE_FRAME_TYPE" => $arParams["COMPOSITE_FRAME_TYPE"],
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "SECTION_ID" => $section["SECTION_ID"],
                    "CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
                    "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                    "SHOW_404" => $arParams["SHOW_404"],
                    "FILE_404" => $arParams["FILE_404"]
                ],
                $component,
                [],
                true
            ); ?>

            <? $APPLICATION->IncludeComponent(
                "dk:catalog.recommend",
                "",
                [
                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "COMPOSITE_FRAME_MODE" => $arParams["COMPOSITE_FRAME_MODE"],
                    "COMPOSITE_FRAME_TYPE" => $arParams["COMPOSITE_FRAME_TYPE"],
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                    "IDS" => $element["RECOMMEND"],
                    "RAND_LIMIT" => 10,
                    "ELEMENT_ID" => $element["ID"]
                ],
                $component
            ); ?>
        </div>

    </div>
</div>
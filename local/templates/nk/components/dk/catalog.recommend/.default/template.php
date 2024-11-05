<?

use Bitrix\Main\Localization\Loc;
use DK\NK\Helper\Main;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */
?>
<section class="recommend">
    <h3 class="title title--min-bottom title--h3"><?= Loc::getMessage("CATALOG_RECOMMEND_TITLE"); ?></h3>
    <div class="product-slider">
        <div class="product-slider__nav product-slider__nav--prev">
            <button class="arrow arrow--prev" id="recommendArrowPrev"></button>
        </div>
        <div class="product-slider__main">
            <div class="swiper" id="recommendSwiper">
                <div class="swiper-wrapper">
                    <? foreach ($arResult as $arItem) : ?>
                        <div class="swiper-slide">
                            <? Main::include("elements/product", [...$arItem, "template" => $this]); ?>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
        </div>
        <div class="product-slider__nav product-slider__nav--next">
            <button class="arrow arrow--next" id="recommendArrowNext"></button>
        </div>
    </div>
</section>
<script>
    initCatalogRecommended();
</script>
<? use Bitrix\Main\Localization\Loc;

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
<? if ($arResult["ITEMS"]) : ?>
    <? if ($arParams["ONLY_CONTENT"] != "Y") : ?>
    <section class="section news container">
        <h2 class="title title--h2 title--min-bottom"><?= Loc::getMessage("NEWS_TITLE"); ?></h2>
        <? endif; ?>
        <div class="news__swiper swiper" id="newsSwiper">
            <div class="swiper-wrapper">
                <? foreach ($arResult["ITEMS"] as $arItem) : ?>
                    <div class="swiper-slide news__slide">
                        <? \DK\NK\Helper\Main::include("elements/new", [...$arItem, "template" => $this]); ?>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
        <div class="news__footer">
            <div class="arrows news__arrows" id="newsSwiperArrows">
                <button class="arrow arrow--prev"></button>
                <button class="arrow arrow--next"></button>
            </div>
            <a href="<?= $arResult["LIST_PAGE_URL"]; ?>" class="button"><?= Loc::getMessage("NEWS_LIST"); ?></a>
        </div>
        <? if ($arParams["ONLY_CONTENT"] != "Y") : ?>
    </section>
<? endif; ?>
    <script>
        initNewsSlider();
    </script>
<? endif; ?>
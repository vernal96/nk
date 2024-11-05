<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
if (!empty($arResult["CATEGORIES"]) && $arResult['CATEGORIES_ITEMS_EXISTS']):?>
    <div class="search__result">
        <? foreach ($arResult["CATEGORIES"] as $category_id => $arCategory): ?>
            <? if ($category_id == 0) : ?>
                <div class="search__result-list scroll">
            <? endif ?>
            <? foreach ($arCategory["ITEMS"] as $i => $arItem): ?>
                <? if (isset($arItem["MODULE_ID"])) : ?>
                    <a href="<?= $arItem["URL"]; ?>" class="mini-product mini-product--no-price search__result-item">
                        <img src="<?= $arItem["ICON"]; ?>" alt="" class="mini-product__image">
                        <span class="mini-product__title"><?= $arItem["NAME"] ?></span>
                    </a>
                <? elseif ($arItem["TYPE"] != "all") : ?>
                    <div class="search__result-footer">
                        <a href="<?= $arItem["URL"]; ?>" class="link"><?= $arItem["NAME"] ?></a>
                    </div>
                <? endif; ?>
            <? endforeach; ?>
            <? if ($category_id == 0) : ?>
                </div>
            <? endif; ?>
        <? endforeach; ?>
    </div>
    <div class="title-search-fader"></div>
<?endif;
?>
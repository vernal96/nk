<?

use Bitrix\Main\Localization\Loc;
use DK\NK\Cart;
use DK\NK\Helper\Main;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */
$this->setFrameMode(true);
?>
<?
$this->AddEditAction("edit_$arResult[ID]", $arResult["LINKS"]["EDIT"]["URL"], $arResult["LINKS"]["EDIT"]["TITLE"]);
$this->AddEditAction("edit_$arResult[ID]", $arResult["LINKS"]["DELETE"]["URL"], $arResult["LINKS"]["DELETE"]["TITLE"]);
?>
<? if (count($arResult["PRICES"]) > 1) : ?>
    <div class="product-table" id="<?= $this->GetEditAreaId("edit_$arResult[ID]"); ?>">
        <div class="product-table__header">
            <? if ($arResult["DETAIL_PICTURE"]) : ?>
                <div class="product-table__image pic" data-fancybox
                     data-src="<?= $arResult["DETAIL_PICTURE"]["SRC"]; ?>">
                    <img
                            src="<?= CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], ["width" => 50, "height" => 50], 2)["src"]; ?>"
                            alt="<?= $arResult["DETAIL_PICTURE"]["ALT"]; ?>"
                            title="<?= $arResult["DETAIL_PICTURE"]["TITLE"]; ?>"
                    >
                </div>
            <? endif; ?>
            <h1 class="product-table__title"><?= $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]; ?></h1>
        </div>
        <div class="product-table__content" id="product-table">
            <table>
                <? for ($i = 0; $i < 6; $i++) : ?>
                    <tr>
                        <? for ($j = 0; $j < 5; $j++) : ?>
                            <td>
                                <div class="content-loader"></div>
                            </td>
                        <? endfor; ?>
                    </tr>
                <? endfor; ?>
            </table>
            <script>BX.Vue3.createApp(DK.Sizes, {productId: <?=$arResult["ID"];?>}).mount("#product-table");</script>
        </div>
    </div>
<? else : ?>
    <div class="product-page" id="<?= $this->GetEditAreaId("edit_$arResult[ID]"); ?>">
        <? if ($arResult["DETAIL_PICTURE"]) : ?>
            <picture class="product-page__image product-page__image--desktop pic" data-fancybox
                     data-src="<?= $arResult["DETAIL_PICTURE"]["SRC"]; ?>">
                <? Main::getPictureSrcSet($arResult["DETAIL_PICTURE"]["DETAIL_PICTURE"], [
                    1430 => [350, 350],
                    660 => [0, 0]
                ]); ?>
                <img
                        src="<?= CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], ["width" => 452, "height" => 452], 3)["src"]; ?>"
                        alt="<?= $arResult["DETAIL_PICTURE"]["ALT"]; ?>"
                        title="<?= $arResult["DETAIL_PICTURE"]["TITLE"]; ?>"
                >
            </picture>
        <? endif; ?>
        <div class="product-page__main">
            <h1 class="title title--h3 title--min-bottom title--underline product-page__title">
                <?= $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]; ?>
            </h1>
            <? if ($arResult["DETAIL_PICTURE"]) : ?>
                <picture class="product-page__image product-page__image--mobile pic" data-fancybox
                         data-src="<?= $arResult["DETAIL_PICTURE"]["SRC"]; ?>">
                    <? Main::getPictureSrcSet($arResult["DETAIL_PICTURE"]["DETAIL_PICTURE"], [
                        900 => [520, 264],
                        620 => [575, 292],
                        500 => [464, 236]
                    ]); ?>
                    <img
                            src="<?= CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], ["width" => 650, "height" => 330], 3)["src"]; ?>"
                            alt="<?= $arResult["DETAIL_PICTURE"]["ALT"]; ?>"
                            title="<?= $arResult["DETAIL_PICTURE"]["TITLE"]; ?>"
                    >
                </picture>
            <? endif; ?>
            <div class="product-page__buy" id="productBuy">
                <? $frame = $this->createFrame("productBuy", false)->begin(); ?>
                <? $frame->setBrowserStorage(true); ?>
                <div class="product-page__sum">
                    <?= Main::priceFormat($arResult["PRICES"][0]["UF_PRICE_" . Main::getUserType()]); ?> <?= Loc::getMessage("CURRENCY"); ?>
                </div>
                <div class="product-page__counter-place" id="counter-place">
                    <? $sizeId = $arResult["PRICES"][0]["ID"]; ?>
                    <script>
                        BX.Vue3.createApp(DK.Counter, {
                            productId: <?=$arResult["ID"]?>,
                            sizeId: <?=$sizeId;?>,
                            cartCount: <?=Cart::getInstance()->getSizeCount($sizeId, $arResult["ID"]);?>
                        }).mount("#counter-place");</script>
                </div>

                <? $frame->beginStub(); ?>
                <div class="product-page__sum">
                    <div class="content-loader content-loader--white content-loader--16h"></div>
                </div>
                <div class="product-page__sum">
                    <div class="content-loader content-loader--white content-loader--20h"></div>
                </div>
                <? $frame->end(); ?>
            </div>
            <? if ($arResult["PROPERTIES"]["SPECIFICATIONS"]["VALUE"]) : ?>
                <div class="properties">
                    <? foreach ($arResult["PROPERTIES"]["SPECIFICATIONS"]["VALUE"] as $key => $value) : ?>
                        <div class="properties__row">
                            <div class="properties__key"><?= $value; ?></div>
                            <div class="properties__value"><?= $arResult["PROPERTIES"]["SPECIFICATIONS"]["DESCRIPTION"][$key]; ?></div>
                        </div>
                    <? endforeach; ?>
                </div>
            <? endif; ?>
        </div>
    </div>
<? endif; ?>

<? if ($arResult["DETAIL_TEXT"] || $arResult["PROPERTIES"]["DESCRIPTION"]["~VALUE"]) : ?>
    <div class="content-blocks">
        <? if ($arResult["DETAIL_TEXT"]) : ?>
            <div class="slide-text is-active">
                <div class="slide-text__header slider-text-header">
                    <span><?= Loc::getMessage("DESCRIPTION_TITLE"); ?></span>
                </div>
                <div class="slide-text__content-outer">
                    <div class="slide-text__content">
                        <div class="text-content">
                            <?= $arResult["DETAIL_TEXT"]; ?>
                        </div>
                    </div>
                </div>
            </div>
        <? endif; ?>
        <? if ($arResult["PROPERTIES"]["DESCRIPTION"]["~VALUE"]) : ?>
            <? foreach ($arResult["PROPERTIES"]["DESCRIPTION"]["~VALUE"] as $index => $value) : ?>
                <div class="slide-text<?= $arResult["DETAIL_TEXT"] || $index ? "" : " is-active"; ?>">
                    <div class="slide-text__header slider-text-header">
                        <span><?= $arResult["PROPERTIES"]["DESCRIPTION"]["DESCRIPTION"][$index]; ?></span>
                    </div>
                    <div class="slide-text__content-outer"
                         <? if ($arResult["DETAIL_TEXT"] || $index) : ?>style="display:none;"<? endif; ?>>
                        <div class="slide-text-content">
                            <div class="slide-text__content">
                                <div class="text-content">
                                    <?= $value["TEXT"]; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>
        <? endif; ?>
    </div>
<? endif; ?>
<script>
    BX("pagetitle").classList.add("catalog-detail-page-header");
    BX.onCustomEvent(window, 'onProductPageReady');
</script>

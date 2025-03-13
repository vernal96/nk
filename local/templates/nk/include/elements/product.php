<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use DK\NK\Cart;
use DK\NK\Helper\Catalog;
use DK\NK\Helper\Main;

/** @var array $PARAMS */
$PARAMS["template"]->AddEditAction($PARAMS["ID"], $PARAMS["LINKS"]["EDIT"]["URL"], $PARAMS["LINKS"]["EDIT"]["TITLE"]);
$PARAMS["template"]->AddDeleteAction($PARAMS["ID"], $PARAMS["LINKS"]["DELETE"]["URL"], $PARAMS["LINKS"]["DELETE"]["TITLE"]);
?>

<div class="product<?= $PARAMS["IS_SECTION"] ? " product--section" : ""; ?>"
     id="<?= $PARAMS["template"]->GetEditAreaId($PARAMS["ID"]); ?>">
    <a href="<?= $PARAMS["DETAIL_PAGE_URL"] ?: $PARAMS["SECTION_PAGE_URL"]; ?>" class="product__header">
        <?
        if (!$PARAMS["IS_SECTION"]) {
            $image = $PARAMS["PREVIEW_PICTURE"] ?: $PARAMS["NO_PHOTO"];
        } else {
            $image = $PARAMS["PICTURE"] ?: $PARAMS["NO_PHOTO"];
        }
        ?>
        <picture class="product__image">
            <img src="<?= CFile::ResizeImageGet($image, $PARAMS["IS_SECTION"] ? Catalog::PRODUCT_SECTION_IMAGE_SIZE : Catalog::PRODUCT_IMAGE_SIZE, 3)["src"]; ?>"
                 alt="<?= $PARAMS["IS_SECTION"] ? $PARAMS["PICTURE"]["ALT"] : $PARAMS["PREVIEW_PICTURE"]["ALT"]; ?>"
                 title="<?= $PARAMS["IS_SECTION"] ? $PARAMS["PICTURE"]["TITLE"] : $PARAMS["PREVIEW_PICTURE"]["TITLE"]; ?>"
            >
        </picture>
        <span class="product__title">
            <?= $PARAMS["NAME"]; ?>
        </span>
    </a>
    <? if ($PARAMS["IS_SECTION"]) : ?>
    <? else : ?>
        <? $frame = $PARAMS["template"]->createFrame()->begin(); ?>
        <? $frame->setBrowserStorage(true); ?>
        <?php
        $arPrice = Catalog::getProductPrices($PARAMS["ID"]);
        $sizeTable = count($arPrice) > 1;
        $priceType = Application::getInstance()->getSession()->get("USER_STATUS") ?: 1;
        ?>
        <span class="product__price<?= $sizeTable ? " product__price--min" : ""; ?>">
            <?= $sizeTable ? Loc::getMessage("FROM") : ""; ?>
            <?= Main::priceFormat(current($arPrice)["UF_PRICE_$priceType"]); ?>
            <?= Loc::getMessage("CURRENCY"); ?>
        </span>
        <?
        $placementId = $PARAMS["template"]->GetEditAreaId($PARAMS["ID"] . "_button");
        ?>
        <div id="<?= $placementId; ?>">
            <script>
                <? if ($sizeTable) : ?>
                BX.Vue3.createApp(DK.SizesButton, {productId: <?=$PARAMS["ID"];?>}).mount("#<?=$placementId;?>");
                <? else: ?>
                <? $sizeId = $arPrice[0]["ID"] ?: 0;?>
                BX.Vue3.createApp(DK.Counter, {
                        productId: <?=$PARAMS["ID"]?>,
                        sizeId: <?=$sizeId;?>,
                        cartCount: <?=Cart::getInstance()->getSizeCount($sizeId, $PARAMS["ID"])?>
                    }
                ).mount("#<?=$placementId;?>");
                <? endif; ?>
            </script>
        </div>
        <? $frame->beginStub(); ?>
        <span class="product__price content-loader content-loader--20 content-loader--14h"></span>
        <button class="button button--center button--transparent button--bordered button--100 product__button">
            <span class="content-loader"></span>
        </button>
        <? $frame->end(); ?>
    <? endif; ?>
</div>
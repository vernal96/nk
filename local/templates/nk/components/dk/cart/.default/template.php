<?php

use Bitrix\Main\Localization\Loc;
use DK\NK\Cart;

/** @var array $arResult */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>
<? $frame = $this->createFrame()->begin(); ?>
<? if ($deal = Cart::getInstance()->getDeal()) : ?>
    <div class="white-block">
        <? \DK\NK\Helper\Main::include("form_success", [
            "static" => true,
            "successTitle" => Loc::getMessage("CART_SUCCESS_TITLE", ["#DEAL#" => \DK\NK\Helper\Main::getApplicationFormat($deal)]),
            "successDescription" => Loc::getMessage("CART_SUCCESS_DESCRIPTION")
        ]); ?>
    </div>
<? else : ?>
    <? $sum = Cart::getInstance()->getTotalSum(); ?>
    <? $count = Cart::getInstance()->getTotalCount(); ?>
    <div class="cart-block">
        <? if ($count["value"]) : ?>
            <div class="title title--h3 title--min-bottom hidden-after-success"><?= Loc::getMessage("CART_TOTAL_TITLE"); ?></div>
            <div class="cart__total white-block hidden-after-success">
                <div class="cart__sum"><?= Loc::getMessage("CART_TOTAL_SUM"); ?> <span
                            class="total-sum"><?= $sum["format"]; ?></span>
                </div>
                <div class="cart__count"><?= Loc::getMessage("CART_TOTAL_COUNT"); ?> <span class="total-count"><?= $count["format"]; ?></span></div>
            </div>
            <div id="cart" class="cart"></div>
            <script>
                DK.deliveries = <?=CUtil::PhpToJSObject($arResult['ITEMS']);?>;
                BX.Vue3.createApp(DK.Cart).mount("#cart");
            </script>
        <? else : ?>
            <div class="white-block">
                <div class="text-content">
                    <p>
                        <?= (new CTextParser())->convertText(Loc::getMessage("CART_TOTAL_EMPTY")); ?>
                    </p>
                </div>
            </div>
        <? endif; ?>
    </div>
<? endif; ?>
<? $frame->beginStub(); ?>
    <div class="cart-block">
        <div class="title title--h3 title--min-bottom content-loader content-loader--40"></div>
        <div class="cart__total white-block">
            <div class="cart__sum">
                <div class="content-loader"></div>
            </div>
            <div class="cart__count total-count">
                <div class="content-loader"></div>
            </div>
        </div>
    </div>
<? $frame->end(); ?>
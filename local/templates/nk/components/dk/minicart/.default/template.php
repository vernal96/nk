<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
global $CART;
/** @var array $arResult */
/** @var array $arParams */
$this->setFrameMode(true);
?>

<a href="/cart/" class="mini-cart<?= $arParams["MOBILE"] == "Y" ? " mini-cart--border" : ""; ?>">
    <? $frame = $this->createFrame()->begin(); ?>
    <span class="total-sum mini-cart__content<?= $arResult["EMPTY"] ? " mini-cart__content--empty" : "" ?>">
        <?= $arResult["TEXT"]; ?>
    </span>
    <? $frame->beginStub(); ?>
    <span class="mini-cart__content content-loader"></span>
    <? $frame->end(); ?>
    <span class="mini-cart__icon"></span>
</a>
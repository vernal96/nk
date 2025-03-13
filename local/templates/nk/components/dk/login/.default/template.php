<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\Extension;


if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
/** @var CMain $APPLICATION */
$this->setFrameMode(true);
?>
<? $frame = $this->createFrame()->begin(); ?>
<? if ($arResult["AUTH"]) : ?>
    <?php
    $arrClasses = ["login"];
    if (isset($arResult["USER"]["STATUS"]) && $arResult["USER"]["STATUS"] > 1) {
        $arrClasses[] = "login--with-status";
        if ($arResult["USER"]["STATUS"] == 2) $arrClasses[] = "login--with-status-silver";
        if ($arResult["USER"]["STATUS"] == 3) $arrClasses[] = "login--with-status-gold";
    }
    ?>
    <a href="/personal/" class="<?= implode(" ", $arrClasses); ?>">
        <img src="<?= $arResult["USER"]["IMAGE"]; ?>" alt="" class="login__image">
        <span class="login__content">
            <span class="login__title"><?= $arResult["USER"]["TITLE"]; ?></span>
            <span class="login__description"><?= Loc::getMessage("LOGIN_DESCRIPTION"); ?></span>
        </span>
    </a>
<? else : ?>
    <button class="login modal-trigger" data-src="#authorize">
        <span class="login__image login__image--auth"></span>
        <span class="login__title"><?= Loc::getMessage("LOGIN_START"); ?></span>
    </button>
    <div id="authorize" class="modal"></div>
    <script>
        BX.message(<?=CUtil::PhpToJSObject(Loc::loadLanguageFile(__FILE__));?>);
    </script>
    <? Extension::load("dk.login"); ?>
<? endif; ?>
<? $frame->beginStub(); ?>
<div class="login">
    <span class="login__image loader-fill"></span>
    <span class="login__title content-loader content-loader--9"></span>
</div>
<? $frame->end(); ?>

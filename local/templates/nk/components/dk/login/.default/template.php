<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
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
    <button class="login" onclick="openAuth(event)">
        <span class="login__image login__image--auth"></span>
        <span class="login__title"><?= Loc::getMessage("LOGIN_START"); ?></span>
    </button>
    <script>
        function openAuth(event) {
            BX.ajax.get("/personal/", {FRAME: "Y"}, response => {
                const modal = DK.Methods.createStructure({
                    classes: ["modal", "modal--visible"],
                    children: [
                        {
                            classes: "modal__header",
                            children: [
                                {
                                    classes: "modal__title",
                                    content: "Вход в личный кабинет"
                                }
                            ]
                        },
                        {
                            classes: "modal__form",
                            inner: response
                        }
                    ]
                });
                new Fancybox([
                    {
                        src: modal,
                        type: "html"
                    }
                ], {
                    dragToClose: false,
                    Hash: false,
                    autoFocus: false
                });
            });
        }
    </script>
<? endif; ?>
<? $frame->beginStub(); ?>
    <div class="login">
        <span class="login__image loader-fill"></span>
        <span class="login__title content-loader content-loader--9"></span>
    </div>
<? $frame->end(); ?>

<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
?>
<? if (!empty($arResult)) : ?>
    <nav class="menu">
        <ul>
            <? $previousLevel = 0; ?>
            <? foreach ($arResult

            as $index => $arItem) : ?>
            <? if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel): ?>
                <?= str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"])); ?>
            <? endif ?>
            <? if ($arItem["IS_PARENT"]): ?>
            <li class="menu__parent"><a href="<?= $arItem["LINK"]; ?>"><?= $arItem["TEXT"]; ?></a>
                <ul>
                    <? else: ?>
                        <? if ($arItem["PERMISSION"] > "D"): ?>
                            <li>
                                <? if ($arItem["PARAMS"]["CATALOG"] == "Y") : ?>
                                    <button class="button button--medium menu__catalog">
                                        <i class="icon icon--burger menu__catalog-opener"></i>
                                        <i class="icon icon--close menu__catalog-closer"></i>
                                        <span><?= $arItem["TEXT"]; ?></span>
                                    </button>
                                <? else : ?>
                                    <a href="<?= $arItem["LINK"]; ?>"><?= $arItem["TEXT"]; ?></a>
                                <? endif; ?>
                            </li>
                        <? endif ?>
                    <? endif; ?>
                    <? $previousLevel = $arItem["DEPTH_LEVEL"]; ?>
                    <? endforeach; ?>
                    <? if ($previousLevel > 1): ?>
                        <?= str_repeat("</ul></li>", ($previousLevel - 1)); ?>
                    <? endif ?>
    </nav>
<? endif; ?>

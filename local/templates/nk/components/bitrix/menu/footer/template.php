<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
?>
<? if (!empty($arResult)) : ?>
    <nav class="container footer__menu">
        <? foreach ($arResult as $arItem) : ?>
            <a href="<?=$arItem['LINK']?>"><?= $arItem['TEXT']; ?></a>
        <? endforeach; ?>
    </nav>
<? endif; ?>

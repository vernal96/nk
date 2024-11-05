<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
?>
<? if (!empty($arResult)) : ?>
    <div class="social-network">
        <? foreach ($arResult as $name => $link) : ?>
            <a href="<?= $link; ?>" rel="nofollow" target="_blank"
               class="social-network__item social-network__item--<?= $name; ?>"></a>
        <? endforeach; ?>
    </div>
<? endif; ?>
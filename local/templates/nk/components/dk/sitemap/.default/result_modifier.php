<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */

function printLi($items): void
{
    foreach ($items as $item) {
        ?>
        <li>
            <a href="<?= $item['link']; ?>">
                <?= $item['text']; ?>
            </a>
            <? if ($item['children']) : ?>
                <ul>
                    <? printLi($item['children']); ?>
                </ul>
            <? endif; ?>
        </li>
        <?
    }
}
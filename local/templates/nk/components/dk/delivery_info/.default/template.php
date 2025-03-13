<?php

/** @var array $arResult */

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$introText = Loc::getMessage('DK_DELIVERY_INFO_INTRO');
$textParser = new CTextParser();
//printR($arResult);
?>

<div class="content-blocks delivery-info">
    <? if ($introText) : ?>
        <div class="text-content white-block">
            <?= $textParser->convertText($introText); ?>
        </div>
    <? endif; ?>
    <? if ($arResult['ITEMS']) : ?>
        <div class="delivery-info-items">
            <? foreach ($arResult['ITEMS'] as $item) : ?>
                <div class="delivery-info-item">
                    <?
                    $imagePath = $item['IMAGE']['SRC'];
                    if ($item['IMAGE']['CONTENT_TYPE'] != 'image/svg+xml') {
//                        $imagePath = CFile::ResizeImageGet($item['IMAGE'], )
                    }
                    ?>
                    <img src="<?= $imagePath; ?>" alt="<?= $textParser->stripAllTags($item['TITLE']); ?>"
                         class="delivery-info-item__image">
                    <div class="delivery-info-item__title"><?= $item['TITLE']; ?></div>
                    <div class="delivery-info-item__description"><?= $item['DESCRIPTION']; ?></div>
                </div>
            <? endforeach; ?>
        </div>
    <? endif; ?>
</div>
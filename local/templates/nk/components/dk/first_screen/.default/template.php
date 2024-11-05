<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use DK\NK\Helper\Main;

/** @var array $arResult */
/** @var array $arParams */
?>
<div class="container">
    <div class="first-screen">
        <div class="first-screen__main">
            <div class="first-screen__content swiper" id="firstScreenContent">
                <div class="swiper-wrapper">
                    <? foreach ($arResult as $key => $arItem) : ?>
                        <?
                        $this->AddEditAction("tab-" . $arItem["ID"], $arItem["LINKS"]["EDIT"]["URL"], $arItem["LINKS"]["EDIT"]["TITLE"]);
                        $this->AddDeleteAction("tab-" . $arItem["ID"], $arItem["LINKS"]["DELETE"]["URL"], $arItem["LINKS"]["DELETE"]["TITLE"]);
                        ?>
                        <div class="first-screen__content-item swiper-slide" id="<?= $this->GetEditAreaId("tab-" . $arItem["ID"]) ?>">
                            <h<?= !$key ? 1 : 2; ?> class="title title--min-bottom title--h1 first-screen__title">
                                <?= $arItem["NAME"]; ?>
                            </h<?= !$key ? 1 : 2; ?>>
                            <? if ($arItem["DETAIL_TEXT"]) : ?>
                                <div class="first-screen__subtitle"><?= $arItem["DETAIL_TEXT"]; ?></div>
                            <? endif; ?>
                            <? if ($arItem["PROPERTIES"]["LINK_TEXT"]["VALUE"]) : ?>
                                <a href="<?= $arItem["PROPERTIES"]["LINK"]["VALUE"]; ?>"
                                   class="link link--white"><?= $arItem["PROPERTIES"]["LINK_TEXT"]["VALUE"]; ?></a>
                            <? endif; ?>
                        </div>
                    <? endforeach; ?>
                </div>
            </div>
        </div>
        <div class="first-screen__images swiper" id="firstScreenImages">
            <div class="swiper-wrapper">
                <? foreach ($arResult as $key => $arItem) : ?>
                    <picture class="first-screen__image swiper-slide">
                        <? Main::getPictureSrcSet($arItem["DETAIL_PICTURE"], [
                            1430 => [1340, 510],
                            1000 => [940, 430],
                            600 => [560, 360]
                        ]); ?>
                        <img src="<?= CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], ["width" => 1714, "height" => 600], BX_RESIZE_IMAGE_EXACT)["src"]; ?>"
                             alt="<?= $arItem["DETAIL_PICTURE"]["ALT"] ?>"
                             title="<?= $arItem["DETAIL_PICTURE"]["TITLE"] ?>">
                    </picture>

                <? endforeach; ?>
            </div>
        </div>
        <div class="first-screen__dots dots" id="firstScreenDots"></div>
    </div>
</div>
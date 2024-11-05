<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use DK\NK\Helper\Main;

/** @var array $PARAMS */

($PARAMS["template"])->AddEditAction($PARAMS["ID"], $PARAMS["LINKS"]["EDIT"]["URL"], $PARAMS["LINKS"]["EDIT"]["TITLE"]);
($PARAMS["template"])->AddDeleteAction($PARAMS["ID"], $PARAMS["LINKS"]["DELETE"]["URL"], $PARAMS["LINKS"]["DELETE"]["TITLE"]);
$topLevel = $PARAMS["IBLOCK_SECTION_ID"] == 0;
?>
<li
        id="<?= $PARAMS["template"]->GetEditAreaId($PARAMS["ID"]); ?>"
        class="catalog-category-wrapper<?= !$PARAMS["CURRENT"] ? " closed" : ""; ?>"
>
    <<? if ($PARAMS["SECTIONS"]): ?>div<? else : ?>a href="<?= $PARAMS["SECTION_PAGE_URL"] ?>"<? endif; ?>
            class="catalog-category
            <?= $PARAMS["CURRENT"] ? " is-active" : ""; ?>
            <?= $PARAMS["UF_NEW"] ? " is-tagged is-tagged--new" : ""; ?>
            <?= $topLevel ? "" : "catalog-category--min"; ?>
            "
    >
        <?
        if ($topLevel) {
            $picture = $PARAMS["UF_ICON"];
        } else {
            $PARAMS["PICTURE"]["SRC"] = $PARAMS["PICTURE"] ? CFile::ResizeImageGet($PARAMS["PICTURE"]["ID"], ["width" => 40, "height" => 40], 2)["src"] : $PARAMS["PICTURE"];
            $picture = $PARAMS["PICTURE"];
        }

        ?>
        <? if ($picture["ID"]) : ?>
            <img
                    src="<?= $picture["SRC"]; ?>"
                    alt="<?= $picture["ALT"] ?>"
                    title="<?= $picture["TITLE"]; ?>"
                    class="catalog-category__<?= $topLevel ? "image" : "picture"; ?>">
        <? endif; ?>
        <span class="catalog-category__label">
            <? if ($PARAMS["SECTIONS"]): ?>
                <a href="<?= $PARAMS["SECTION_PAGE_URL"] ?>" class="catalog-category__link">
                    <?= $PARAMS["NAME"]; ?>
                </a>
            <? else : ?>
                <?= $PARAMS["NAME"]; ?>
            <? endif; ?>
        </span>
        <? if ($PARAMS["SECTIONS"]) : ?>
            <span class="catalog-category__arrow catalog-category__arrow--down"></span>
        <? endif; ?>
    </<? if ($PARAMS["SECTIONS"]): ?>div<? else : ?>a<? endif; ?>>
    <? if ($PARAMS["SECTIONS"]) : ?>
        <ul class="catalog-categories__children">
            <? foreach ($PARAMS["SECTIONS"] as $arItem) : ?>
                <? Main::include("elements/category", [...$arItem, "template" => $PARAMS["template"]]); ?>
            <? endforeach; ?>
        </ul>
    <? endif; ?>
</li>
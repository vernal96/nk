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
        class="catalog-category-wrapper"
>
    <a href="<?= $PARAMS["SECTION_PAGE_URL"] ?>"
            class="catalog-category
            <?= $PARAMS["CURRENT"] ? " is-active" : ""; ?>
            <?= $PARAMS["UF_NEW"] ? " is-tagged is-tagged--new" : ""; ?>
            <?= $topLevel ? "" : "catalog-category--min"; ?>
            "
    >
        <?
        if ($topLevel) {
            $picture = [
                "ID" => $PARAMS["~UF_ICON"],
                "SRC" => $PARAMS["UF_ICON"],
                "ALT" => $PARAMS["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_ALT"],
                "TITLE" => $PARAMS["IPROPERTY_VALUES"]["SECTION_PICTURE_FILE_TITLE"],
            ];
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
            <?= $PARAMS["NAME"]; ?>
        </span>
        <? if ($PARAMS["HAVE_CHILDREN"]) : ?>
            <span class="catalog-category__arrow catalog-category__arrow--down"></span>
        <? endif; ?>
    </a>
    <? if ($PARAMS["HAVE_CHILDREN"]) : ?>
        <ul class="catalog-categories__children">
            <? foreach ($PARAMS["CHILDREN"] as $arItem) : ?>
                <? Main::include("elements/category", [...$arItem, "template" => $PARAMS["template"]]); ?>
            <? endforeach; ?>
        </ul>
    <? endif; ?>
</li>
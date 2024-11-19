<?php

use DK\NK\Helper\Main;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arResult */
$this->setFrameMode(true);
?>

<? if ($arResult["ELEMENTS"]) : ?>
    <? $this->AddEditAction("element_tab_add", $arResult["ADD_LINK"]["URL"], $arResult["ADD_LINK"]["TITLE"]); ?>
    <div id="<?= $this->GetEditAreaId("element_tab_add_element"); ?>"></div>
    <div class="catalog-products__main" id="<?= $this->GetEditAreaId("element_tab_add"); ?>">
        <? foreach ($arResult["ELEMENTS"] as $arItem) : ?>
            <? Main::include("elements/product", [...$arItem, "template" => $this]); ?>
        <? endforeach; ?>
    </div>
<? endif; ?>
<script>
    BX("pagetitle").classList.remove("catalog-detail-page-header");
</script>
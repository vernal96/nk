<?php

use Bitrix\Main\Web\Json;
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
        <? $schemaIterator = 1; ?>
        <? $schemaResult = [
            "@context" => "https://schema.org",
            "@type" => "ItemList",
            "itemListElement" => []
        ]; ?>
        <? foreach ($arResult["ELEMENTS"] as $arItem) : ?>
            <? Main::include("elements/product", [...$arItem, "template" => $this]); ?>
            <?
            if (!$arItem["IS_SECTION"]) {
                $arPrice = \DK\NK\Helper\Catalog::getProductPrices($arItem["ID"]);
                $schemaItem = [
                    "@type" => "ListItem",
                    "position" => $schemaIterator,
                    "item" => [
                        "@type" => "Product",
                        "name" => $arItem["NAME"],
                        "offers" => [
                            "@type" => "Offer",
                            "url" => "https://" . SITE_SERVER_NAME . $arItem["DETAIL_PAGE_URL"],
                            "priceCurrency" => "RUB",
                            "price" => $arPrice[0]["UF_PRICE_1"]
                        ]
                    ]

                ];
                if ($arItem["PREVIEW_PICTURE"]) {
                    $schemaItem["item"]["image"] = "https://" . SITE_SERVER_NAME . $arItem["PREVIEW_PICTURE"]["SRC"];
                }
                $schemaResult["itemListElement"][] = $schemaItem;
                $schemaIterator++;
            }
            ?>
        <? endforeach; ?>
    </div>
    <? if (!\Bitrix\Main\Context::getCurrent()->getRequest()->isAjaxRequest()) : ?>
        <script type="application/ld+json">
      <?= Json::encode($schemaResult); ?>

        </script>
    <? endif; ?>
<? endif; ?>
<script>
    BX("pagetitle").classList.remove("catalog-detail-page-header");
</script>
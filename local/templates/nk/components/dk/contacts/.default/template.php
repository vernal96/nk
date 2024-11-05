<?

/** @var array $arResult */

use DK\NK\Helper\Main;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->AddEditAction("market_add", $arResult["LINK_ADD"]["URL"], $arResult["LINK_ADD"]["TITLE"]);
?>
<div class="content-blocks" id="<?= $this->GetEditAreaId("market_add"); ?>">
    <? foreach ($arResult["MARKETS"] as $arItem) : ?>
        <div class="contact-item">
            <? Main::include("elements/contact", [
                ...$arItem,
                "CLASS" => "contact-item__data",
                "template" => $this
            ]); ?>
            <div class="contact-item__map"
                 data-coord="<?= $arItem["PROPERTIES"]["COORD"]["VALUE"]; ?>"
                 data-icon="<?= SITE_TEMPLATE_PATH . "/src/images/marker.svg" ?>"
            ></div>
        </div>
    <? endforeach; ?>
</div>

<? use Bitrix\Main\Localization\Loc;
use DK\NK\Helper\Main;

/** @var array $arResult */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->AddEditAction("market_add", $arResult["LINK_ADD"]["URL"], $arResult["LINK_ADD"]["TITLE"]);
?>
<div class="contacts section section--pb0">
    <h2 class="title title--h2 title--min-bottom"><?= Loc::getMessage("CONTACTS_TITLE"); ?></h2>
    <div class="contacts__wrapper" id="<?= $this->GetEditAreaId("market_add"); ?>">
        <div class="contacts__list scroll">
            <? foreach ($arResult["MARKETS"] as $arItem) : ?>
                <? Main::include("elements/contact", [...$arItem, "CLASS" => "contacts__item", "template" => $this]); ?>
            <? endforeach; ?>
        </div>
        <div class="contacts__map" data-icon="<?= SITE_TEMPLATE_PATH . "/src/images/marker.svg" ?>">
            <? foreach ($arResult["MARKETS"] as $arItem) : ?>
                <span data-coord="<?= $arItem["PROPERTIES"]["COORD"]["VALUE"]; ?>"></span>
            <? endforeach; ?>
        </div>
    </div>
</div>
<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use DK\NK\Helper\Main;

/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */
?>

<? if ($arResult["TREE"]) : ?>
    <?
    $this->AddEditAction("add_section", $arResult["ADD_LINK"]["URL"], $arResult["ADD_LINK"]["TITLE"]);
    ?>
    <div class="catalog-categories-outer">
        <ul class="catalog-categories scroll" id="<?= $this->GetEditAreaId("add_section") ?>">
            <? foreach ($arResult["TREE"] as $arItem) : ?>
                <? Main::include("elements/category", [...$arItem, "template" => $this]); ?>
            <? endforeach; ?>
        </ul>
    </div>
<? endif; ?>

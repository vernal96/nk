<? use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

if (!$arResult["NavShowAlways"]) {
    if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
        return;
}
$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"] . "&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?" . $arResult["NavQueryString"] : "");
?>
<div class="pagination-wrapper">
    <div class="pagination">
        <? if ($arResult["bDescPageNumbering"] === true): ?>
            <? if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]): ?>
                <? if ($arResult["bSavePage"]): ?>
                    <a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["NavPageCount"] ?>"
                       class="pagination__item">
                        <?= GetMessage("nav_begin") ?>
                    </a>
                    <a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] + 1) ?>"
                       class="pagination__arrow pagination__arrow--prev"></a>
                <? else: ?>
                    <a href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>"
                       class="pagination__item"><?= GetMessage("nav_begin") ?></a>
                    <? if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"] + 1)): ?>
                        <a href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>"
                           class="pagination__arrow pagination__arrow--prev"></a>
                    <? else: ?>
                        <a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] + 1) ?>"
                           class="pagination__arrow pagination__arrow--prev"><?= GetMessage("nav_prev") ?></a>
                    <? endif ?>
                <? endif ?>
            <? else: ?>
                <div class="pagination__item pagination__item--closed"><?= GetMessage("nav_begin") ?></div>
                <div class="pagination__arrow pagination__arrow--prev pagination__arrow--closed"></div>
            <? endif ?>
            <? while ($arResult["nStartPage"] >= $arResult["nEndPage"]): ?>
                <? $NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1; ?>
                <? if ($arResult["nStartPage"] == $arResult["NavPageNomer"]): ?>
                    <div class="pagination__item pagination__item--active"><?= $NavRecordGroupPrint ?></div>
                <? elseif ($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false): ?>
                    <a href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>"
                       class="pagination__item"><?= $NavRecordGroupPrint ?></a>
                <? else: ?>
                    <a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["nStartPage"] ?>"
                       class="pagination__item"><?= $NavRecordGroupPrint ?></a>
                <? endif ?>
                <? $arResult["nStartPage"]-- ?>
            <? endwhile ?>
            <? if ($arResult["NavPageNomer"] > 1): ?>
                <a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] - 1) ?>"
                   class="pagination__arrow pagination__arrow--next"><?= GetMessage("NEXT") ?></a>
                <a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=1"
                   class="pagination__item"><?= GetMessage("nav_end") ?></a>
            <? else: ?>
                <div class="pagination__arrow pagination__arrow--next pagination__arrow--closed"><?= Loc::getMessage("NEXT") ?></div>
                <div class="pagination__item"><?= GetMessage("nav_end") ?></div>
            <? endif ?>
        <? else : ?>
            <? if ($arResult["NavPageNomer"] > 1): ?>
                <? if ($arResult["bSavePage"]): ?>
                    <a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=1"
                       class="pagination__item"><?= GetMessage("nav_begin") ?></a>
                    <a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] - 1) ?>"
                       class="pagination__arrow pagination__arrow--prev"></a>
                <? else: ?>
                    <a href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>"
                       class="pagination__item"><?= GetMessage("nav_begin") ?></a>
                    <? if ($arResult["NavPageNomer"] > 2): ?>
                        <a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] - 1) ?>"
                           class="pagination__arrow pagination__arrow--prev"></a>
                    <? else: ?>
                        <a href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>"
                           class="pagination__arrow pagination__arrow--prev"></a>
                    <? endif ?>
                <? endif ?>
            <? else: ?>
                <div class="pagination__item pagination__item--closed"><?= GetMessage("nav_begin") ?></div>
                <div class="pagination__arrow pagination__arrow--prev pagination__arrow--closed"></div>
            <? endif ?>
            <? while ($arResult["nStartPage"] <= $arResult["nEndPage"]): ?>
                <? if ($arResult["nStartPage"] == $arResult["NavPageNomer"]): ?>
                    <div class="pagination__item pagination__item--active"><?= $arResult["nStartPage"] ?></div>
                <? elseif ($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false): ?>
                    <a href="<?= $arResult["sUrlPath"] ?><?= $strNavQueryStringFull ?>"
                       class="pagination__item"><?= $arResult["nStartPage"] ?></a>
                <? else: ?>
                    <a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["nStartPage"] ?>"
                       class="pagination__item"><?= $arResult["nStartPage"] ?></a>
                <? endif ?>
                <? $arResult["nStartPage"]++ ?>
            <? endwhile ?>
            <? if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]): ?>
                <a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= ($arResult["NavPageNomer"] + 1) ?>"
                   class="pagination__arrow pagination__arrow--next"></a>
                <a href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>PAGEN_<?= $arResult["NavNum"] ?>=<?= $arResult["NavPageCount"] ?>"
                   class="pagination__item"><?= GetMessage("nav_end") ?></a>
            <? else: ?>
                <div class="pagination__arrow pagination__arrow--next pagination__arrow--closed"></div>
                <div class="pagination__item pagination__item--closed"><?= GetMessage("nav_end") ?></div>
            <? endif ?>
        <? endif; ?>
        <? if ($arResult["bShowAll"]): ?>
            <? if ($arResult["NavShowAll"]): ?>
                <a
                        href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>SHOWALL_<?= $arResult["NavNum"] ?>=0"
                        rel="nofollow"
                        class="pagination__item"><?= GetMessage("nav_paged") ?></a>
            <? else: ?>
                <a
                        href="<?= $arResult["sUrlPath"] ?>?<?= $strNavQueryString ?>SHOWALL_<?= $arResult["NavNum"] ?>=1"
                        rel="nofollow" class="pagination__item"><?= GetMessage("nav_all") ?></a>
            <? endif ?>
        <? endif ?>
    </div>
    <div class="pagination-total">
        <?= $arResult["NavTitle"]; ?> <?= $arResult["NavPageNomer"]; ?> <?= GetMessage("nav_of"); ?> <?= $arResult["NavPageCount"]; ?>
    </div>
</div>
<? use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @var CBitrixComponentTemplate $this */

/** @var PageNavigationComponent $component */
$component = $this->getComponent();
$this->setFrameMode(true);
?>

<div class="pagination-wrapper">
    <div class="pagination">
        <? if ($arResult["CURRENT_PAGE"] > 1): ?>
            <? if ($arResult["CURRENT_PAGE"] > 2): ?>
                <a href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"] - 1)) ?>"
                   class="pagination__arrow pagination__arrow--prev"></a>
            <? else: ?>
                <a href="<?= htmlspecialcharsbx($arResult["URL"]) ?>"
                   class="pagination__arrow pagination__arrow--prev"></a>
            <? endif ?>
            <a href="<?= htmlspecialcharsbx($arResult["URL"]) ?>"
               class="pagination__item">1</a>
        <? else: ?>
            <div class="pagination__arrow pagination__arrow--prev pagination__arrow--closed"></div>
            <div class="pagination__item pagination__item--active">1</div>
        <? endif ?>
        <? if ($arResult["START_PAGE"] > 1) : ?>
            <div class="pagination__item pagination__item--empty">...</div>
        <? endif; ?>
        <?
        $page = $arResult["START_PAGE"] + 1;
        while ($page <= $arResult["END_PAGE"] - 1):
            ?>
            <? if ($page == $arResult["CURRENT_PAGE"]): ?>
            <div class="pagination__item pagination__item--active"><?= $page; ?></div>
        <? else: ?>
            <a href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($page)) ?>"
               class="pagination__item"><?= $page ?></a>
        <? endif ?>
            <? $page++ ?>
        <? endwhile ?>

        <? if ($arResult["END_PAGE"] < $arResult["PAGE_COUNT"]) : ?>
            <? if ($arResult["END_PAGE"] + 1 == $arResult["PAGE_COUNT"]) : ?>
                <a href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($page)) ?>"
                   class="pagination__item"><?= $page ?></a>
            <? else : ?>
                <div class="pagination__item pagination__item--empty">...</div>
            <? endif; ?>
        <? endif; ?>

        <? if ($arResult["CURRENT_PAGE"] < $arResult["PAGE_COUNT"]): ?>
            <? if ($arResult["PAGE_COUNT"] > 1): ?>
                <a href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["PAGE_COUNT"])) ?>"
                   class="pagination__item"><?= $arResult["PAGE_COUNT"] ?></a>
            <? endif ?>
            <a href="<?= htmlspecialcharsbx($component->replaceUrlTemplate($arResult["CURRENT_PAGE"] + 1)) ?>"
               class="pagination__arrow pagination__arrow--next"></a>
        <? else: ?>
            <? if ($arResult["PAGE_COUNT"] > 1): ?>
                <div class="pagination__item pagination__item--active"><?= $arResult["PAGE_COUNT"] ?></div>
            <? endif ?>
            <div class="pagination__arrow pagination__arrow--next pagination__arrow--closed"></div>
        <? endif ?>

        <? if ($arResult["SHOW_ALL"]): ?>
            <? if ($arResult["ALL_RECORDS"]): ?>
                <a class="pagination__item" href="<?= htmlspecialcharsbx($arResult["URL"]) ?>"
                   rel="nofollow"><?= Loc::getMessage("ALL") ?></a>
            <? else: ?>
                <a class="pagination__item" href="<?= htmlspecialcharsbx($component->replaceUrlTemplate("all")) ?>"
                   rel="nofollow"><?= Loc::getMessage("ALL") ?></a>
            <? endif ?>
        <? endif ?>

    </div>
    <div class="pagination-total">
        <?= Loc::getMessage("NAV_PAGES"); ?> <?= $arResult["CURRENT_PAGE"]; ?> <?= Loc::getMessage("OF"); ?> <?= $arResult["PAGE_COUNT"]; ?>
    </div>
</div>

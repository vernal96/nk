<?

use Bitrix\Main\Localization\Loc;

/** @var CBitrixComponentTemplate $this */
/** @var array $arResult */
/** @var array $arParams */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

?>
<div class="about">
    <h2 class="title title--h2 title--min-bottom"><?= Loc::getMessage("ABOUT_TITLE"); ?></h2>
    <div class="text-content about__content">
        <?= (new CTextParser())->convertText(
            Loc::getMessage(
                "ABOUT_CONTENT",
                [
                    "#YEARS_COUNT#" => $arResult["YEARS_COUNT"],
                    "#YEAR#" => $arResult["YEAR_START"],
                ]
            )
        ); ?>
    </div>
    <? if (!empty($arResult["PROPERTIES"])) : ?>
        <div class="about__properties">
            <? foreach ($arResult["PROPERTIES"] as $property) : ?>
                <div class="about__property">
                    <img src="<?= $property["UF_ICO"]; ?>" alt="" class="about__property-image">
                    <div class="about__property-content">
                        <?= $property["UF_TEXT"]; ?>
                    </div>
                </div>
            <? endforeach; ?>
        </div>
    <? endif; ?>
    <a href="<?= $arParams["LINK"]; ?>" class="link about__link"><?= Loc::getMessage("ABOUT_MORE"); ?></a>
    <img
            src="<?= CFile::ResizeImageGet($arResult["PICTURE"]["id"], ["width" => 780, "height" => 550], 1)["src"]; ?>"
            alt="<?= $arResult["PICTURE"]["alt"]; ?>"
            title="<?= $arResult["PICTURE"]["title"]; ?>"
            class="about__image"
    >
</div>


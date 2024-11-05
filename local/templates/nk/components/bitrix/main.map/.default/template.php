<?

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!is_array($arResult["arMap"]) || count($arResult["arMap"]) < 1)
    return;

?>

<ul>

    <? foreach ($arResult["arMapStruct"] as $arItem) : ?>
        <li>
            <a href="<?= $arItem["FULL_PATH"] ?>"><?= $arItem["NAME"] ?></a>
        </li>
    <? endforeach; ?>
</ul>
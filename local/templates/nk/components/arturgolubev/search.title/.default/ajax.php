<? use Bitrix\Main\Config\Option;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult["CATEGORIES"]) && $arResult["DEBUG"]["SHOW"] != 'Y') return;

IncludeTemplateLangFile(__FILE__);

$arParams["SHOW_PREVIEW_TEXT"] = ($arParams["SHOW_PREVIEW_TEXT"]) ? $arParams["SHOW_PREVIEW_TEXT"] : 'Y';

$preview = ($arParams["SHOW_PREVIEW"] != 'N');

$image_style = '';
$info_style = '';

$noPhotoId = \DK\NK\Helper\Main::getFileIdBySrc(Option::get(NK_MODULE_NAME, "NOPHOTO"));
$noPhotoSrc = CFile::ResizeImageGet($noPhotoId, ['width' => 100, 'height' => 100])['src'];

if ($preview) {
    if ($arParams["PREVIEW_WIDTH_NEW"]) {
        $image_style .= 'width: ' . $arParams["PREVIEW_WIDTH_NEW"] . 'px;';
        $info_style .= 'padding-left: ' . ($arParams["PREVIEW_WIDTH_NEW"] + 5) . 'px;';
    }
    if ($arParams["PREVIEW_HEIGHT_NEW"]) {
        $image_style .= 'height: ' . $arParams["PREVIEW_HEIGHT_NEW"] . 'px;';
    }
    if ($info_style) $info_style = 'style="' . $info_style . '"';
}
?>

<div class="search__result">
    <div class="search__result-list scroll">
        <? if (!empty($arResult["CATEGORIES"])): ?>

            <? foreach ($arResult["CATEGORIES"] as $category_id => $arCategory): ?>

                <? foreach ($arCategory["ITEMS"] as $i => $arItem): ?>
                    <? if (isset($arResult["SECTIONS"][$arItem["ITEM_ID"]])):
                        $arElement = $arResult["SECTIONS"][$arItem["ITEM_ID"]];

                        if (is_array($arElement["PICTURE"]))
                            $image_url = $arElement["PICTURE"]["src"];
                        else
                            $image_url = $noPhotoSrc;
                        ?>
                        <a class="mini-product mini-product--no-price search__result-item"
                           href="<? echo $arItem["URL"] ?>">
                            <? if ($preview):?>
                                <img class="mini-product__image" src="<?= $image_url ?>" alt="">
                            <? endif; ?>
                            <span class="mini-product__content">
                            <? if ($arElement['PATH']) : ?>
                                <span class="mini-product__under-title"><?= $arElement['PATH']; ?></span>
                            <? endif; ?>
                            <span class="mini-product__title"><?= $arItem["NAME"] ?></span>
                        </span>
                        </a>
                    <? endif; ?>
                <? endforeach; ?>
                <? foreach ($arCategory["ITEMS"] as $i => $arItem): ?>
                    <? if (isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]])):
                        $arElement = $arResult["ELEMENTS"][$arItem["ITEM_ID"]];

                        $arElement["PREVIEW_TEXT"] = strip_tags($arElement["PREVIEW_TEXT"]);

                        if ($arItem['IS_HINT']) {
                            $image_url = '/bitrix/components/arturgolubev/search.title/templates/.default/images/search-icon.svg';
                        } elseif (is_array($arElement["PICTURE"])) {
                            $image_url = $arElement["PICTURE"]["src"];
                        } else {
                            $image_url = $noPhotoSrc;
                        }
                        ?>
                        <a class="mini-product mini-product--no-price search__result-item"
                           href="<? echo $arItem["URL"] ?>">
                            <? if ($preview):?>
                                <img class="mini-product__image" src="<?= $image_url ?>" alt="">
                            <? endif; ?>
                            <span class="mini-product__content">
                            <span class="mini-product__title"><? echo $arItem["NAME"] ?></span>
                        </span>
                        </a>
                    <? endif; ?>
                <? endforeach; ?>

                <? foreach ($arCategory["ITEMS"] as $i => $arItem): ?>
                    <? if (isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]]) || isset($arResult["SECTIONS"][$arItem["ITEM_ID"]])):
                        continue;
                    elseif ($category_id === "all"):?>
                        <div class="search__result-footer">
                            <a href="<?= $arItem["URL"]; ?>" class="link"><?= $arItem["NAME"] ?></a>
                        </div>
                    <? endif; ?>
                <? endforeach; ?>
            <? endforeach; ?>

        <? else: ?>
            <?= GetMessage("AG_SMARTIK_NO_RESULT"); ?>
        <? endif; ?>
    </div>
</div>
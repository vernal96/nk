<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use DK\NK\Helper\Main;

/** @var array $PARAMS */

$PARAMS["template"]->AddEditAction($PARAMS['ID'], $PARAMS['EDIT_LINK'], CIBlock::GetArrayByID($PARAMS["IBLOCK_ID"], "ELEMENT_EDIT"));
$PARAMS["template"]->AddDeleteAction($PARAMS['ID'], $PARAMS['DELETE_LINK'], CIBlock::GetArrayByID($PARAMS["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);
?>
<a href="<?= $PARAMS["DETAIL_PAGE_URL"]; ?>" class="news-item"
   id="<?= $PARAMS["template"]->GetEditAreaId($PARAMS['ID']); ?>">
    <picture class="news-item__image">
        <img
            <?
            $image = $PARAMS["PREVIEW_PICTURE"] ?: Main::getFileIdBySrc(Option::get(NK_MODULE_NAME, "NOPHOTO"));
            ?>
                src="<?= CFile::ResizeImageGet($image, ["width" => 410, "height" => 280], 2)["src"]; ?>"
                alt="<?= $PARAMS["PREVIEW_PICTURE"]["ALT"]; ?>"
                title="<?= $PARAMS["PREVIEW_PICTURE"]["TITLE"]; ?>"
        >
    </picture>
    <span class="news-item__title">
        <?= $PARAMS["PREVIEW_TEXT"]; ?>
    </span>
    <span class="news-item__footer">
        <span class="link news-item__link">
            <?= Loc::getMessage("REED_FULL"); ?>
        </span>
        <span class="news-item__date">
            <?= $PARAMS["DISPLAY_ACTIVE_FROM"]; ?>
        </span>
    </span>
</a>
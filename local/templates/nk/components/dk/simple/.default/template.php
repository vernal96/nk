<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
?>
<?
$this->AddEditAction($arResult["ID"], $arResult["EDIT"]["ACTION_URL"], $arResult["EDIT"]["TITLE"]);
?>
<div class="content-blocks" id="<?= $this->GetEditAreaId($arResult["ID"]); ?>">
    <? if ($arResult["PREVIEW_TEXT"]) : ?>
        <div class="text-content white-block">
            <?= $arResult["PREVIEW_TEXT"]; ?>
        </div>
    <? endif; ?>
    <? if ($arResult["DETAIL_TEXT"]) : ?>
        <div class="text-content white-block">
            <?= $arResult["DETAIL_TEXT"]; ?>
        </div>
    <? endif; ?>
</div>
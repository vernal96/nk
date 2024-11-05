<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

/** @var array $PARAMS */
?>
<? if ($PARAMS["TEXT_ONLY"]) : ?>
    <?= (new CTextParser())->convertText(Loc::getMessage("NK_FORM_CONFIRM")); ?>
<? else : ?>
    <label class="checkbox form-confirm feedback__confirm">
        <input type="checkbox" data-form-confirm checked>
        <span class="checkbox__fake"></span>
        <span class="checkbox__content">
        <?= (new CTextParser())->convertText(Loc::getMessage("NK_FORM_CONFIRM")); ?>
    </span>
    </label>
<? endif; ?>
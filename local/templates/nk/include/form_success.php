<?php

use Bitrix\Main\Localization\Loc;

/** @var array $PARAMS */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
?>

<div class="form-success<?= $PARAMS["horizontal"] ? " form-success--horizontal" : ""; ?><?= $PARAMS["static"] ? " form-success--static" : ""; ?>">
    <div class="form-success__inner">
        <div class="form-success__image">
            <svg class="checkmark<?= $PARAMS["error"] ? " checkmark_close" : ""; ?>" xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 52 52">
                <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"></circle>
                <? if ($PARAMS["error"]) : ?>
                    <path class="checkmark__close" d="M15 15L35 35" stroke-linecap="round"/>
                    <path class="checkmark__close" d="M35 15L15 35" stroke-linecap="round"/>
                <? else: ?>
                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"
                          stroke-linecap="round"></path>
                <? endif; ?>
            </svg>
        </div>
        <div class="form-success__content">
            <div class="form-success__title">
                <?= $PARAMS["successTitle"] ?? Loc::getMessage("FORM_SUCCESS_TITLE", ["#DEAL_ID#" => $PARAMS["formatDealId"]]); ?>
            </div>
            <div class="form-success__description">
                <?= $PARAMS["successDescription"] ?? Loc::getMessage("FORM_SUCCESS_DESCRIPTION"); ?>
            </div>
        </div>
    </div>
</div>
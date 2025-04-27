<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectException;
use Bitrix\Main\Type\Date as BitrixDate;
use Bitrix\Main\UI\Extension;
use DK\NK\Helper;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


/** @var CModule $APPLICATION */

/** @var CUser $USER */

Extension::load(['ui.buttons', 'ui.alerts']);

$canPermissions = Helper\Main::checkUserGroup($USER->GetID(), 1);

$expiresDate = Option::get(NK_MODULE_NAME, 'YANDEX_APP_TOKEN_EXPIRES', null);


if ($expiresDate) {
    try {
        $expiresDate = new BitrixDate($expiresDate);
        $currentDate = new BitrixDate();
        $expiresDays = $expiresDate->getDiff($currentDate)->days;
    } catch (ObjectException $e) {
        $expiresDays = null;
    }
} else {
    $expiresDays = null;
}

$clientId = Option::get(NK_MODULE_NAME, 'YANDEX_APP_CLIENT_ID', null);

if (!$clientId) $canPermissions = false;

$url = "https://oauth.yandex.ru/authorize?response_type=token&client_id=$clientId";

?>

<? if ($canPermissions) : ?>
    <div class="ui-alert ui-alert-primary">
        <?= Loc::getMessage("NK_YANDEX_TOKEN_RELOAD_INFO"); ?>
    </div>
    <a href="<?= $url; ?>" target="_blank" class="ui-btn ui-btn-light-border">
        <?= Loc::getMessage('NK_YANDEX_TOKEN_RELOAD_HREF'); ?>
    </a>
<? else : ?>
    <div class="ui-alert ui-alert-warning">
        <span class="ui-alert-message">
            <?= (new CTextParser())->convertText(Loc::getMessage("NK_YANDEX_TOKEN_RELOAD_HAS_NOT_PERMISSIONS")); ?>
        </span>
    </div>
<? endif; ?>

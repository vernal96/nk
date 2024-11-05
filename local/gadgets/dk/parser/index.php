<?php

/** @var CModule $APPLICATION */

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UI\Extension;
use DK\NK\Parser;

Extension::load([
    "ui.buttons",
    "ui.layout-form",
    "ui.alerts"
]);
Loader::includeModule(NK_MODULE_NAME);

$request = Application::getInstance()->getContext()->getRequest();

if ($request->get("PARSER_UPDATE")) {
    $result = Parser::start();
}

?>

<form action="" method="post">
    <? if (isset($result)) : ?>
        <div class="ui-alert ui-alert-<?= ($result === true) ? "success" : "danger"; ?>">
        <span class="ui-alert-message">
            <? if ($result === true) : ?>
                <strong><?= Loc::getMessage("NK_PARSER_SUCCESS"); ?></strong> <?= Loc::getMessage("NK_PARSER_SUCCESS_MESSAGE"); ?>
            <? else: ?>
                <strong><?= Loc::getMessage("NK_PARSER_ERROR"); ?></strong> <?= $result; ?>
            <? endif; ?>
        </span>
        </div>
    <? endif; ?>
    <div class="ui-form-row">
        <?
        $APPLICATION->IncludeComponent("bitrix:main.file.input", "drag_n_drop", [
            "INPUT_NAME" => "IMAGES",
            "ALLOW_UPLOAD" => "I",
            "MULTIPLE" => "Y",
            "CONTROL_ID" => "parser_images"
        ]);
        ?>
    </div>
    <div class="ui-form-row">
        <div class="ui-form-content">
            <button class="ui-btn ui-btn-success" name="PARSER_UPDATE"
                    value="1"><?= Loc::getMessage("NK_PARSER_RUN") ?></button>
        </div>
    </div>
</form>
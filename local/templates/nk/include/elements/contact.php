<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use DK\NK\Helper\Main;

/** @var array $PARAMS */

$PARAMS["template"]->AddEditAction($PARAMS["ID"], $PARAMS["LINKS"]["EDIT"]["URL"], $PARAMS["LINKS"]["EDIT"]["TITLE"]);
$PARAMS["template"]->AddDeleteAction($PARAMS["ID"], $PARAMS["LINKS"]["DELETE"]["URL"], $PARAMS["LINKS"]["DELETE"]["TITLE"]);
?>
<div class="<?= $PARAMS["CLASS"]; ?> contact" id="<?= $PARAMS["template"]->GetEditAreaId($PARAMS["ID"]); ?>">
    <div class="contact__item contact__item--main contact__item--icon">
        <i class="icon icon--location"></i>
        <?= $PARAMS["NAME"]; ?>
    </div>
    <div class="contact__item"><?= Loc::getMessage("EMAIL"); ?>:
        <a href="mailto:<?= $PARAMS["PROPERTIES"]["EMAIL"]["VALUE"]; ?>"
           class="simple-link"><?= $PARAMS["PROPERTIES"]["EMAIL"]["VALUE"]; ?></a>
    </div>
    <div class="contact__item"><?= Loc::getMessage("PHONE"); ?>:
        <a href="tel:<?= Main::getPhone($PARAMS["PROPERTIES"]["PHONE"]["VALUE"]); ?>"
           class="simple-link"><?= $PARAMS["PROPERTIES"]["PHONE"]["VALUE"]; ?></a>
    </div>
    <div class="contact__item contact__item--main contact__item--icon">
        <i class="icon icon--time"></i>
        <?= Loc::getMessage("TIMES"); ?>
    </div>
    <? foreach ($PARAMS["PROPERTIES"]["TIMES"]["VALUE"] as $key => $value) : ?>
        <div class="contact__item"><?= $PARAMS["PROPERTIES"]["TIMES"]["DESCRIPTION"][$key] ?>
            : <?= $value; ?></div>
    <? endforeach; ?>
</div>

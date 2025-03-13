<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use DK\NK\Helper\Main;

?>
<div class="feedback">
    <div class="container feedback__container">
        <h3 class="title title--h2 title--mb-0">
            <?= Loc::getMessage("DK_NK_FEEDBACK_TITLE"); ?>
        </h3>
        <form action="?" class="form feedback__form ajax_form">
            <?= bitrix_sessid_post(); ?>
            <input type="hidden" name="st" value="1">
            <input type="hidden" name="g-token" value="">
            <div class="form-content feedback__form-content">
                <div class="total-error" style="display: none;">
                    <div class="note note--error total-error-content"></div>
                </div>
                <div class="feedback__form-wrapper">
                    <label class="input">
                        <span class="input__title"><?= Loc::getMessage("DK_NK_TITLE_NAME"); ?></span>
                        <input type="text" name="name" placeholder="<?= Loc::getMessage("DK_NK_PLACEHOLDER_NAME"); ?>">
                    </label>
                    <label class="input input--required">
                        <span class="input__title"><?= Loc::getMessage("DK_NK_TITLE_PHONE"); ?></span>
                        <input type="tel" name="tel" placeholder="<?= Loc::getMessage("DK_NK_PLACEHOLDER_PHONE"); ?>">
                    </label>
                    <button class="button button--100" type="submit">
                        <?= Loc::getMessage("DK_NK_SUBMIT"); ?>
                    </button>
                </div>
                <? Main::include("form_confirm"); ?>
            </div>
        </form>
    </div>
</div>
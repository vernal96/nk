<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use DK\NK\Helper\Main;

?>

<div class="modal" id="recall">
    <form action="" class="form ajax_form">
        <input type="hidden" name="st" value="0">
        <input type="hidden" name="g-token" value="">
        <div class="form-content">
            <div class="modal__header">
                <div class="modal__title"><?= Loc::getMessage("NK_MODAL_RECALL_TITLE"); ?></div>
                <div class="modal__subtitle"><?= Loc::getMessage("NK_MODAL_RECALL_SUBTITLE"); ?></div>
            </div>
            <div class="modal__form">
                <div class="total-error" style="display: none;">
                    <div class="note note--error total-error-content"></div>
                </div>
                <label class="input">
                    <span class="input__title"><?= Loc::getMessage("DK_NK_TITLE_NAME"); ?></span>
                    <input type="text" name="name" placeholder="<?= Loc::getMessage("DK_NK_PLACEHOLDER_NAME"); ?>">
                </label>
                <label class="input input--required">
                    <span class="input__title"><?= Loc::getMessage("DK_NK_TITLE_PHONE"); ?></span>
                    <input type="tel" name="tel" placeholder="<?= Loc::getMessage("DK_NK_PLACEHOLDER_PHONE"); ?>">
                </label>
                <label class="input">
                    <span class="input__title"><?= Loc::getMessage("DK_NK_TITLE_COMMENT"); ?></span>
                    <textarea name="comment"
                              placeholder="<?= Loc::getMessage("DK_NK_PLACEHOLDER_COMMENT"); ?>"></textarea>
                </label>
            </div>
            <div class="modal__footer">
                <? Main::include("form_confirm"); ?>
                <button type="submit"
                        class="button button--orange button--100"><?= Loc::getMessage("DK_NK_SUBMIT"); ?></button>
            </div>
        </div>
    </form>
</div>

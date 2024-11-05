<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CMain $APPLICATION */
/** @var bool $IS_MAIN_PAGE */

/** @var bool $IS_CATALOG_PAGE */

use Bitrix\Main\Config\Option;
use Bitrix\Main\Type\Date as BitrixDateTime;
use DK\NK\Helper\Main;

?>
<? if (!$IS_MAIN_PAGE && !$IS_CATALOG_PAGE) : ?>
    </section>
<? endif; ?>
</main>
<footer class="footer">
    <? $APPLICATION->IncludeComponent("dk:feedback", "", [
        "CACHE_TIME" => "36000000",
        "CACHE_TYPE" => "A"
    ]); ?>
    <div class="footer__bottom">
        <div class="container footer__wrapper">
            <a href="/" class="logo footer__logo">
                <img src="<?= SITE_TEMPLATE_PATH; ?>/src/images/logo-min.svg" alt="<?= SITE_NAME; ?>">
            </a>
            <div class="footer__copyright">
                ©️&nbsp;<?= Option::get(NK_MODULE_NAME, "YEAR_START"); ?>
                - <?= (new BitrixDateTime())->format("Y"); ?> <?= SITE_NAME; ?>
            </div>
            <? $APPLICATION->IncludeComponent(
                "dk:socnet",
                "",
                [
                    "CACHE_TIME" => "36000000",
                    "CACHE_TYPE" => "A"
                ]
            ); ?>
        </div>
    </div>
</footer>
</div>
<? Main::include("modal/recall"); ?>
<? $APPLICATION->IncludeComponent("dk:mm", "", []); ?>
</body>
</html>
<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\UI\Extension;
use DK\NK\Helper\Main;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CMain $APPLICATION */
/** @var CUser $USER */
/** @var bool $IS_MAIN_PAGE */
/** @var bool $IS_CATALOG_PAGE */

if (!Main::checkUserGroup($USER->GetID(), [1, 'MANAGER'])) {
    Extension::load('dk.seo.yandex');
}

?>

    <!DOCTYPE html>
<html lang="<?= LANGUAGE_ID; ?>">

    <head>
        <title><? $APPLICATION->ShowTitle(); ?></title>
        <?php
        $APPLICATION->ShowHead();
        Asset::getInstance()->addString('<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">');
        Asset::getInstance()->addString('
            <link rel="apple-touch-icon" sizes="57x57" href="' . SITE_TEMPLATE_PATH . '/src/images/favicon/apple-icon-57x57.png">
            <link rel="apple-touch-icon" sizes="60x60" href="' . SITE_TEMPLATE_PATH . '/src/images/favicon/apple-icon-60x60.png">
            <link rel="apple-touch-icon" sizes="72x72" href="' . SITE_TEMPLATE_PATH . '/src/images/favicon/apple-icon-72x72.png">
            <link rel="apple-touch-icon" sizes="76x76" href="' . SITE_TEMPLATE_PATH . '/src/images/favicon/apple-icon-76x76.png">
            <link rel="apple-touch-icon" sizes="114x114" href="' . SITE_TEMPLATE_PATH . '/src/images/favicon/apple-icon-114x114.png">
            <link rel="apple-touch-icon" sizes="120x120" href="' . SITE_TEMPLATE_PATH . '/src/images/favicon/apple-icon-120x120.png">
            <link rel="apple-touch-icon" sizes="144x144" href="' . SITE_TEMPLATE_PATH . '/src/images/favicon/apple-icon-144x144.png">
            <link rel="apple-touch-icon" sizes="152x152" href="' . SITE_TEMPLATE_PATH . '/src/images/favicon/apple-icon-152x152.png">
            <link rel="apple-touch-icon" sizes="180x180" href="' . SITE_TEMPLATE_PATH . '/src/images/favicon/apple-icon-180x180.png">
            <link rel="icon" type="image/png" sizes="192x192"  href="' . SITE_TEMPLATE_PATH . '/src/images/favicon/android-icon-192x192.png">
            <link rel="icon" type="image/png" sizes="32x32" href="' . SITE_TEMPLATE_PATH . '/src/images/favicon/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="96x96" href="' . SITE_TEMPLATE_PATH . '/src/images/favicon/favicon-96x96.png">
            <link rel="icon" type="image/png" sizes="16x16" href="' . SITE_TEMPLATE_PATH . '/src/images/favicon/favicon-16x16.png">
            <meta name="msapplication-TileImage" content="' . SITE_TEMPLATE_PATH . '/src/images/favicon/ms-icon-144x144.png">
            <link rel="icon" href="/favicon.ico" type="image/x-icon">
        ');
        Asset::getInstance()->addString('<link rel="manifest" href="/manifest.json">');
        Asset::getInstance()->addString('<script>BX.message(' . CUtil::PhpToJSObject(Loc::loadLanguageFile(__FILE__)) . ')</script>');
        Asset::getInstance()->addJs("https://www.google.com/recaptcha/api.js?render=" . Option::get(NK_MODULE_NAME, "GRC_PUBLIC"));
        Asset::getInstance()->addString("
        <script>
            if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) { window.location = 'microsoft-edge:' + window.location; setTimeout(function () { window.location = 'https://go.microsoft.com/fwlink/?linkid=2135547'; }, 0); }
                const reCAPTCHASiteKey = '" . Option::get(NK_MODULE_NAME, "GRC_PUBLIC") . "';
                const ymApiKey = '" . Option::get(NK_MODULE_NAME, "YM_KEY") . "';
                const maxFileSize = " . Option::get(NK_MODULE_NAME, "MAX_FILE_SIZE") . ";
        </script>
    ");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/src/libs/fancybox/style.css");
        Asset::getInstance()->addCss(SITE_TEMPLATE_PATH . "/src/libs/swiper/style.css");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/src/libs/fancybox/script.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH . "/src/libs/swiper/script.js");
        ?>
    </head>

<body>
<? $APPLICATION->ShowPanel(); ?>
<div class="wrapper">
    <header class="header">
        <div id="header">
            <div class="header__top">
                <div class="container header__top-wrapper header__wrapper">
                    <a href="<?= SITE_DIR; ?>" class="logo header__logo">
                        <picture>
                            <source media="(max-width: 910px)"
                                    srcset="<?= SITE_TEMPLATE_PATH; ?>/src/images/logo-min.svg">
                            <img src="<?= SITE_TEMPLATE_PATH; ?>/src/images/logo.svg" alt="<?= SITE_NAME; ?>">
                        </picture>
                    </a>
                    <button class="button button--link button--link-orange button--medium header__fastpay fastpay">
                        <i class="icon icon--check-form"></i>
                        <?= Loc::getMessage("DK_FASTPAY_BUTTON"); ?>
                    </button>
                    <button class="button button--orange button--long header__top-recall modal-trigger"
                            data-src="#recall">
                        <?= Loc::getMessage("DK_RECALL_BUTTON"); ?>
                    </button>
                    <div class="header__tools header__tools--auto">
                        <? $arSocNet = $APPLICATION->IncludeComponent(
                            "dk:socnet",
                            "",
                            [
                                "CACHE_TIME" => "36000000",
                                "CACHE_TYPE" => "A"
                            ]
                        ); ?>
                    </div>
                    <div class="header__mobile">
                        <? $APPLICATION->IncludeComponent(
                            "dk:minicart",
                            "",
                            [
                                "CACHE_TIME" => "36000000",
                                "CACHE_TYPE" => "A",
                                "MOBILE" => "Y"
                            ]
                        ); ?>
                        <a href="tel:<?= Main::getPhone(Option::get(NK_MODULE_NAME, "PHONE")); ?>"
                           class="header__phone main-phone">
                            <span class="header__phone-icon"></span>
                        </a>
                        <button class="button header__burger mmo">
                            <i class="icon icon--burger"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="header__bottom">
                <div class="container header__bottom-wrapper header__wrapper">
                    <button class="button header__burger mmo">
                        <i class="icon icon--burger"></i>
                    </button>
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "",
                        MENU_PARAMS
                    ); ?>
                    <div class="header__tools">
                        <a href="tel:<?= Main::getPhone(Option::get(NK_MODULE_NAME, "PHONE")); ?>"
                           class="header__phone main-phone">
                            <span class="header__phone-icon"></span>
                            <span class="header__phone-text"><?= Option::get(NK_MODULE_NAME, "PHONE"); ?></span>
                        </a>
                        <button class="button button--orange button--long header__bottom-recall modal-trigger"
                                data-src="#recall">
                            <?= Loc::getMessage("DK_RECALL_BUTTON"); ?>
                        </button>
                        <? $APPLICATION->IncludeComponent(
                            "arturgolubev:search.title",
                            ".default",
                            [
                                "ANIMATE_HINTS_SPEED" => "1",
                                "CATEGORY_0" => [
                                    0 => "iblock_catalog",
                                ],
                                "CATEGORY_0_TITLE" => "",
                                "CATEGORY_0_iblock_catalog" => [
                                    0 => "2",
                                ],
                                "CHECK_DATES" => "Y",
                                "CONTAINER_ID" => "smart-title-search",
                                "FILTER_NAME" => "",
                                "INPUT_ID" => "smart-title-search-input",
                                "INPUT_PLACEHOLDER" => "Поиск...",
                                "NUM_CATEGORIES" => "1",
                                "ORDER" => "rank",
                                "PAGE" => "/search/",
                                "PREVIEW_HEIGHT_NEW" => "75",
                                "PREVIEW_WIDTH_NEW" => "75",
                                "SHOW_HISTORY" => "N",
                                "SHOW_INPUT" => "Y",
                                "SHOW_LOADING_ANIMATE" => "Y",
                                "SHOW_PREVIEW" => "Y",
                                "SHOW_PREVIEW_TEXT" => "N",
                                "TOP_COUNT" => "15",
                                "USE_LANGUAGE_GUESS" => "Y",
                                "VOICE_INPUT" => "Y",
                                "COMPONENT_TEMPLATE" => ".default",
                                "SHOW_HISTORY_POPUP" => "N"
                            ],
                            false
                        ); ?>
                        <? $APPLICATION->IncludeComponent(
                            "dk:minicart",
                            "",
                            [
                                "CACHE_TIME" => "36000000",
                                "CACHE_TYPE" => "A"
                            ]
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
        <? if ($APPLICATION->GetCurPage() !== "/") : ?>
            <div class="breadcrumbs container" id="navigation">
                <? $APPLICATION->IncludeComponent("bitrix:breadcrumb", "", []); ?>
            </div>
        <? endif; ?>
        <? if (!$IS_MAIN_PAGE && !$IS_CATALOG_PAGE) : ?>
            <h1 class="container title title--h1 title--page title--min-bottom" id="pagetitle"
                data-page-loader-start><? $APPLICATION->ShowTitle("h1"); ?></h1>
        <? else: ?>
            <? $APPLICATION->ShowViewContent("CATALOG_TITLE"); ?>
        <? endif; ?>
        <? $APPLICATION->ShowViewContent("PAGE_DESCRIPTION"); ?>
    </header>
    <main>
<? if (!$IS_MAIN_PAGE && !$IS_CATALOG_PAGE) : ?>
    <section class="container content-blocks section section--pt0">
<? endif; ?>
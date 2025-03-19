<?php

namespace DK\NK\Handler\Main;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\UI\Extension;
use DK\NK\Cart;

class OnBeforeProlog
{

    public static function run(): void
    {
        Cart::initUserStatus();
        self::initTinyMCE();
    }

    private static function initTinyMCE(): void
    {
        global $USER;
        if (Application::getInstance()->getContext()->getRequest()->isAjaxRequest()) return;
        if (isset($GLOBALS["USER"]) && is_object($USER) && $USER->IsAuthorized() && !isset($_REQUEST["bx_hit_hash"])) {
            $tinyMCEAPI = Option::get(NK_MODULE_NAME, "TINYMCE_KEY");
            Asset::getInstance()->addString("<script>const tinyMceAPI = \"". $tinyMCEAPI ."\"</script>");
            Extension::load("dk.tools.tinymce");
        }

    }

}
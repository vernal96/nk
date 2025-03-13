<?php

namespace DK\NK\Handler\Main;

use Bitrix\Main\Config\Option;
use CHTTP;

class OnProlog
{

    public static function run(): void
    {
    }

    private static function siteStopped(): void
    {
        global $APPLICATION, $USER;
        if ($APPLICATION->GetCurPage(true) !== "/bitrix/admin/index.php"
            && Option::get("main", "site_stopped", "N") === "Y"
            && !$USER->CanDoFileOperation("edit_other_settings", [])
        ) {
            CHTTP::SetStatus("503 Service Unavailable");
            include $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/site_closed.php';
            die;
        }
    }

}
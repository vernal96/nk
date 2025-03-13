<?php

namespace DK\NK\Handler\Iblock;

use DK\NK\Helper\Main;

class OnAfterIBlockElementDelete
{

    public static function run($arParams): void
    {
        if ($arParams["IBLOCK_ID"] == IBLOCK_CATALOG) {
            Main::setTimeLastUpdate();
        }
    }

}
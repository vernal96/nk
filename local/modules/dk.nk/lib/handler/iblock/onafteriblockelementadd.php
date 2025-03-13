<?php

namespace DK\NK\Handler\Iblock;

use DK\NK\Helper\Iblock;
use DK\NK\Helper\Main;

class OnAfterIBlockElementAdd
{

    public static function run($arParams): void
    {

        if ($arParams["IBLOCK_ID"] == IBLOCK_CATALOG) {
            Main::setTimeLastUpdate();
        }
        else if ($arParams["IBLOCK_ID"] == IBLOCK_MARKET) {
            Iblock::setMarketInfo($arParams["ID"]);
        }
    }

}
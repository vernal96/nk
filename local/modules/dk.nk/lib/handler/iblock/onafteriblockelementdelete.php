<?php

namespace DK\NK\Handler\Iblock;

use DK\NK\Helper\Main;

class OnAfterIBlockElementDelete
{

    public static function run($arParams): void
    {
        Main::setTimeLastUpdate();
    }

}
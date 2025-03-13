<?php

namespace DK\NK\Handler\Main;

use DK\NK\Helper\Main;
use Exception;

class OnBeforeUserAdd
{

    public static function run(&$arParams): bool
    {
        return self::setMobilePhoneNumber($arParams);
    }

    private static function setMobilePhoneNumber(&$arParams): bool {
        global $APPLICATION;
        if ($arParams["PERSONAL_MOBILE"]) {
            try {
                $arParams["PERSONAL_MOBILE"] = Main::setFormatPhone($arParams["PERSONAL_MOBILE"]);
                return true;
            } catch (Exception $e) {
                $APPLICATION->ThrowException($e->getMessage());
                return false;
            }
        }
        return true;
    }

}
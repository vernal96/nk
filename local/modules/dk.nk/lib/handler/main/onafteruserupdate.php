<?php

namespace DK\NK\Handler\Main;

use Bitrix\Main\Application;

class OnAfterUserUpdate
{

    public static function run($arParams): void
    {
        Application::getInstance()->getTaggedCache()->clearByTag("user_$arParams[ID]");
    }

}
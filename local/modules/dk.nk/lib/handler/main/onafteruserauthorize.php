<?php

namespace DK\NK\Handler\Main;

use Bitrix\Main\Application;
use CUser;

class OnAfterUserAuthorize
{

    public static function run($arParams): void
    {
        $session = Application::getInstance()->getSession();
        $user = CUser::GetByID($arParams["user_fields"]["ID"])->Fetch();
        $userStatus = $user["UF_STATUS"] ?: DEFAULT_PRICE_STATUS;
        $session->set("USER_STATUS", $userStatus);
    }

}
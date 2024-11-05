<?php

namespace DK\NK\BX24\Handler;


use Bitrix\Main\Application;
use DK\NK\BX24\ImBot;
use DK\NK\Services\Bitrix24;

class OnImBotMessageAdd
{

    use ImBot;

    public static function run($data): bool
    {
        Bitrix24::getInstance()->queryBot("imbot.message.add", [
            "CLIENT_ID" => Application::getInstance()->getContext()->getRequest()->get("auth")["application_token"],
            "BOT_ID" => array_shift($data["BOT"])["BOT_ID"],
            "DIALOG_ID" => $data["PARAMS"]["DIALOG_ID"],
            "MESSAGE" => "Выбери команду",
            "KEYBOARD" => self::getKeyBoard()
        ]);
        return true;
    }
}
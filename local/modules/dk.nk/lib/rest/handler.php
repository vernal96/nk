<?php

namespace DK\NK\Rest;

use Bitrix\Main\Application;
use DK\NK\Agents\InvoiceCreator;
use DK\NK\BX24\Handler as BX24Handler;
use DK\NK\BX24\ImBot;
use DK\NK\Services\Bitrix24;

class Handler
{

    use ImBot;

    /** https://n-krep.bitrix24.ru/rest/7/uzwe3kowxx3icxcv/imbot.command.register.json?BOT_ID=69&CLIENT_ID=ro739193r9p4x324cl0ytg067hrl4jmd&COMMAND=FASTINVOICE&EVENT_COMMAND_ADD=https://dev2.n-krep.ru/rest/1/cmsrod0g099ftuna/nk.bot.command/&LANG[0][LANGUAGE_ID]=ru&LANG[1][LANGUAGE_ID]=en&LANG[0][TITLE]=Ускорить загрузку счёта&LANG[1][TITLE]=Speed up account loading */
    public static function bot(array $arParams): bool
    {
        $command = array_shift($arParams["data"]["COMMAND"]);
        switch ($command["COMMAND"]) {
            case "FASTINVOICE":
            {
                InvoiceCreator::run();
                break;
            }
        }
        Bitrix24::getInstance()->queryBot("imbot.message.add", [
            "CLIENT_ID" => Application::getInstance()->getContext()->getRequest()->get("auth")["application_token"],
            "BOT_ID" => $command["BOT_ID"],
            "DIALOG_ID" => $arParams["data"]["PARAMS"]["DIALOG_ID"],
            "MESSAGE" => "Готово!",
            "KEYBOARD" => self::getKeyBoard()
        ]);
        return true;
    }

    public static function run(array $arParams): bool
    {
        switch ($arParams["event"]) {
            case "ONCRMREQUISITEADD":
            {
                return BX24Handler\OnCrmRequisiteAdd::run($arParams["data"]["FIELDS"]["ID"]);
            }
            case "ONIMBOTMESSAGEADD":
            {
                return BX24Handler\OnImBotMessageAdd::run($arParams["data"]);
            }
            default :
            {
                return false;
            }
        }
    }

}
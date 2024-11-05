<?php

namespace DK\NK;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\Web\Json;
use DK\NK\Helper\Main;

class Valid
{

    public static function phone($string): bool
    {
        return !!preg_match("/^(\+\d|8)( \(\d{3}\) \d{3}-\d{2}-\d{2}|\d{10})$/", $string);
    }

    public static function email($string): bool
    {
        return filter_var($string, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function notEmpty(?string $string): bool
    {
        if ($string === null) return false;
        return trim($string) !== "";
    }

    public static function market($id): bool
    {
        return (bool)ElementTable::query()->where("IBLOCK_ID", IBLOCK_MARKET)->where("ID", $id)->fetchObject();
    }

    public static function city($name): bool
    {
        return (bool)Main::getHLObject(HL_DELIVERY_CITIES)::query()->where("UF_NAME", $name)->fetchObject();
    }

    public static function reV3($token): bool
    {
        $httpClient = new HttpClient();
        $httpClient->post("https://www.google.com/recaptcha/api/siteverify", [
            "secret" => Option::get(NK_MODULE_NAME, "GRC_SECRET"),
            "response" => $token,
            "remoteip" => Application::getInstance()->getContext()->getRequest()->getRemoteAddress()
        ]);
        return Json::decode($httpClient->getResult())["success"];
    }

}
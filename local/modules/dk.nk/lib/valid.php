<?php

namespace DK\NK;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\Web\Json;
use DK\NK\Helper\Main;
use Throwable;

class Valid
{

    public static function phone($string): bool
    {
        $cleaned = preg_replace('/\D/', '', $string);
        return !!preg_match('/^([78])?\d{10}$/', $cleaned);
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

    public static function market(?int $id): bool
    {
        if ($id === null) return false;
        try {
            return (bool)ElementTable::query()->where("IBLOCK_ID", IBLOCK_MARKET)->where("ID", $id)->fetchObject();
        } catch (Throwable $exception) {
            addUncaughtExceptionToLog($exception);
            return false;
        }
    }

    public static function city(?int $id): bool
    {
        if ($id === null) return false;
        try {
            return (bool)Main::getHLObject(HL_DELIVERY_CITIES)::query()->where("ID", $id)->fetchObject();
        } catch (Throwable $exception) {
            addUncaughtExceptionToLog($exception);
            return false;
        }
    }

    public static function reV3($token): bool
    {
        $httpClient = new HttpClient();
        $httpClient->post("https://www.google.com/recaptcha/api/siteverify", [
            "secret" => Option::get(NK_MODULE_NAME, "GRC_SECRET"),
            "response" => $token,
            "remoteip" => Application::getInstance()->getContext()->getRequest()->getRemoteAddress()
        ]);
        try {
            return Json::decode($httpClient->getResult())["success"];
        } catch (ArgumentException $exception) {
            addUncaughtExceptionToLog($exception);
            return true;
        }
    }

}
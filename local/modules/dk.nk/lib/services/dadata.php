<?php

namespace DK\NK\Services;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\Web\Json;

class DaData
{

    use Services;

    private static string $apiKey;
    private static string $secretKey;
    private static HttpClient $httpClient;

    private static string $address = "http://suggestions.dadata.ru/suggestions/api/4_1/rs/";

    public static function getRqByInn($inn): array
    {
        $cache = Cache::createInstance();
        if ($cache->initCache(3600, $inn, "company")) {
            $result = $cache->getVars();
        } else {
            $cache->startDataCache();
            self::init();
            self::$httpClient->post(self::$address . "findById/party", Json::encode([
                "query" => $inn,
                "count" => 1
            ]));
            $result = self::parseResult(self::$httpClient->getResult(), "suggestions.0.data");
            $cache->endDataCache($result);
        }
        return $result;
    }

    public static function getAddressInfoByCoord(string $coord): array {
        $cache = Cache::createInstance();
        if ($cache->initCache(CACHE_TIME, $coord, "address")) {
            $result = $cache->getVars();
        } else {
            $cache->startDataCache();
            $arCoord = explode(",", $coord);
            self::init();
            self::$httpClient->post(self::$address . "geolocate/address", Json::encode([
                "lat" => $arCoord[0],
                "lon" => $arCoord[1],
            ]));
            $result = self::parseResult(self::$httpClient->getResult(), "suggestions.0.data");
            $cache->endDataCache($result);
        }
        return $result;
    }

    private static function init(): void
    {
        self::$apiKey = Option::get(NK_MODULE_NAME, "DADATA_PUBLIC");
        self::$secretKey = Option::get(NK_MODULE_NAME, "DADATA_SECRET");
        self::$httpClient = new HttpClient();
        self::$httpClient->setHeaders([
            "Content-Type" => "application/json",
            "Authorization" => "Token " . self::$apiKey
        ]);
    }

}
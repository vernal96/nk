<?php

namespace DK\NK\Services;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Data\Cache;
use DK\NK\ServiceConnectException;

class DaData extends Service
{

    public function __construct()
    {
        parent::__construct('https://suggestions.dadata.ru/suggestions/api/4_1/rs/');
        $this->httpClient->setHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Token ' . Option::get(NK_MODULE_NAME, 'DADATA_PUBLIC')
        ]);
    }

    /**
     * @throws ArgumentException
     * @throws ServiceConnectException
     */
    public function getRqByInn(int $inn): array
    {
        $cache = Cache::createInstance();
        if ($cache->initCache(3600, $inn, 'company')) {
            $result = $cache->getVars();
        } else {
            $cache->startDataCache();
            $requestResult = $this->sendRequest('findById/party', [
                'query' => $inn,
                'count' => 1
            ]);
            $result = $this->parseResultFromJson($requestResult, 'suggestions.0.data');
            $cache->endDataCache($result);
        }
        return $result;
    }

    /**
     * @throws ArgumentException
     * @throws ServiceConnectException
     */
    public function getAddressInfoByCoord(string $coord): array {
        $cache = Cache::createInstance();
        if ($cache->initCache(CACHE_TIME, $coord, "address")) {
            $result = $cache->getVars();
        } else {
            $cache->startDataCache();
            $arCoord = explode(",", $coord);
            $requestResult = $this->sendRequest('geolocate/address', [
                "lat" => $arCoord[0],
                "lon" => $arCoord[1],
            ]);
            $result = $this->parseResultFromJson($requestResult, 'suggestions.0.data');
            $cache->endDataCache($result);
        }
        return $result;
    }

}
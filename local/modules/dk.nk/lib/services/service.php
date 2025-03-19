<?php

namespace DK\NK\Services;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\Web\Json;
use DK\NK\ServiceConnectException;

abstract class Service
{

    private string $address;
    protected HttpClient $httpClient;

    public function __construct(string $address) {
        $this->address = $address;
        $this->httpClient = new HttpClient();
    }

    /**
     * @throws ArgumentException
     * @throws ServiceConnectException
     */
    protected function sendRequest(string $method, array $params = []): string
    {
        $this->httpClient->post($this->address . $method, Json::encode($params));
        if ($this->httpClient->getStatus() >= 200 && $this->httpClient->getStatus() < 300) {
            return $this->httpClient->getResult();
        } else {
            throw new ServiceConnectException($this->httpClient->getResult());
        }
    }

    /**
     * @throws ArgumentException
     */
    protected function parseResultFromJson(string $result, string $resultSelect = null): array
    {
        $result = Json::decode($result);
        if ($resultSelect) {
            $levels = explode(".", $resultSelect);
            foreach ($levels as $level) {
                if (isset($result[$level])) {
                    $result = $result[$level];
                } else {
                    break;
                }
            }
        }
        return $result;
    }
}
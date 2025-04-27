<?php

namespace DK\NK\Services;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Config\Option;
use DK\NK\ServiceConnectException;
use DK\NK\Helper;

class Yandex extends Service
{

    private string $accessToken;

    public function __construct()
    {
        parent::__construct('https://api.webmaster.yandex.net/v4/');
        $this->accessToken = Option::get(NK_MODULE_NAME, 'YANDEX_APP_TOKEN');
        $this->httpClient->setHeader('Authorization', "OAuth $this->accessToken");
        $this->httpClient->setHeader('Content-Type', 'application/json');
    }

    /**
     * @throws ArgumentOutOfRangeException
     * @throws ServiceConnectException
     * @throws ArgumentException
     */
    public function getUserId(): int
    {
        $userId = Option::get(NK_MODULE_NAME, 'YANDEX_APP_USER_ID');
        if ($userId) return (int)$userId;
        $requestResult = $this->sendRequest('user', [], 'GET');
        $result = $this->parseResultFromJson($requestResult);
        Option::set(NK_MODULE_NAME, 'YANDEX_APP_USER_ID', $result['user_id']);
        return (int)$result['user_id'];
    }

    /**
     * @throws ArgumentOutOfRangeException
     * @throws ArgumentException
     * @throws ServiceConnectException
     */
    public function uploadFeeds(array $feeds = []): array
    {
        $url = sprintf(
            'user/%d/hosts/%s/feeds/batch/add',
            $this->getUserId(),
            Option::get(NK_MODULE_NAME, 'YANDEX_HOST_ID')
        );
        $params = [];
        foreach ($feeds as $feed) {
            $params['feeds'][] = [
                'url' => $feed['url'],
                'type' => $feed['type'],
                'regionIds' => Helper\Main::separatorStringToArray(
                    Option::get(NK_MODULE_NAME, 'YANDEX_FEEDS_REGIONS')
                )
            ];
        }
        $requestResult = $this->sendRequest($url, $params);
        return $this->parseResultFromJson($requestResult, 'feeds');
    }

    /**
     * @throws ArgumentOutOfRangeException
     * @throws ArgumentException
     * @throws ServiceConnectException
     */
    public function removeFeeds(array $feeds = []): array {
        $url = sprintf(
            'user/%d/hosts/%s/feeds/batch/remove',
            $this->getUserId(),
            Option::get(NK_MODULE_NAME, 'YANDEX_HOST_ID')
        );
        $params = [];
        foreach ($feeds as $feed) {
            $params['urls'][] = $feed;
        }
        $requestResult = $this->sendRequest($url, $params, 'DELETE');
        return $this->parseResultFromJson($requestResult, 'feeds');
    }
}
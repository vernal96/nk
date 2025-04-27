<?php

namespace DK\NK\Services;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Config\Option;
use DK\NK\Helper;
use DK\NK\ServiceConnectException;
use Throwable;

class Yandex extends Service
{

    private string $accessToken;
    private int $userId;
    private string $hostId;

    public function __construct()
    {
        parent::__construct('https://api.webmaster.yandex.net/v4/');
        $this->accessToken = Option::get(NK_MODULE_NAME, 'YANDEX_APP_TOKEN');
        $this->httpClient->setHeader('Authorization', "OAuth $this->accessToken");
        $this->httpClient->setHeader('Content-Type', 'application/json');
        $this->userId = $this->getUserId();
        $this->hostId = Option::get(NK_MODULE_NAME, 'YANDEX_HOST_ID');
    }

    private function getUserId(): int
    {
        try {
            $userId = Option::get(NK_MODULE_NAME, 'YANDEX_APP_USER_ID');
            if ($userId) return (int)$userId;
            $requestResult = $this->sendRequest('user', [], 'GET');
            $result = $this->parseResultFromJson($requestResult);
            Option::set(NK_MODULE_NAME, 'YANDEX_APP_USER_ID', $result['user_id']);
            return (int)$result['user_id'];
        } catch (Throwable $e) {
            addUncaughtExceptionToLog($e);
            return 0;
        }
    }

    private function getFeedsUrl(string $method): string {
        return sprintf(
            'user/%d/hosts/%s/feeds/%s',
            $this->userId,
            $this->hostId,
            $method
        );
    }

    /**
     * @throws ArgumentOutOfRangeException
     * @throws ArgumentException
     * @throws ServiceConnectException
     */
    public function uploadFeeds(array $feeds = []): array
    {
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
        $requestResult = $this->sendRequest($this->getFeedsUrl('batch/add'), $params);
        return $this->parseResultFromJson($requestResult, 'feeds');
    }

    /**
     * @throws ArgumentOutOfRangeException
     * @throws ArgumentException
     * @throws ServiceConnectException
     */
    public function removeFeeds(array $feeds = []): array {
        $params = [];
        foreach ($feeds as $feed) {
            $params['urls'][] = $feed;
        }
        $requestResult = $this->sendRequest($this->getFeedsUrl('batch/remove'), $params, 'DELETE');
        return $this->parseResultFromJson($requestResult, 'feeds');
    }

    /**
     * @throws ArgumentOutOfRangeException
     * @throws ArgumentException
     * @throws ServiceConnectException
     */
    public function getFeeds(): array
    {
        $requestResult = $this->sendRequest($this->getFeedsUrl('list'), [], 'GET');
        return $this->parseResultFromJson($requestResult, 'feeds');
    }
}
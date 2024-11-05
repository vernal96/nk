<?php

namespace DK\NK\Services;

use Bitrix\Main\Web\Json;

trait Services
{

    private static function parseResult(string $result, string $resultSelect = null): array
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
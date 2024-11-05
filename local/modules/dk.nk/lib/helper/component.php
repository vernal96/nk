<?php

namespace DK\NK\Helper;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Data\Cache;

class Component
{
    public static function getSocnet(): array
    {
        $arNames = ["TELEGRAM", "WHATSAPP", "VK", "VIBER"];
        $cache = Cache::createInstance();
        $taggedCache = Application::getInstance()->getTaggedCache();
        $cacheUnique = "dk.socnet";
        $cachePath = SITE_ID . "/dk/socnet";
        $result = [];

        if ($cache->initCache(CACHE_TIME, $cacheUnique, $cachePath)) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $taggedCache->startTagCache($cachePath);

            $options = Option::getForModule(NK_MODULE_NAME);
            $arSocNet = array_filter(
                $options,
                fn($value, $name) => in_array($name, $arNames) && $value,
                ARRAY_FILTER_USE_BOTH
            );
            $result = array_combine(
                array_map(fn($name) => mb_strtolower($name), array_keys($arSocNet)),
                array_values($arSocNet)
            );

            $taggedCache->registerTag("socnet");
            $taggedCache->endTagCache();
            $cache->endDataCache($result);
        }
        return $result;
    }
}
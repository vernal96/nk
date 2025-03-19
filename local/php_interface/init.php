<?php

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Web\Json;

//const VUEJS_DEBUG = true;

const CACHE_TIME = 36000000;
const IBLOCK_FS = 1;
const IBLOCK_CATALOG = 2;
const IBLOCK_SIMPLE = 3;
const IBLOCK_MARKET = 4;
const HL_SIZES = 1;
const HL_ABOUT_PROPERTIES = 2;
const HL_DELIVERY_CITIES = 3;
const HL_DELIVERY_INFO = 4;

const NK_MODULE_NAME = "dk.nk";

const EMAIL_TEMPLATE_PATH = "/local/templates/nk.mail/";
const DEFAULT_PRICE_STATUS = 1;

function printR(mixed $array): void
{
    echo "<pre style='background: #00000012; padding: 1em;'>" . print_r($array, true) . "</pre>";
}

function logToFile(mixed $data, bool $append = false): void
{
    try {
        $data = Json::encode($data, JSON_PRETTY_PRINT);
    } catch (ArgumentException $e) {
        $data = $e->getMessage();
    }
    file_put_contents(
        '/var/www/u1364127/data/www/logs/debug.json', $data, $append ? FILE_APPEND : 0
    );
}

function addUncaughtExceptionToLog(Throwable $exception): void
{
    CEventLog::Log(
        CEventLog::SEVERITY_ERROR,
        'An error has occurred on the website',
        NK_MODULE_NAME,
        'Uncaught Exception',
        preg_replace('/\n/', '<br>', (string)$exception)
    );
}

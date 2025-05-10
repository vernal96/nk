<?php

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Web\Json;

/** @noinspection PhpUnused */
function printR(mixed $array): void
{
    echo "<pre style='background: #00000012; padding: 1em;'>" . print_r($array, true) . "</pre>";
}

/** @noinspection PhpUnused */
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

/** @noinspection PhpUnused */
function addUncaughtExceptionToLog(Throwable $exception): void
{
    CEventLog::Log(
        CEventLog::SEVERITY_ERROR,
        'UNCAUGHT_EXCEPTION',
        NK_MODULE_NAME,
        'EXCEPTION',
        preg_replace('/\n/', '<br>', (string)$exception)
    );
}
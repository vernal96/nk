<?php

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;

try {
    Loader::registerAutoLoadClasses(
        'dk.nk',
        [
            'dk\\nk\\fieldsexception' => 'lib/exception.php',
            'dk\\nk\\serviceconnectexception' => 'lib/exception.php',
        ]
    );
} catch (LoaderException $e) {
    addUncaughtExceptionToLog($e);
}
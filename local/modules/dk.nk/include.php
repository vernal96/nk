<?php

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'dk.nk',
    [
        'dk\\nk\\fieldsexception' => 'lib/exception.php',
        'dk\\nk\\serviceconnectexception' => 'lib/exception.php',
    ]
);
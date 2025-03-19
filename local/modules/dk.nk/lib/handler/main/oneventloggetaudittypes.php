<?php

namespace DK\NK\Handler\Main;

class OnEventLogGetAuditTypes
{

    public static function run(): array
    {
        return [
            'UNCAUGHT_EXCEPTION' => '[UNCAUGHT_EXCEPTION] Не перехваченное исключение'
        ];
    }

}
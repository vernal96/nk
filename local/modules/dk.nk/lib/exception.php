<?php

namespace DK\NK;

use Bitrix\Main\SystemException;
use Throwable;

class FieldsException extends SystemException
{
    public function __construct(array $fields, int $code = 0, Throwable $previous = null)
    {
        $message = serialize($fields);
        parent::__construct($message, $code, $previous);
    }

    public function getFields(): array {
        return unserialize($this->message);
    }

}
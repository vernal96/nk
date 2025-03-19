<?php

namespace Sprint\Migration;


use Bitrix\Main\EventManager;

class dk_nk_20250319141335 extends Version
{
    protected $author = "admin";

    protected $description = "Добавляет обработчик OnEventLogGetAuditTypes";

    protected $moduleVersion = "4.18.2";

    private array $eventFields = [
        'main',
        'OnEventLogGetAuditTypes',
        'dk.nk',
        'DK\NK\Handler\Main\OnEventLogGetAuditTypes',
        'run'
    ];

    public function up(): void
    {
        EventManager::getInstance()->registerEventHandler(...$this->eventFields);
    }

    public function down(): void
    {
        EventManager::getInstance()->unRegisterEventHandler(...$this->eventFields);
    }
}

<?php

namespace Sprint\Migration;


use Bitrix\Main\EventManager;

class dk_nk_20250430190741 extends Version
{
    protected $author = "admin";

    protected $description = "Добавляет обработчик OnEndBufferContent";

    protected $moduleVersion = "5.0.0";

    private array $eventFields = [
        'main',
        'OnEndBufferContent',
        'dk.nk',
        'DK\NK\Handler\Main\OnEndBufferContent',
        'run',
        1000
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

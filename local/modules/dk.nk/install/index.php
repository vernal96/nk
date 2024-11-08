<?php

use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;

class dk_nk extends CModule
{

    private static array $events = [
        ["Main", "OnFileSave"],
        ["Main", "OnBeforeProlog"],
        ["Main", "OnEpilog"],
        ["Main", "OnProlog"],
        ["Main", "OnAfterUserAuthorize"],
        ["Main", "OnAfterUserUpdate"],
        ["Main", "OnBeforeUserAdd"],
        ["Main", "OnBeforeUserUpdate"],
        ["Rest", "OnRestServiceBuildDescription"],
        ["Iblock", "OnAfterIBlockElementAdd"],
        ["Iblock", "OnAfterIBlockElementDelete"],
        ["Iblock", "OnAfterIBlockElementUpdate"],
    ];

    public function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . "/version.php");
        if (!$arModuleVersion) return;

        $this->MODULE_ID = str_replace("_", ".", self::class);
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage("NK_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("NK_MODULE_DESCRIPTION");
        $this->PARTNER_NAME = Loc::getMessage("NK_MODULE_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("NK_MODULE_PARTNER_URL");
        $this->setEventsData();
    }

    private function setEventsData(): void
    {
        foreach (self::$events as &$event) {
            $event = [
                strtolower($event[0]),
                $event[1],
                $this->MODULE_ID,
                "DK\\NK\\Handler\\" . ucfirst($event[0]) . "\\" . $event[1],
                "run"
            ];
        }
    }

    public function DoInstall(): void
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->InstallEvents();
    }

    public function InstallEvents(): void
    {
        foreach (self::$events as $eventData) {
            EventManager::getInstance()->registerEventHandler(...$eventData);
        }
    }

    public function DoUninstall(): void
    {
        $this->UnInstallEvents();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function UnInstallEvents(): void
    {
        foreach (self::$events as $eventData) {
            EventManager::getInstance()->unRegisterEventHandler(...$eventData);
        }
    }

}
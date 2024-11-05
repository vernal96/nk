<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\Contract\Controllerable;
use DK\NK\Helper\Catalog;
use DK\NK\Helper\Component;
use DK\NK\Helper\Main;

class DKMMComponent extends CBitrixComponent implements Controllerable
{


    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent(): void
    {
        $this->includeComponentTemplate();
    }

    public function getTreeAction(): array
    {
        return Catalog::getCompactTree();
    }

    public function getMMAction(): array
    {
        $phone = Option::get(NK_MODULE_NAME, "PHONE");
        return [
            "children" => $this->getMenu(),
            "socnet" => Component::getSocnet(),
            "login" => $this->getLogin(),
            "phone" => [
                "format" => $phone,
                "link" => Main::getPhone($phone)
            ]
        ];
    }

    private function getLogin(): string {
        global $APPLICATION;
        ob_start();
        $APPLICATION->IncludeComponent("dk:login", "", [], $this);
        return ob_get_clean();
    }

    public function getMenu(): array
    {
        global $APPLICATION;
        $arResult = $APPLICATION->IncludeComponent(
            "bitrix:menu",
            "",
            MENU_PARAMS,
            null,
            [],
            true
        );
        $arNewMenu = [];
        foreach ($arResult as $arItem) {
            $item = [
                "title" => $arItem["TEXT"],
                "url" => $arItem["LINK"],
                "icon" => $arItem["PARAMS"]["ICON"],
                "images" => [
                    "picture"
                ],
                "isCatalog" => $arItem["PARAMS"]["CATALOG"] == "Y",
                "children" => []
            ];
            if ($arItem["DEPTH_LEVEL"] == 1) {
                $arNewMenu[] = $item;
            } elseif ($arItem["DEPTH_LEVEL"] == 2) {
                $arNewMenu[count($arNewMenu) - 1]["children"][] = $item;
            }
        }
        return $arNewMenu;

    }

    public function getSocNetAction(): array
    {
        return $this->arParams;
    }

    public function configureActions(): array
    {
        return [
            "getTree" => [
                "prefilters" => [new Csrf()]
            ],
            "getMM" => [
                "prefilters" => [new Csrf()]
            ]
        ];
    }
}
<?php

use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\UI\PageNavigation;
use DK\NK\Helper\Catalog;
use DK\NK\Helper\Iblock;
use DK\NK\Helper\Main;

class NkCatalogProducts extends CBitrixComponent
{

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent(): void
    {
        if (preg_match("/\D/", $this->arParams["PAGE"])) {
            Tools::process404();
        }

        $taggedCache = Application::getInstance()->getTaggedCache();
        if ($this->startResultCache(false, [$this->arParams["SECTION_ID"], $this->arParams["PAGE"]])) {
            $taggedCache->startTagCache($this->getCachePath());

            $this->arResult = Catalog::getProducts(
                $this->arParams["SECTION_ID"],
                $this->arParams["PRODUCT_COUNT"],
                $this->arParams["PAGE"]
            );

            if ($this->arResult === null) {
                $this->abortResultCache();
                $taggedCache->abortTagCache();
                Tools::process404();
            }

            $taggedCache->registerTag("iblock_id_" . $this->arParams["IBLOCK_ID"]);
            $taggedCache->endTagCache();
            $this->endResultCache();
        }
        $this->includeComponentTemplate();
    }


}
<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use DK\NK\Helper\Iblock;
use DK\NK\Helper\Main;

class NkCatalogRecommend extends CBitrixComponent
{

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent(): void
    {
        $taggedCache = Application::getInstance()->getTaggedCache();
        if ($this->startResultCache(false, [$this->arParams["ELEMENT_ID"]])) {
            $taggedCache->startTagCache($this->getCachePath());

            $arFilter = [
                "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
                "ACTIVE" => "Y",
            ];

            $arOrder = [
                "SORT" => "ASC"
            ];

            $arNavStartParams = [
                "nTopCount" => $this->arParams["RAND_LIMIT"]
            ];

            if ($this->arParams["IDS"]) {
                $arFilter["ID"] = $this->arParams["IDS"];
            } else {
                $arOrder["RAND"] = "ASC";
            }
            $rsElement = CIBlockElement::GetList($arOrder, $arFilter, false, $arNavStartParams);
            while ($element = $rsElement->GetNextElement(true, false)) {
                $dataElement = $element->GetFields();
                $dataElement["PROPERTIES"] = $element->GetProperties();
                $dataElement = Iblock::setResultFields($dataElement);
                $dataElement["NO_PHOTO"] = Main::getFileIdBySrc(Option::get(NK_MODULE_NAME, "NOPHOTO"));
                $this->arResult[] = $dataElement;
            }

            $taggedCache->registerTag("iblock_id_" . $this->arParams["IBLOCK_ID"]);
            $taggedCache->endTagCache();

            $this->setResultCacheKeys([]);
            $this->includeComponentTemplate();
        }
    }
}
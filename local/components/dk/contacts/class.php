<?php

use Bitrix\Main\Application;
use DK\NK\Helper\Iblock;


class DKContactsComponent extends CBitrixComponent
{

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent(): void
    {
        if ($this->startResultCache()) {

            Application::getInstance()->getTaggedCache()->startTagCache($this->getCachePath());

            $markets = CIBlockElement::GetList(
                ["SORT" => "ASC", "ID" => "ASC"],
                [
                    "IBLOCK_ID" => IBLOCK_MARKET,
                    "ACTIVE" => "Y",
                ],
                false,
                false,
                [
                    "NAME", "IBLOCK_ID", "ID"
                ]
            );
            $this->arResult["MARKETS"] = [];
            $this->arResult["LINK_ADD"] = [];
            while ($market = $markets->GetNextElement()) {
                $arMarket = $market->fields;
                $arMarket["PROPERTIES"] = $market->GetProperties();
                $this->arResult["MARKETS"][] = Iblock::setResultFields($arMarket, $this->arResult["LINK_ADD"]);
            }
            Application::getInstance()->getTaggedCache()->registerTag("iblock_id_" . IBLOCK_MARKET);
            Application::getInstance()->getTaggedCache()->endTagCache();
            $this->setResultCacheKeys([]);
            $this->includeComponentTemplate();
        }
    }

}
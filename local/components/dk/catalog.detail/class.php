<?php

use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\Contract\Controllerable;
use DK\NK\Helper\Catalog;
use DK\NK\Helper\Iblock;
use DK\NK\Helper\Main;

class DKCatalogDetail extends CBitrixComponent implements Controllerable
{

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent(): void
    {
        global $APPLICATION;
        $taggedCache = Application::getInstance()->getTaggedCache();
        if ($this->startResultCache(false, $this->arParams["CODE"])) {
            $taggedCache->startTagCache($this->getCachePath());

            $rsElement = CIBlockElement::GetList([], [
                "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
                "ACTIVE" => "Y",
                "CODE" => $this->arParams["CODE"],
            ]);

            $objElement = $rsElement->getNextElement(true, false);

            if (!$objElement) {
                Tools::process404();
                $this->abortResultCache();
                $taggedCache->abortTagCache();
                return;
            }
            $arFields = $objElement->GetFields();
            $noImage = Main::getFileIdBySrc(Option::get("nk.main", "NOPHOTO"));
            $arFields["DETAIL_PICTURE"] = ($arFields["DETAIL_PICTURE"] ?: $arFields["PREVIEW_PICTURE"]) ?: $noImage;
            $arFields["PREVIEW_PICTURE"] = ($arFields["PREVIEW_PICTURE"] ?: $arFields["DETAIL_PICTURE"]) ?: $noImage;

            $this->arResult = Iblock::setResultFields($arFields);
            $this->arResult["PROPERTIES"] = $objElement->GetProperties();

            $this->arResult["PRICES"] = Catalog::getProductPrices($arFields["ID"]);

            $this->arResult["RECOMMEND"] = $this->arResult["PROPERTIES"]["RECOMMEND"]["VALUE"];
            $taggedCache->registerTag("iblock_id_" . $this->arParams["IBLOCK_ID"]);
            $taggedCache->endTagCache();
            $this->endResultCache();
        }
        $this->includeComponentTemplate();
        if ($iprop = $this->arResult["IPROPERTY_VALUES"]) {
            $APPLICATION->SetPageProperty("TITLE", $iprop["ELEMENT_META_TITLE"]);
            $APPLICATION->SetPageProperty("description", $iprop["ELEMENT_META_DESCRIPTION"]);
            $APPLICATION->SetPageProperty("keywords", $iprop["ELEMENT_META_KEYWORDS"]);
        }

        $APPLICATION->AddChainItem($this->arResult["NAME"], $this->arResult["DETAIL_PAGE_URL"]);
    }

    public function configureActions(): array
    {
        return [
            'getPriceTable' => [
                'prefilters' => [
                    new Csrf()
                ]
            ]
        ];
    }
}
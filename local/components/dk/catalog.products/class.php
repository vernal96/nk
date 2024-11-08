<?php

use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
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
        $sectionId = $this->arParams["SECTION_ID"];
        $pageNum = Application::getInstance()->getContext()->getRequest()->get("PAGEN_1");
        if ($pageNum === null) {
            $pageNum = "1";
        }
        if (!is_numeric($pageNum) && $this->arParams["SHOW_404"]) {
            Tools::process404();
            return;
        }
        $taggedCache = Application::getInstance()->getTaggedCache();
        if ($this->startResultCache(false, [$sectionId, $pageNum])) {
            $taggedCache->startTagCache($this->getCachePath());
            $this->arResult["ADD_LINK"] = [];

            $arSort = Catalog::$arProductOrder;
            $arFilter = [
                "ACTIVE" => "Y",
                "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
                "SECTION_ID" => $sectionId,
                "INCLUDE_SUBSECTIONS" => "Y"
            ];
            $arNavStartParams = [
                "nPageSize" => $this->arParams["PRODUCT_COUNT"],
                "bShowAll" => $this->arParams["PAGER_SHOW_ALL"] == "Y"
            ];
            $elements = [];
            $rsElements = CIBlockElement::GetList($arSort, $arFilter, false, $arNavStartParams);
//            if ($rsElements->NavPageCount < $pageNum && $this->arParams["SHOW_404"]) {
//                Tools::process404();
//                $this->abortResultCache();
//                $taggedCache->abortTagCache();
//                return;
//            }
            while ($arElement = $rsElements->GetNextElement(true, false)) {
                $element = $arElement->fields;
                $element["PROPERTIES"] = $arElement->GetProperties();
                $element["NO_PHOTO"] = Main::getFileIdBySrc(Option::get(NK_MODULE_NAME, "NOPHOTO"));
                $element = Iblock::setResultFields($element, $this->arResult["ADD_LINK"]);
                $elements[] = $element;
            }
            $this->arResult["ELEMENTS"] = $elements;
            $this->arResult["NAV_STRING"] = $rsElements->GetPageNavString(
                $this->arParams["PAGER_TITLE"],
                $this->arParams["PAGER_TEMPLATE"],
                $this->arParams["PAGER_SHOW_ALWAYS"] == "Y"
            );


            $taggedCache->registerTag("iblock_id_" . $this->arParams["IBLOCK_ID"]);
            $taggedCache->endTagCache();
            $this->endResultCache();
        }
        $this->includeComponentTemplate();
    }

}
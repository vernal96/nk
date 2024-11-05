<?php

use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Application;
use DK\NK\Helper\Iblock;

class DKCatalogTree extends CBitrixComponent
{

    private static int $activeSectionId = 0;

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent(): void
    {
        global $APPLICATION;

        $this->arResult = [
            "TREE" => [],
            "ADD_LINK" => [],
            "CHAIN" => [],
            "IPROPERTY_VALUES" => [],
            "SECTION_ID" => 0
        ];

        $taggedCache = Application::getInstance()->getTaggedCache();
        $sectionCode = $this->arParams["SECTION_CODE"];
        $fullTree = $this->arParams["FULL"];
        $depthLevel = $this->arParams["DEPTH_LEVEL"];
        if ($this->startResultCache(false, [$sectionCode, $fullTree, $depthLevel])) {
            $taggedCache->startTagCache($this->getCachePath());

            if ($sectionCode) {
                $activeSection = CIBlockSection::GetList([], ["CODE" => $sectionCode], false, ["ID"])->Fetch();
                if ($activeSection) {
                    self::$activeSectionId = (int)$activeSection["ID"];
                    $this->arResult["SECTION_ID"] = self::$activeSectionId;
                } elseif ($this->arParams["SHOW_404"]) {
                    Tools::process404();
                    $this->abortResultCache();
                    $taggedCache->abortTagCache();
                }
            }

            $arSort = \DK\NK\Helper\Catalog::$arSectionOrder;
            $arFilter = [
                "ACTIVE" => "Y",
                "GLOBAL_ACTIVE" => "Y",
                "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
            ];
            if ($depthLevel !== "0") {
                $arFilter["DEPTH_LEVEL"] = $depthLevel;
            }
            $arSelect = [
                "*", "UF_*"
            ];

            $chains = [];
            $rsChain = CIBlockSection::GetNavChain($this->arParams["IBLOCK_ID"], self::$activeSectionId, ["ID", "NAME", "SECTION_PAGE_URL"]);
            while ($chain = $rsChain->GetNext(true, false)) {
                $chains[] = $chain;
            }
            $chainArray = array_column($chains, "ID");
            $this->arResult["CHAIN"] = array_map(function ($arItem) {
                return [
                    "ID" => $arItem["ID"],
                    "NAME" => $arItem["NAME"],
                    "URL" => $arItem["SECTION_PAGE_URL"]
                ];
            }, $chains);

            $sections = [];
            $rsSection = CIBlockSection::GetList($arSort, $arFilter, false, $arSelect);
            $currentSection = null;
            while ($section = $rsSection->GetNext(true, false)) {
                $section = Iblock::setResultFields($section, $this->arResult["ADD_LINK"], false);
                if (in_array($section["ID"], $chainArray)) {
                    $section["CURRENT"] = true;
                    $currentSection = $section;
                    if ($section["ID"] == self::$activeSectionId) {
                        $this->arResult["IPROPERTY_VALUES"] = $section["IPROPERTY_VALUES"];
                    }
                }
                $sections[] = $section;
            }

            $this->arResult["TREE"] = self::setTree($sections, 0, $fullTree);
            $this->arResult["DESCRIPTION"] = $currentSection["DESCRIPTION"];
            $taggedCache->registerTag("iblock_id_" . $this->arParams["IBLOCK_ID"]);
            $taggedCache->endTagCache();
            $this->setResultCacheKeys(["IPROPERTY_VALUES", "CHAIN", "SECTION_ID", "DESCRIPTION"]);
            $this->includeComponentTemplate();
        }

        if ($iprop = $this->arResult["IPROPERTY_VALUES"]) {
            $APPLICATION->SetPageProperty("TITLE", $iprop["SECTION_META_TITLE"]);
            $APPLICATION->SetPageProperty("h1", $iprop["SECTION_PAGE_TITLE"]);
            $APPLICATION->SetPageProperty("description", $iprop["SECTION_META_DESCRIPTION"]);
            $APPLICATION->SetPageProperty("keywords", $iprop["SECTION_META_KEYWORDS"]);
            $APPLICATION->SetTitle($iprop["SECTION_PAGE_TITLE"]);
        }
        foreach ($this->arResult["CHAIN"] as $arItem) {
            $APPLICATION->AddChainItem($arItem["NAME"], $arItem["URL"]);
        }
    }

    private static function setTree($array, $parent, $fullTree): array
    {
        $result = [];
        $items = array_filter($array, fn($item) => $item["IBLOCK_SECTION_ID"] == $parent);
        if ($items) {
            foreach ($items as &$item) {
                if (!$item["CURRENT"] && $fullTree == "N" && $item["IBLOCK_SECTION_ID"] == 0) {
                    $item["SECTIONS"] = [];
                } else {
                    $item["SECTIONS"] = self::setTree($array, $item["ID"], $fullTree);
                }
            }
            $result = $items;
        }
        return $result;
    }

}
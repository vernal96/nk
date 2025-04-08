<?php

use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Application;
use DK\NK\Helper\Iblock;

class DKCatalogTree extends CBitrixComponent
{

    private static int $activeSectionId = 0;
    private static array $activeSection = [];


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
        if ($this->startResultCache(false, $sectionCode)) {
            $taggedCache->startTagCache(Iblock::getCachePath($this->getName()));

            if ($sectionCode) {
                $activeSection = CIBlockSection::GetList([], [
                    "CODE" => $sectionCode,
                    "GLOBAL_ACTIVE" => "Y"
                ], false, ["ID"])->Fetch();
                if ($activeSection) {
                    self::$activeSectionId = (int)$activeSection["ID"];
                    $this->arResult["SECTION_ID"] = self::$activeSectionId;
                } elseif ($this->arParams["SHOW_404"]) {
                    Tools::process404();
                    $this->abortResultCache();
                    $taggedCache->abortTagCache();
                }
            }

            $tree = \DK\NK\Helper\Catalog::getTree();
            $this->setTree($tree);
            $this->arResult["TREE"] = $tree;

            $chains = [];
            $rsChain = CIBlockSection::GetNavChain($this->arParams["IBLOCK_ID"], self::$activeSectionId, ["ID", "NAME", "SECTION_PAGE_URL"]);
            while ($chain = $rsChain->GetNext(true, false)) {
                $chains[] = $chain;
            }
            $this->arResult["CHAIN"] = array_map(function ($arItem) {
                return [
                    "ID" => $arItem["ID"],
                    "NAME" => $arItem["NAME"],
                    "URL" => $arItem["SECTION_PAGE_URL"]
                ];
            }, $chains);

            if (self::$activeSectionId) {
                $this->arResult["DESCRIPTION"] = self::$activeSection["DESCRIPTION"];
                $this->arResult["IPROPERTY_VALUES"] = self::$activeSection["IPROPERTY_VALUES"];
            }
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

    private function setTree(&$sections): void
    {
        foreach ($sections as &$section) {
            if ($section["ID"] == self::$activeSectionId) {
                $section["CURRENT"] = true;
                self::$activeSection = $section;
            }
            $section["HAVE_CHILDREN"] = !empty($section["CHILDREN"]);
            $this->setTree($section["CHILDREN"]);
            if (!array_filter($section["CHILDREN"], fn($child) => $child["CURRENT"]) && !$section["CURRENT"]) {
                $section["CHILDREN"] = [];
            } else {
                $section["CURRENT"] = true;
            }
        }
    }

}
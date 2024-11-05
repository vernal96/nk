<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use DK\NK\Helper\Main;

class DKAbout extends CBitrixComponent
{

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent(): void
    {
        if ($this->startResultCache()) {
            $yearStart = Option::get(NK_MODULE_NAME, "YEAR_START");
            $yearsCount = date("Y") - $yearStart;
            $this->arResult["YEAR_START"] = $yearStart;
            $this->arResult["YEARS_COUNT"] = $yearsCount . " " . Main::num2word($yearsCount, [
                    Loc::getMessage("ABOUT_YEAR_S1"),
                    Loc::getMessage("ABOUT_YEAR_S2"),
                    Loc::getMessage("ABOUT_YEAR_S3")
                ]);
            $properties = Main::getHLObject(HL_ABOUT_PROPERTIES)::getList([
                "order" => ["UF_SORT" => "ASC"],
                "filter" => ["ID" => $this->arParams["PROPERTIES"]]
            ])->fetchAll();
            $properties = array_map(function ($property) {
                $property["UF_ICO"] = CFile::GetPath($property["UF_ICO"]);
                return $property;
            }, $properties);
            $this->arResult["PROPERTIES"] = $properties;
            $this->arResult["PICTURE"] = [
                "id" => Main::getFileIdBySrc($this->arParams["IMAGE"]),
                "alt" => $this->arParams["IMAGE_ALT"],
                "title" => $this->arParams["IMAGE_TITLE"]
            ];
            $this->setResultCacheKeys([]);
            $this->includeComponentTemplate();
        }
    }

}

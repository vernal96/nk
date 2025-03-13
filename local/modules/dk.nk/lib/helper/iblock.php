<?php

namespace DK\NK\Helper;

use Bitrix\Iblock\Component\Tools;
use Bitrix\Iblock\InheritedProperty\ElementValues;
use Bitrix\Iblock\InheritedProperty\SectionValues;
use Bitrix\Main\Config\Option;
use CFile;
use CIBlock;
use CTextParser;
use DK\NK\Services\DaData;

class Iblock
{
    public static function setResultFields(array $item, ?array &$addLink = null, bool $element = true): array
    {
        $objectValues = $element ? ElementValues::class : SectionValues::class;
        $propValues = new $objectValues($item["IBLOCK_ID"], $item["ID"]);
        $item["IPROPERTY_VALUES"] = $propValues->getValues();
        $item["IBLOCK_SECTION_ID"] = $item["IBLOCK_SECTION_ID"] ?: 0;
        Tools::getFieldImageData(
            $item,
            $element ? ["PREVIEW_PICTURE", "DETAIL_PICTURE"] : ['PICTURE', 'DETAIL_PICTURE', 'UF_ICON'],
            $element ? Tools::IPROPERTY_ENTITY_ELEMENT : Tools::IPROPERTY_ENTITY_SECTION
        );
        if ($element) {
            $buttonsPanel = self::panelButton($item["IBLOCK_ID"], $item["ID"], $item["IBLOCK_SECTION_ID"]);
        } else {
            $buttonsPanel = self::panelButton($item["IBLOCK_ID"], 0, $item["ID"], 1);
        }
        $item["LINKS"] = $buttonsPanel["LINKS"];
        if ($addLink !== null) {
            if (!$addLink) {
                $addLink = $buttonsPanel["ADD_LINK"];
            }
        }
        return $item;
    }

    public static function panelButton(int $iblockId, int $elementId, int $sectionId, int $type = 0): array
    {
        $buttonsPanel = CIBlock::GetPanelButtons($iblockId, $elementId, $sectionId);
        $type = $type == 0 ? "element" : "section";
        $result["LINKS"] = [
            "EDIT" => [
                "URL" => $buttonsPanel["edit"]["edit_$type"]["ACTION_URL"],
                "TITLE" => $buttonsPanel["edit"]["edit_$type"]["TITLE"],
            ],
            "DELETE" => [
                "URL" => $buttonsPanel["edit"]["delete_$type"]["ACTION_URL"],
                "TITLE" => $buttonsPanel["edit"]["delete_$type"]["TITLE"],
            ]
        ];
        $result["ADD_LINK"] = [
            "URL" => $buttonsPanel["edit"]["add_$type"]["ACTION_URL"],
            "TITLE" => $buttonsPanel["edit"]["add_$type"]["TITLE"],
        ];
        return $result;
    }

    public static function newsListModifier($arResult, $arParams): array
    {
        foreach ($arResult["ITEMS"] as &$arItem) {
            $imageId = $arItem["PREVIEW_PICTURE"]["ID"] ?? Main::getFileIdBySrc(Option::get(NK_MODULE_NAME, "NOPHOTO"));
            $arItem["PREVIEW_PICTURE"]["THUMB"] = CFile::ResizeImageGet($imageId, ["width" => 420, "height" => 330], 2)["src"];
            $arItem["PREVIEW_TEXT"] = $arItem["PREVIEW_TEXT"] ?: (new CTextParser)->html_cut(HTMLToTxt($arItem["DETAIL_TEXT"]), $arParams["PREVIEW_TRUNCATE_LEN"]);
        }
        return $arResult;
    }

    public static function getCachePath(string $componentName): string {
        $componentNameParts = explode(":", $componentName);
        return SITE_ID . "/$componentNameParts[0]/$componentNameParts[1]/";
    }

    public static function setMarketInfo(int $id): void {
        $market = \Bitrix\Iblock\Iblock::wakeUp(IBLOCK_MARKET)->getEntityDataClass()::query()
            ->setSelect(["COORD"])
            ->where("ID", $id)
            ->fetchObject();
        if (!$market->getCoord()->getValue()) return;
        $data = DaData::getAddressInfoByCoord($market->getCoord()->getValue());
        $market
            ->setCountry($data["country"])
            ->setCity($data["city"])
            ->setStreet($data["street_with_type"])
            ->setPostIndex($data["postal_code"])
            ->setHouse($data["house"])
            ->setRegion($data["region"])
            ->save();
    }

}
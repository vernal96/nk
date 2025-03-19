<?php

namespace DK\NK\Helper;

use Bitrix\Iblock\Component\Tools;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\InheritedProperty\ElementValues;
use Bitrix\Iblock\InheritedProperty\SectionValues;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Main\UserFieldTable;
use CFile;
use CIBlock;
use CUserFieldEnum;
use DK\NK\Services\DaData;
use Throwable;
use Bitrix\Iblock\Iblock as BitrixIblock;

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

    public static function getCachePath(string $componentName): string {
        $componentNameParts = explode(":", $componentName);
        return SITE_ID . "/$componentNameParts[0]/$componentNameParts[1]/";
    }

    public static function setMarketInfo(int $id): void {
        $market = BitrixIblock::wakeUp(IBLOCK_MARKET)->getEntityDataClass()::query()
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

    /** @noinspection PhpUnused */
    public static function getUfEnumId(int $iblockId, string $ufName, string $xmlId): ?int
    {
        try {
            $userFieldId = UserFieldTable::query()
                ->addSelect('ID')
                ->where('ENTITY_ID', "IBLOCK_{$iblockId}_SECTION")
                ->where('FIELD_NAME', $ufName)
                ->fetchObject()?->getId();
            if (!$userFieldId) return null;
            $userFieldEnumResult = CUserFieldEnum::GetList([], [
                'USER_FIELD_ID' => $userFieldId,
                'XML_ID' => $xmlId
            ])->Fetch();
            return $userFieldEnumResult ? (int)$userFieldEnumResult['ID'] : null;
        } catch (Throwable) {
            return null;
        }
    }

    /**
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getIblockPhotoSrc(bool $isElement, int|string $id, array $arSizes)
    {
        $id = is_int($id) ? $id : (int)preg_replace("/\D/", "", $id);
        $arItem = $isElement ? ElementTable::getRowById($id) : SectionTable::getRowById($id);
        $pictureId = $arItem[$isElement ? "PREVIEW_PICTURE" : "PICTURE"] ?: Main::getFileIdBySrc(Option::get(NK_MODULE_NAME, "NOPHOTO"));
        return CFile::ResizeImageGet($pictureId, ["width" => $arSizes[0], "height" => $arSizes[1]], BX_RESIZE_IMAGE_EXACT)["src"];
    }

}
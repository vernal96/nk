<?php

namespace DK\NK;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\Iblock;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\FileTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;
use CDataXML;
use CFile;
use CIBlockElement;
use CIBlockSection;
use CUtil;
use DK\NK\Helper\Main;
use DK\NK\Services\Bitrix24;
use Exception;
use Throwable;

class Parser
{
    public static string $errors = "";
    private static array $fileSections;
    private static array $fileElements;
    private static array $structure;
    private static string $filePath = "/upload/integration/tovar_out2.xml";
    private static string $fileSectionName = "Разделы";
    private static string $fileElementName = "Элементы";
    private static string $guidFieldName = "КодWeb";
    private static string $parentFieldName = "Группа";
    private static string $titleFieldName = "Наименование";
    private static string $imageFieldName = "Картинка";
    private static string $linkFieldName = "Ссылка";
    private static string $codeFieldName = "КодНомен";
    private static string $boxCountFieldName = "ШтукУпак";
    private static string $price1FieldName = "Цена1";
    private static string $price2FieldName = "Цена2";
    private static string $price3FieldName = "Цена3";
    private static string $sizeFieldName = "Размер";
    private static string $rootSection = "1000000";
    private static int $iblockId = IBLOCK_CATALOG;
    private static int $hlId = HL_SIZES;
    private static array $loadedImages;
    private static CIBlockSection $iblockSection;
    private static CIBlockElement $iblockElement;

    private static array $actualSections = [];
    private static array $actualElements = [];

    /**
     * @throws ArgumentNullException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function start(): string|bool
    {
        self::$loadedImages = FileTable::getList(["filter" => ["MODULE_ID" => NK_MODULE_NAME]])->fetchAll();

        self::$iblockElement = new CIBlockElement();
        self::$iblockSection = new CIBlockSection();
        try {
            self::createFileStructure();
        } catch (Throwable) {
            return Loc::getMessage("NK_PARSER_FILE_ERROR");
        }

        self::setZeroPricesElements();

        self::$structure = self::createStructure(self::$fileSections, self::$rootSection);
//		self::$structure = self::optimizeStructure(self::$structure);
        self::updateIblock(self::$structure);
        self::parsingElements();
        self::deleteOldElements();

        foreach (self::$loadedImages as $loadedImage) {
            CFile::Delete($loadedImage["ID"]);
        }

//        self::disableAllEmpty();
//        self::deactivateEmptyProducts();
        Cache::clearCache(true);
        self::updateDBBitrix24();
        return true;
    }

    private static function createFileStructure(): void
    {
        $xml = new CDataXML();
        $documentRoot = Application::getInstance()->getContext()->getServer()->getDocumentRoot();
        $xml->Load($documentRoot . self::$filePath);
        $data = $xml->GetTree();
        $sections = $data->elementsByName(self::$fileSectionName);
        $elements = $data->elementsByName(self::$fileElementName);
        self::$fileSections = self::getArrayFromSection($sections);
        self::$fileElements = self::getArrayFromSection($elements);
    }

    private static function getArrayFromSection($section): array
    {
        $result = [];
        $children = array_shift($section)->children();
        foreach ($children as $element) {
            $attributes = $element->GetAttributes();
            $elementData = [];
            foreach ($attributes as $attribute) {
                $attributeName = $attribute->name();
                $attributeValue = $attribute->textContent();
                $elementData[$attributeName] = $attributeValue;
            }
            $guid = $elementData[self::$guidFieldName];
            $result[$guid] = $elementData;
        }
        return $result;
    }

    private static function setZeroPricesElements(): void
    {
        foreach (self::$fileElements as &$element) {
            for ($i = 1; $i < 4; $i++) {
                $elementName = "price{$i}FieldName";
                $previousElementName = "price" . ($i - 1) . "FieldName";
                if (!$element[self::${$elementName}] && $i != 1) {
                    $element[self::${$elementName}] = $element[self::${$previousElementName}];
                }
                $element[self::${$elementName}] = number_format($element[self::${$elementName}], 2, ".", "");
            }
        }
    }

    private static function createStructure(array $sections, int $parentId): array
    {
        $result = [];
        foreach ($sections as $guid => &$section) {
            if ($section[self::$parentFieldName] == $parentId) {
                $section["CHILDREN"] = self::createStructure($sections, $guid) ?: [];
                $section["ELEMENTS"] = (bool)array_filter(self::$fileElements, fn($element) => $element[self::$parentFieldName] == $guid);
                $result[$guid] = $section;
            }
        }
        return $result;
    }

    private static function updateIblock($sections): void
    {
        foreach ($sections as $guid => $section) {
            $parentId = ($section[self::$parentFieldName] != self::$rootSection) ? self::getIblockElementIdByXml($section[self::$parentFieldName]) : null;
            $addArray = [
                "XML_ID" => $guid,
                "CODE" => $section[self::$linkFieldName] ?: self::translation($section[self::$titleFieldName]),
                "IBLOCK_ID" => self::$iblockId,
                "NAME" => $section[self::$titleFieldName],
                "IBLOCK_SECTION_ID" => $parentId
            ];
            $updateArray = [
                "IBLOCK_SECTION_ID" => $parentId,
                "NAME" => $section[self::$titleFieldName],
                "ACTIVE" => 'Y'
            ];

            if ($section["ELEMENTS"]) {
                self::$actualElements[] = $guid;
                if ($existingElementId = self::getIblockElementIdByXml($guid, true)) {
                    self::$iblockElement->Update($existingElementId, $updateArray);
                } else {
                    $existingElementId = self::$iblockElement->Add($addArray);
                }
                self::updateImage($section, $existingElementId, true);
            } else {
                self::$actualSections[] = $guid;
                if ($existingSectionId = self::getIblockElementIdByXml($guid)) {
                    self::$iblockSection->Update($existingSectionId, $updateArray);
                } else {
                    $existingSectionId = self::$iblockSection->Add($addArray);
                }
                self::updateImage($section, $existingSectionId);
                if ($section["CHILDREN"]) {
                    self::updateIblock($section["CHILDREN"]);
                }
            }
        }
    }

    private static function getIblockElementIdByXml($guid, $isElement = false): int|bool
    {
        if ($isElement) {
            $result = CIBlockElement::GetList(["ID"], [
                "XML_ID" => $guid,
                "IBLOCK_ID" => self::$iblockId
            ])->Fetch();
        } else {
            $result = CIBlockSection::GetList(["ID"], [
                "XML_ID" => $guid,
                "IBLOCK_ID" => self::$iblockId
            ])->Fetch();
        }
        return $result ? $result["ID"] : false;
    }

    private static function translation($string): string
    {
        return CUtil::translit($string, "ru", [
            "replace_space" => "-",
            "replace_other" => ""
        ]);
    }

    private static function updateImage($element, $elementId, $isElement = false): void
    {
        $fileName = $element[self::$imageFieldName];
        $loadedImage = array_filter(self::$loadedImages, fn($image) => $image["ORIGINAL_NAME"] == $fileName);
        if (!$loadedImage) return;
        $loadedImage = array_shift($loadedImage);
        $imageArray = CFile::MakeFileArray($loadedImage["ID"]);
        if ($isElement) {
            self::$iblockElement->Update($elementId, [
                "PREVIEW_PICTURE" => $imageArray
            ]);
        } else {
            self::$iblockSection->Update($elementId, [
                "PREVIEW_PICTURE" => $imageArray
            ]);
        }
    }

    /**
     * @throws ObjectPropertyException
     * @throws ArgumentException
     * @throws SystemException
     * @throws Exception
     */
    private static function parsingElements(): void
    {
        self::setZeroPricesElements();
        $sizesTable = Main::getHLObject(self::$hlId);
        $products = ElementTable::getList([
            "select" => ["ID", "XML_ID"],
            "filter" => [
                "XML_ID" => array_column(self::$fileElements, self::$parentFieldName),
                "IBLOCK_ID" => self::$iblockId
            ]
        ])->fetchAll();
        $existingElements = $sizesTable::getList(["select" => ["ID", "UF_CODE"]])->fetchAll();
        foreach (self::$fileElements as $element) {
            $parentElement = array_filter($products, fn($product) => $product["XML_ID"] == $element[self::$parentFieldName]);
            if (!$parentElement) continue;
            $parentId = array_shift($parentElement)["ID"];
            $paramsArray = [
                "UF_SIZE" => $element[self::$sizeFieldName],
                "UF_BOX_COUNT" => $element[self::$boxCountFieldName],
                "UF_PRICE_1" => $element[self::$price1FieldName],
                "UF_PRICE_2" => $element[self::$price2FieldName],
                "UF_PRICE_3" => $element[self::$price3FieldName],
                "UF_CODE" => $element[self::$codeFieldName],
                "UF_NAME" => $element[self::$titleFieldName],
                "UF_PRODUCT" => $parentId
            ];

            $existingElement = array_filter($existingElements, fn($existingElement) => $existingElement["UF_CODE"] == $element[self::$codeFieldName]);
            if ($existingElement) {
                $existingElementId = array_shift($existingElement)["ID"];
                $sizesTable::update($existingElementId, $paramsArray);
            } else {
                $paramsArray["UF_SORT"] = 500;
                $sizesTable::add($paramsArray);
            }
        }
        $deletedElements = $sizesTable::getList([
            "select" => ["ID"],
            "filter" => [
                "!UF_CODE" => array_column(self::$fileElements, self::$codeFieldName)
            ]
        ]);
        while ($deletedElement = $deletedElements->fetch()) {
            $sizesTable::delete($deletedElement["ID"]);
        }
    }

    private static function deleteOldElements(): void
    {
        $notActualSections = self::$iblockSection->GetList([], [
            "!XML_ID" => self::$actualSections,
            "IBLOCK_ID" => self::$iblockId
        ], false, ["ID"]);
        $notActualElements = self::$iblockElement->GetList([], [
            "!XML_ID" => self::$actualElements,
            "IBLOCK_ID" => self::$iblockId
        ], false, ["ID"]);
        while ($notActualSection = $notActualSections->Fetch()) {
            self::$iblockSection->Update($notActualSection["ID"], [
                "ACTIVE" => "N"
            ]);
        }
        while ($notActualElement = $notActualElements->Fetch()) {
            self::$iblockElement->Update($notActualElement["ID"], [
                "ACTIVE" => "N"
            ]);
        }
    }

    private static function updateDBBitrix24(): void
    {
        $bx24 = new Bitrix24();

        $actualItems = Main::getHLObject(HL_SIZES)::query()
            ->setSelect(["*", "NAME" => "PRODUCT.NAME", "PHOTO" => "PRODUCT.PREVIEW_PICTURE"])
            ->registerRuntimeField("PRODUCT", new Reference("PRODUCT", ElementTable::class, Join::on("this.UF_PRODUCT", "ref.ID")))
            ->fetchAll();
        $actualItems = array_map(function ($item) {
            $item["NAME"] = $item["NAME"] . " " . $item["UF_SIZE"];
            return $item;
        }, $actualItems);


        $arItems = [];
        $start = 0;
        while (true) {
            $result = $bx24->query("crm.product.list", [
                "select" => ["*"],
                "start" => $start
            ]);
            $arItems = array_merge($arItems, $result["result"]);
            if ($result["next"]) $start = $result["next"];
            else break;
        }

        $notRemoveElements = [];

        foreach ($arItems as $item) {
            $xmlId = $item["XML_ID"];
            if (in_array($xmlId, array_column($notRemoveElements, "XML_ID"))) continue;
            $arrayFilter = array_filter($actualItems, fn($size) => $size["UF_CODE"] == $xmlId);
            if (empty($arrayFilter)) continue;
            $actualItem = current($arrayFilter);
            $notRemoveElements[] = $item;
            if (
                !$item["PREVIEW_PICTURE"] && $actualItem["PHOTO"]
                || $item["NAME"] != $actualItem["NAME"]
                || $item["PRICE"] != number_format($actualItem["UF_PRICE_1"], 2, ".", "")
                || $item["PROPERTY_113"]["value"] != $actualItem["UF_PRICE_2"]
                || $item["PROPERTY_115"]["value"] != $actualItem["UF_PRICE_3"]
                || $item["PROPERTY_117"]["value"] != $actualItem["UF_BOX_COUNT"]
            ) {
                $bx24->batchAdd("update_" . $item["ID"], "crm.product.update", [
                    "id" => $item["ID"],
                    "fields" => self::getFieldsForBX24($actualItem)
                ]);
            }
        }

        $removeElements = array_filter($arItems, fn($item) => !in_array($item, $notRemoveElements));
        foreach ($removeElements as $item) {
            $bx24->batchAdd("delete_" . $item["ID"], "crm.product.delete", [
                "id" => $item["ID"],
            ]);
        }

        foreach ($actualItems as $item) {
            if (in_array($item["UF_CODE"], array_column($notRemoveElements, "XML_ID"))) continue;
            $bx24->batchAdd("add_" . $item["UF_CODE"], "crm.product.add", [
                "fields" => self::getFieldsForBX24($item)
            ]);
        }
        $bx24->batchCall();
    }

    private static function getFieldsForBX24(array $item): array
    {
        return [
            "NAME" => $item["NAME"],
            "CURRENCY_ID" => "RUB",
            "PRICE" => $item["UF_PRICE_1"],
            "XML_ID" => $item["UF_CODE"],
            "PREVIEW_PICTURE" => $item["PHOTO"],
            "PROPERTY_113" => $item["UF_PRICE_2"], // Цена 2
            "PROPERTY_115" => $item["UF_PRICE_3"], // Цена 3
            "PROPERTY_117" => $item["UF_BOX_COUNT"], // В коробке
        ];
    }

    private static function optimizeStructure(array $sections): array
    {
        $result = [];
        foreach ($sections as &$section) {

            if (preg_match("/\+{3}/", $section[self::$titleFieldName])) {
                $section[self::$linkFieldName] = $section[self::$imageFieldName];
            }

            $children = self::optimizeStructure($section["CHILDREN"]);
            if (count($children) == 1) {
                $child = current($children);
                if ($section[self::$parentFieldName] == self::$rootSection) {
                    if ($child["ELEMENTS"]) {
                        $section["CHILDREN"] = $children;
                    } else {
                        $section["CHILDREN"] = array_map(function ($c) use ($section) {
                            $c[self::$parentFieldName] = $section[self::$guidFieldName];
                            return $c;
                        }, $child["CHILDREN"]);
                    }
                } else {
                    $child[self::$parentFieldName] = $section[self::$parentFieldName];
                    $section = $child;
                }
            } else {
                $section["CHILDREN"] = $children;
            }
            $section[self::$titleFieldName] = self::setTitle($section[self::$titleFieldName]);
            $result[$section[self::$guidFieldName]] = $section;
        }
        return $result;
    }

    private static function setTitle(string $title): string
    {
        $title = preg_replace("/\+{3}/", "", $title);
        $title = preg_replace("/\s{2,}/", " ", $title);
        $title = preg_replace("/'/", "\"", $title);
        return trim($title);
    }

    private static function disableAllEmpty(): void
    {
        $elementCollection = Iblock::wakeUp(IBLOCK_CATALOG)->getEntityDataClass()::query()
            ->setSelect(["PRICE", "IBLOCK_SECTION_ID"])
            ->where("ACTIVE", true)
            ->registerRuntimeField("PRICE", new Reference("PRICE", Main::getHLObject(HL_SIZES), Join::on("this.ID", "ref.UF_PRODUCT")))
            ->fetchCollection();

        foreach ($elementCollection as $element) {
            if(!$element->get("PRICE")) {
                $element->set("ACTIVE", false)->save();
            }
        }

        $rsSections = CIBlockSection::GetList([], [
            "IBLOCK_ID" =>IBLOCK_CATALOG,
            "ACTIVE" => "Y",
        ], true, ["ID"]);

        while ($section = $rsSections->Fetch()) {
            if (!$section["ELEMENT_CNT"]) (new CIBlockSection())->Update($section["ID"], ["ACTIVE" => "N"]);
        }
    }

    private static function deactivateEmptyProducts(): void {
        $elements = Iblock::wakeUp(IBLOCK_CATALOG)
            ->getEntityDataClass()::query()
            ->addSelect('ID')
            ->where('ACTIVE', 'Y')
            ->fetchCollection()->getAll();

        $sizes = Main::getHLObject(HL_SIZES)::query()
            ->addSelect('UF_PRODUCT')
            ->fetchCollection()->getAll();

        foreach ($elements as $element) {
            $hasSizes = (int)array_filter($sizes, fn($size) => $size->getUfProduct() == $element->getId());
            if (!$hasSizes) {
                $element->setActive(false)->save();
            }
        }
    }

}
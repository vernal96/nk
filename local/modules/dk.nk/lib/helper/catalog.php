<?php

namespace DK\NK\Helper;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\UI\PageNavigation;
use CFile;
use CIBlockElement;
use CIBlockSection;
use DK\NK\Cart;

class Catalog
{

    private const CATALOG_CACHE_PATH = SITE_ID . "/dk/catalog/";
    public const PRODUCT_IMAGE_SIZE = ["width" => 440, "height" => 320];
    public const PRODUCT_IMAGE_SIZE_SOURCES = [
        1440 => [324, 220],
        1024 => [219, 190],
        768 => [362, 171],
        425 => [203, 146],
        375 => [180, 142]
    ];
    public const PRODUCT_SECTION_IMAGE_SIZE = ["width" => 440, "height" => 390];
    public static array $arSectionOrder = ["SORT" => "ASC", "NAME" => "ASC", "ID" => "ASC"];
    public static array $arSectionFields = ["ID", "IBLOCK_ID", "IBLOCK_SECTION_ID", "NAME", "PICTURE", "DETAIL_PICTURE", "UF_ICON", "UF_NEW", "SECTION_PAGE_URL"];
    public static array $arProductOrder = ["SORT" => "ASC", "NAME" => "ASC", "ID" => "ASC"];
    public static array $arProductFields = ["ID", "NAME", "IBLOCK_ID", "CODE", "IBLOCK_SECTION_ID", "PREVIEW_PICTURE", "DETAIL_PAGE_URL"];

    public static function getProduct(int $id): array
    {
        $cache = Cache::createInstance();
        $taggedCache = Application::getInstance()->getTaggedCache();
        $cacheUnique = "dk.product.$id";
        $cachePath = "products";
        $result = [];
        if ($cache->initCache(CACHE_TIME, $cacheUnique, self::CATALOG_CACHE_PATH . $cachePath)) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $taggedCache->startTagCache(self::CATALOG_CACHE_PATH . $cachePath);

            $element = CIblockElement::GetByID($id)->GetNextElement();
            if (!$element) {
                $cache->abortDataCache();
                $taggedCache->abortTagCache();
                return $result;
            }
            $result = $element->GetFields();
            $result["PROPERTIES"] = $element->GetProperties();
            $result = Iblock::setResultFields($result);

            $taggedCache->registerTag("iblock_id_" . IBLOCK_CATALOG);
            $taggedCache->endTagCache();
            $cache->endDataCache($result);
        }
        return $result;
    }

    public static function getSection(int $id): array
    {
        $cache = Cache::createInstance();
        $taggedCache = Application::getInstance()->getTaggedCache();
        $cacheUnique = "dk.section.$id";
        $cachePath = "sections";
        $result = [];
        if ($cache->initCache(CACHE_TIME, $cacheUnique, self::CATALOG_CACHE_PATH . $cachePath)) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $taggedCache->startTagCache(self::CATALOG_CACHE_PATH . $cachePath);

            $result = CIBlockSection::GetByID($id)->GetNext();
            if (!$result) {
                $cache->abortDataCache();
                $taggedCache->abortTagCache();
                return $result;
            }
            $result = Iblock::setResultFields($result, $addLink, false);

            $taggedCache->registerTag("iblock_id_" . IBLOCK_CATALOG);
            $taggedCache->endTagCache();
            $cache->endDataCache($result);
        }
        return $result;
    }

    public static function getSizePrice(int $id, int $productId): float|int
    {
        $arPrice = array_filter(self::getProductPrices($productId), fn($price) => $price["ID"] == $id);
        if (empty($arPrice)) return 0;
        return current($arPrice)["UF_PRICE_" . Main::getUserType()];
    }

    public static function getProductPrices(int $productId, array $arId = []): array
    {
        return Main::getHLObject(HL_SIZES)::query()
            ->setSelect(["ID", "UF_SIZE", "UF_BOX_COUNT", "UF_PRICE_1", "UF_PRICE_2", "UF_PRICE_3", "UF_PRODUCT"])
            ->where("UF_PRODUCT", $productId)
            ->whereIn("ID", $arId)
            ->setOrder(["UF_SORT" => "ASC", "UF_SIZE" => "ASC", "UF_PRICE_3" => "ASC", "UF_PRICE_2" => "ASC", "UF_PRICE_1" => "ASC"])
            ->setCacheTtl(CACHE_TIME)
            ->fetchAll();
    }

    public static function getCompactTree($isHeader = false): array
    {
        $cache = Cache::createInstance();
        $taggedCache = Application::getInstance()->getTaggedCache();
        $cacheUnique = "dk.compact.tree" . $isHeader;
        $cachePath = "tree";
        $result = [];
        if ($cache->initCache(CACHE_TIME, $cacheUnique, self::CATALOG_CACHE_PATH . $cachePath)) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $taggedCache->startTagCache(self::CATALOG_CACHE_PATH . $cachePath);

            $result = array_map(self::class . "::setCompactTree", self::getTree($isHeader));

            $taggedCache->registerTag("iblock_id_" . IBLOCK_CATALOG);
            $taggedCache->endTagCache();
            $cache->endDataCache($result);
        }
        return $result;
    }

    public static function getTree($isHeader = false): array
    {
        $cache = Cache::createInstance();
        $taggedCache = Application::getInstance()->getTaggedCache();
        $cacheUnique = "dk.tree". $isHeader;
        $cachePath = "tree";
        $result = [];
        if ($cache->initCache(CACHE_TIME, $cacheUnique, self::CATALOG_CACHE_PATH . $cachePath)) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $taggedCache->startTagCache(self::CATALOG_CACHE_PATH . $cachePath);
            $sectionResult = CIBlockSection::GetList(
                $isHeader ? ['UF_HEADER_SORT' => 'ASC', ...self::$arSectionOrder] : self::$arSectionOrder,
                [
                    "ACTIVE" => "Y",
                    "GLOBAL_ACTIVE" => "Y",
                    "IBLOCK_ID" => IBLOCK_CATALOG,
                    'ELEMENT_SUBSECTIONS' => 'N'
                ],
                true,
                self::$arSectionFields,
            );
            while ($section = $sectionResult->GetNext()) {
                $section = Iblock::setResultFields($section, $link, false);
                if ($section["~UF_ICON"]) {
                    $section["UF_ICON"] = CFile::GetPath($section["~UF_ICON"]);
                }
                $result[] = $section;
            }
            $result = self::setTree($result);

            $taggedCache->registerTag("iblock_id_" . IBLOCK_CATALOG);
            $taggedCache->endTagCache();
            $cache->endDataCache($result);
        }
        return $result;
    }

    private static function setTree(array &$sections, $parentId = 0): array
    {
        $result = array_values(array_filter($sections, fn($section) => $section["IBLOCK_SECTION_ID"] == $parentId));
        $sections = array_filter($sections, fn($section) => !in_array($section, $result));
        foreach ($result as &$resultItem) {
            $resultItem["CHILDREN"] = self::setTree($sections, $resultItem["ID"]);
        }
        return $result;
    }

    public static function getCompactProducts(int $sectionId, int $pageSize, int $page = 1): array
    {
        $cache = Cache::createInstance();
        $taggedCache = Application::getInstance()->getTaggedCache();
        $cacheUnique = "$sectionId.$pageSize.$page";
        $cachePath = "products.compact";
        $result = [];
        if ($cache->initCache(CACHE_TIME, $cacheUnique, self::CATALOG_CACHE_PATH . $cachePath)) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $taggedCache->startTagCache(self::CATALOG_CACHE_PATH . $cachePath);

            $products = self::getProducts($sectionId, $pageSize, $page);

            if (!$products) {
                $cache->abortDataCache();
                $taggedCache->abortTagCache();
                return [];
            }

            $result = array_map(function ($product) {
                $picture = $product[$product["IS_SECTION"] ? "PICTURE" : "PREVIEW_PICTURE"] ?: Main::getFileIdBySrc(Option::get(NK_MODULE_NAME, "NOPHOTO"));

                return [
                    "id" => (int)$product["ID"],
                    "name" => $product["~NAME"],
                    "picture" => [
                        "src" => CFile::ResizeImageGet(
                            $picture,
                            $product["IS_SECTION"] ? self::PRODUCT_SECTION_IMAGE_SIZE : self::PRODUCT_IMAGE_SIZE,
                            3)["src"],
                        "alt" => $product[$product["IS_SECTION"] ? "PICTURE" : "PREVIEW_PICTURE"]["ALT"] ?: "",
                        "title" => $product[$product["IS_SECTION"] ? "PICTURE" : "PREVIEW_PICTURE"]["TITLE"] ?: "",
                    ],
                    "url" => $product["IS_SECTION"] ? $product["SECTION_PAGE_URL"] : $product["DETAIL_PAGE_URL"],
                    "isSection" => $product["IS_SECTION"],
                    "price" => []
                ];
            }, $products["ELEMENTS"]);

            $taggedCache->registerTag("iblock_id_" . IBLOCK_CATALOG);
            $taggedCache->endTagCache();
            $cache->endDataCache($result);
        }

        return array_map(function ($product) {

            $arPrice = self::getProductPrices($product["id"]);
            $from = count($arPrice) > 1;
            $sizeId = null;
            $cartCount = null;
            if (!$from) {
                $sizeId = (int)$arPrice[0]["ID"];
                $cartCount = Cart::getInstance()->getSizeCount($sizeId, $product["id"]);
            }
            $product["price"] = [
                "from" => $from,
                "sizeId" => $sizeId,
                "cartCount" => $cartCount,
                "cost" => Main::priceFormat(current($arPrice)["UF_PRICE_" . Main::getUserType()])
            ];
            return $product;
        }, $result);
    }

    public static function getProducts(int $sectionId, int $pageSize, int $page = 1): ?array
    {
        $result = [
            "ADD_LINKS" => [
                "SECTION" => [],
                "ELEMENT" => []
            ],
            "ELEMENTS" => []
        ];

        $elementFilter = [
            "ACTIVE" => "Y",
            "IBLOCK_ID" => IBLOCK_CATALOG,
            "SECTION_ID" => $sectionId,
//            "INCLUDE_SUBSECTIONS" => "Y",
        ];

        $navigation = new PageNavigation("pagination");
        $navigation
            ->allowAllRecords(true)
            ->setPageSize($pageSize)
            ->allowAllRecords(false)
            ->setCurrentPage($page);

        $rsSections = CIBlockSection::GetList(self::$arSectionOrder, [
            "IBLOCK_ID" => IBLOCK_CATALOG,
            "ACTIVE" => "Y",
            "SECTION_ID" => $sectionId,
        ], false, self::$arSectionFields, [
            "iNumPage" => $navigation->getCurrentPage(),
            "nPageSize" => $navigation->getPageSize(),
            "checkOutOfRange" => true
        ]);

        $elementsCount = CIBlockElement::GetList(self::$arProductOrder, $elementFilter, []);
        $rsElements = CIBlockElement::GetList(self::$arProductOrder, $elementFilter, false,
            [
                "nTopCount" => $navigation->getPageSize() - $rsSections->result->num_rows,
                "nOffset" => max(0, ($navigation->getCurrentPage() - 1) * $navigation->getPageSize() - $rsSections->NavRecordCount),
                "nPageSize" => $navigation->getPageSize() - $rsSections->result->num_rows,
            ]
        );

        $navigation->setRecordCount($rsSections->NavRecordCount + $elementsCount);

        while ($arSection = $rsSections->GetNextElement()) {
            $section = $arSection->fields;
            $section["IS_SECTION"] = true;
            $section["NO_PHOTO"] = Main::getFileIdBySrc(Option::get(NK_MODULE_NAME, "NOPHOTO"));
            $section = Iblock::setResultFields($section, $result["ADD_LINKS"]["SECTION"], false);
            $result["ELEMENTS"][] = $section;
        }
        if ($navigation->getPageSize() - $rsSections->result->num_rows) {
            while ($arElement = $rsElements->GetNextElement()) {
                $element = $arElement->fields;
                $element["PROPERTIES"] = $arElement->GetProperties();
                $element["NO_PHOTO"] = Main::getFileIdBySrc(Option::get(NK_MODULE_NAME, "NOPHOTO"));
                $element = Iblock::setResultFields($element, $result["ADD_LINKS"]["ELEMENT"]);
                $result["ELEMENTS"][] = $element;
            }
        }
        if ($page > $navigation->getPageCount()) {
            return null;
        }

        $result["NAV_OBJECT"] = $navigation;

        return $result;
    }

    private static function setCompactTree(array $section): array
    {
        return [
            "id" => (int)$section["ID"],
            "title" => $section["~NAME"],
            "url" => $section["SECTION_PAGE_URL"],
            "tags" => [
                "new" => (bool)$section["UF_NEW"]
            ],
            "icon" => $section["UF_ICON"],
            "images" => [
                "picture" => $section["PICTURE"] ? [
                    "src" => CFile::ResizeImageGet(
                        $section["PICTURE"],
                        ["width" => 50, "height" => 50],
                        BX_RESIZE_IMAGE_EXACT
                    )["src"],
                    "alt" => $section["PICTURE"]["ALT"],
                    "title" => $section["PICTURE"]["TITLE"],
                ] : false,
                "detail" => $section["DETAIL_PICTURE"] ? [
                    "src" => CFile::ResizeImageGet(
                        $section["DETAIL_PICTURE"],
                        ["width" => 300, "height" => 520]
                    )["src"],
                    "alt" => $section["DETAIL_PICTURE"]["ALT"],
                    "title" => $section["DETAIL_PICTURE"]["TITLE"],
                    BX_RESIZE_IMAGE_EXACT
                ] : false
            ],
            "children" => array_map(self::class . "::setCompactTree", $section["CHILDREN"]),
            "elCount" => (int)$section["ELEMENT_CNT"] + count($section["CHILDREN"]),
            "products" => []
        ];
    }

}
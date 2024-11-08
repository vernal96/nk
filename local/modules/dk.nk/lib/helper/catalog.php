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
    public const PRODUCT_IMAGE_SIZE = ["width" => 400, "height" => 280];
    public static array $arSectionOrder = ["UF_NEW" => "DESC", "SORT" => "ASC", "NAME" => "ASC", "ID" => "ASC"];
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
            ->setOrder(["UF_SORT" => "ASC", "UF_PRICE_3" => "ASC", "UF_PRICE_2" => "ASC", "UF_PRICE_1" => "ASC", "UF_SIZE" => "ASC"])
            ->setCacheTtl(CACHE_TIME)
            ->fetchAll();
    }

    public static function getCompactTree(): array
    {
        $cache = Cache::createInstance();
        $taggedCache = Application::getInstance()->getTaggedCache();
        $cacheUnique = "dk.compact.tree";
        $cachePath = "tree";
        $result = [];
        if ($cache->initCache(CACHE_TIME, $cacheUnique, self::CATALOG_CACHE_PATH . $cachePath)) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $taggedCache->startTagCache(self::CATALOG_CACHE_PATH . $cachePath);

            $result = array_map(self::class . "::setCompactTree", self::getTree());

            $taggedCache->registerTag("iblock_id_" . IBLOCK_CATALOG);
            $taggedCache->endTagCache();
            $cache->endDataCache($result);
        }
        return $result;
    }

    public static function getTree(): array
    {
        $cache = Cache::createInstance();
        $taggedCache = Application::getInstance()->getTaggedCache();
        $cacheUnique = "dk.tree";
        $cachePath = "tree";
        $result = [];
        if ($cache->initCache(CACHE_TIME, $cacheUnique, self::CATALOG_CACHE_PATH . $cachePath)) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $taggedCache->startTagCache(self::CATALOG_CACHE_PATH . $cachePath);

            $sectionResult = CIBlockSection::GetList(
                self::$arSectionOrder,
                [
                    "ACTIVE" => "Y",
                    "GLOBAL_ACTIVE" => "Y",
                    "IBLOCK_ID" => IBLOCK_CATALOG
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

            $result = self::getProducts($sectionId, $pageSize, $page);

            $result = array_map(function ($product) {
                $picture = $product["PREVIEW_PICTURE"] ?: Main::getFileIdBySrc(Option::get(NK_MODULE_NAME, "NOPHOTO"));

                return [
                    "id" => (int)$product["ID"],
                    "name" => $product["~NAME"],
                    "picture" => [
                        "src" => CFile::ResizeImageGet($picture, self::PRODUCT_IMAGE_SIZE, BX_RESIZE_IMAGE_EXACT)["src"],
                        "alt" => $product["PREVIEW_PICTURE"]["ALT"] ?: "",
                        "title" => $product["PREVIEW_PICTURE"]["TITLE"] ?: "",
                    ],
                    "url" => $product["DETAIL_PAGE_URL"],
                    "price" => []
                ];
            }, $result);

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

    public static function getProducts(int $sectionId, int $pageSize, int $page = 1): array
    {
        $navigation = new PageNavigation("productPage");
        $navigation->allowAllRecords(false)->setCurrentPage($page)->setPageSize($pageSize);

        $queryResult = CIBlockElement::GetList(
            self::$arProductOrder,
            [
                "ACTIVE" => "Y",
                "SECTION_ID" => $sectionId,
                "IBLOCK_ID" => IBLOCK_CATALOG,
                "INCLUDE_SUBSECTIONS" => "Y"
            ],
            false,
            [
                "nOffset" => $navigation->getOffset(),
                "iNumPage" => $navigation->getCurrentPage(),
                "nPageSize" => $navigation->getPageSize(),
            ],
            self::$arProductFields
        );
        $navigation->setRecordCount($queryResult->SelectedRowsCount());
        $result = [];
        while ($element = $queryResult->GetNextElement()) {
            $arElement = $element->fields;
            $arElement["PROPERTIES"] = $element->GetProperties();
            $arElement = Iblock::setResultFields($arElement);
            $result[] = $arElement;
        }

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
                        ["width" => 300, "height" => 340]
                    )["src"],
                    "alt" => $section["DETAIL_PICTURE"]["ALT"],
                    "title" => $section["DETAIL_PICTURE"]["TITLE"],
                    BX_RESIZE_IMAGE_EXACT
                ] : false
            ],
            "children" => array_map(self::class . "::setCompactTree", $section["CHILDREN"]),
            "elCount" => (int)$section["ELEMENT_CNT"],
            "products" => []
        ];
    }

}
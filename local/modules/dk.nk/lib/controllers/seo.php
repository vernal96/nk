<?php

namespace DK\NK\Controllers;

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use CIBlockSection;
use DK\NK\Entity\SizesTable;

class SEO extends Controller
{
    public function configureActions(): array
    {
        return [
            'getItems' => [
                'prefilters' => [
                    new ActionFilter\Csrf(),
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_POST])
                ]
            ],
        ];
    }

    public function getItemsAction(array $ids): array
    {
        $result = [];

        foreach ($ids as $index => $id) {
            try {
                $item = $this->getEcommerceItem($id);
            } catch (SystemException) {
                continue;
            }
            $result[] = [...$item, 'position' => $index];
        }

        return $result;
    }

    /**
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getEcommerceItem(int $id): array
    {
        $cache = Cache::createInstance();
        $taggedCache = Application::getInstance()->getTaggedCache();
        $cacheUnique = "ecommerce.$id";
        $cachePath = SITE_ID . '/dk/seo/ecommerce';
        $result = [];

        if ($cache->initCache(CACHE_TIME, $cacheUnique, $cachePath)) {
            $result = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            $taggedCache->startTagCache($cachePath);

            $item = SizesTable::query()
                ->setSelect(['UF_SIZE', 'UF_PRICE_1', 'PRODUCT.NAME', 'PRODUCT.IBLOCK_SECTION_ID'])
                ->where('ID', $id)
                ->fetchObject();

            $result = [
                'id' => $item->getId(),
                'name' => "{$item->getProduct()?->getName()} ({$item->getUfSize()})",
                'price' => $item->get('UF_PRICE_1'),
                'category' => implode(
                    ' / ',
                    array_column(
                        CIBlockSection::GetNavChain(
                            IBLOCK_CATALOG,
                            $item->getProduct()->getIblockSectionId(),
                            ['NAME'],
                            true
                        ),
                        'NAME'
                    )
                )
            ];

            $taggedCache->registerTag("iblock_id_" . IBLOCK_CATALOG);
            $taggedCache->endTagCache();
            $cache->endDataCache($result);
        }
        return $result;
    }

}
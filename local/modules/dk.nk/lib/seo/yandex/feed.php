<?php

namespace DK\NK\SEO\Yandex;

use Bitrix\Iblock\InheritedProperty\ElementValues;
use Bitrix\Main\Loader;
use CFile;
use CIBlockElement;
use CIBlockSection;
use DK\NK\Helper;
use DOMDocument;
use DOMElement;
use DOMException;
use Throwable;

class Feed
{

    private DOMDocument $doc;
    private string $filePath;
    private string $absolutePath;

    public function __construct()
    {
        try {
            Loader::includeModule('iblock');
        } catch (Throwable $e) {
            addUncaughtExceptionToLog($e);
        }
        $this->doc = new DOMDocument('1.0', 'UTF-8');
        $this->doc->formatOutput = true;
        $this->filePath = "/feed.yml";
        $this->absolutePath = $_SERVER['DOCUMENT_ROOT'] . $this->filePath;
    }

    public function createFIle(): bool
    {
        try {
            $this->setXml();
            return (bool)$this->doc->save($this->absolutePath);
        } catch (Throwable $e) {
            addUncaughtExceptionToLog($e);
            return false;
        }
    }

    /**
     * @throws DOMException
     */
    private function setXml(): void
    {
        $this->doc->appendChild($this->getCatalog());
    }

    /**
     * @throws DOMException
     */
    private function getCatalog(): DOMElement|bool
    {
        $catalog = $this->doc->createElement('yml_catalog');
        $catalog->setAttribute('date', date('Y-m-d H:iP'));

        $catalog->appendChild($this->getShop());

        return $catalog;
    }

    /**
     * @throws DOMException
     */
    private function getShop(): DOMElement|bool
    {

        $shop = $this->doc->createElement('shop');

        if (!$shop) return $shop;

        $this->addSimpleTagToNode($shop, 'name', 'Надёжный Крепёж');
        $this->addSimpleTagToNode($shop, 'company', 'ООО "НК"');
        $this->addSimpleTagToNode($shop, 'url', HOST);
        $this->addSimpleTagToNode($shop, 'delivery', 'true');

        $shop->appendChild($this->getCurrencies());
        $shop->appendChild($this->getCategories());
        $shop->appendChild($this->getOffers());

        return $shop;
    }

    /**
     * @throws DOMException
     */
    private function getCurrencies(): bool|DOMElement
    {
        $currencies = $this->doc->createElement('currencies');
        $currency = $this->doc->createElement('currency');
        $currency->setAttribute('id', 'RUB');
        $currency->setAttribute('rate', '1');
        $currencies->appendChild($currency);
        return $currencies;
    }

    /**
     * @throws DOMException
     */
    private function getCategories(): DOMElement|bool
    {
        $categories = $this->doc->createElement('categories');

        $iblockResult = CIBlockSection::GetTreeList(
            ['IBLOCK_ID' => IBLOCK_CATALOG, 'GLOBAL_ACCESS' => 'Y'],
            ['ID', 'NAME', 'IBLOCK_SECTION_ID']
        );

        while ($section = $iblockResult->fetch()) {
            $attrs = [
                'id' => $section['ID']
            ];
            if ($section['IBLOCK_SECTION_ID']) {
                $attrs['parentId'] = $section['IBLOCK_SECTION_ID'];
            }
            $this->addSimpleTagToNode($categories, 'category', $section['NAME'], $attrs);
        }

        return $categories;
    }

    /**
     * @throws DOMException
     */
    private function getOffers(): DOMElement|bool
    {
        $offers = $this->doc->createElement('offers');

        $elements = CIBlockElement::GetList([
            'sort' => 'asc',
            'id' => 'asc'
        ], [
            'IBLOCK_ID' => IBLOCK_CATALOG,
            'ACTIVE' => 'Y',
            'SECTION_GLOBAL_ACTIVE' => 'Y'
        ], false, false, [
            'ID', 'NAME', 'IBLOCK_SECTION_ID', 'DETAIL_PAGE_URL', 'PREVIEW_PICTURE', 'DETAIL_PICTURE'
        ]);

        while ($element = $elements->GetNext()) {
            try {
                $propValues = new ElementValues(IBLOCK_CATALOG, $element['ID']);
                $element['IPROPERTY_VALUES'] = $propValues->getValues();
                $sizes = Helper\Main::getHLObject(HL_SIZES)::query()
                    ->setSelect(['UF_SIZE', 'UF_PRICE_1', 'SIZE_ID' => 'ID'])
                    ->where('UF_PRODUCT', $element['ID'])
                    ->fetchAll();
                foreach ($sizes as $size) {
                    $offers->appendChild($this->getOffer([...$element, ...$size]));
                }
            } catch (Throwable $e) {
                addUncaughtExceptionToLog($e);
            }
        }

        return $offers;
    }

    /**
     * @throws DOMException
     */
    private function getOffer(array $params): DOMElement|bool
    {
        $offer = $this->doc->createElement('offer');
        $offer->setAttribute('id', $params['SIZE_ID']);

        $name = "$params[NAME] $params[UF_SIZE]";
        $url = HOST . "{$params['DETAIL_PAGE_URL']}";
        $picture = CFile::ResizeImageGet(
            $params['DETAIL_PICTURE'] ?: $params['PREVIEW_PICTURE'],
            ['width' => 600, 'height' => 600]
        );

        $this->addSimpleTagToNode($offer, 'name', $name);
        $this->addSimpleTagToNode($offer, 'url', $url);
        $this->addSimpleTagToNode($offer, 'price', $params['UF_PRICE_1']);
        $this->addSimpleTagToNode($offer, 'currencyId', 'RUR');
        $this->addSimpleTagToNode($offer, 'categoryId', $params['IBLOCK_SECTION_ID']);
        if ($picture) {
            $this->addSimpleTagToNode($offer, 'picture', HOST . $picture['src']);
        }
        $this->addSimpleTagToNode(
            $offer,
            'description',
            $params['IPROPERTY_VALUES']['ELEMENT_META_DESCRIPTION'],
            [],
            true
        );
        return $offer;
    }

    /**
     * @throws DOMException
     */
    private function addSimpleTagToNode(
        DOMElement $parentNode,
        string     $tag,
        string     $value,
        array      $attrs = [],
        bool       $cData = false
    ): void
    {
        if ($cData) {
            $node = $this->doc->createElement($tag);
            $node->appendChild($this->doc->createCDATASection(htmlspecialchars($value)));
        } else {
            $node = $this->doc->createElement($tag, htmlspecialchars($value));
        }
        if ($attrs) {
            foreach ($attrs as $name => $value) {
                $node->setAttribute($name, $value);
            }
        }
        $parentNode->appendChild($node);
    }

    public static function agent(): string
    {
        (new Feed())->createFIle();
        return sprintf('%s::%s()', self::class, __FUNCTION__);
    }

}
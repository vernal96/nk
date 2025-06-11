<?php

use Bitrix\Iblock\Component\Tools;
use Bitrix\Iblock\InheritedProperty\ElementValues;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Type\Date as BitrixDate;
use DK\NK\ActionFilter\Csrf;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Web\Json;
use DK\NK\Helper\Catalog;
use DK\NK\Helper\Iblock;
use DK\NK\Helper\Main;
use DK\NK\Helper\SEO;

class DKCatalogDetail extends CBitrixComponent implements Controllerable
{

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent(): void
    {
        global $APPLICATION;
        $taggedCache = Application::getInstance()->getTaggedCache();
        if ($this->startResultCache(false, $this->arParams["CODE"])) {
            $taggedCache->startTagCache($this->getCachePath());

            $rsElement = CIBlockElement::GetList([], [
                "IBLOCK_ID" => $this->arParams["IBLOCK_ID"],
                "ACTIVE" => "Y",
                "SECTION_GLOBAL_ACTIVE" => "Y",
                "CODE" => $this->arParams["CODE"],
            ]);

            $objElement = $rsElement->getNextElement(true, false);

            if (!$objElement) {
                Tools::process404();
                $this->abortResultCache();
                $taggedCache->abortTagCache();
                return;
            }
            $arFields = $objElement->GetFields();
            $noImage = Main::getFileIdBySrc(Option::get("nk.main", "NOPHOTO"));
            $arFields["DETAIL_PICTURE"] = ($arFields["DETAIL_PICTURE"] ?: $arFields["PREVIEW_PICTURE"]) ?: $noImage;
            $arFields["PREVIEW_PICTURE"] = ($arFields["PREVIEW_PICTURE"] ?: $arFields["DETAIL_PICTURE"]) ?: $noImage;

            $this->arResult = Iblock::setResultFields($arFields);
            $this->arResult["PROPERTIES"] = $objElement->GetProperties();

            $this->arResult["PRICES"] = Catalog::getProductPrices($arFields["ID"]);

            $this->arResult["RECOMMEND"] = $this->arResult["PROPERTIES"]["RECOMMEND"]["VALUE"];
            $this->arResult['JSON_LD'] = $this->getJsonLd($objElement);
            $taggedCache->registerTag("iblock_id_" . $this->arParams["IBLOCK_ID"]);
            $taggedCache->endTagCache();
            $this->endResultCache();
        }
        $this->arResult['IS_AJAX_REQUEST'] = (bool)$this->request->get(BX_AJAX_PARAM_ID);
        $this->includeComponentTemplate();
        if ($iprop = $this->arResult["IPROPERTY_VALUES"]) {
            $APPLICATION->SetPageProperty("TITLE", $iprop["ELEMENT_META_TITLE"]);
            $APPLICATION->SetPageProperty("description", $iprop["ELEMENT_META_DESCRIPTION"]);
            $APPLICATION->SetPageProperty("keywords", $iprop["ELEMENT_META_KEYWORDS"]);
            $APPLICATION->SetPageProperty("canonical", $this->arResult["CANONICAL_PAGE_URL"]);
        }

        $APPLICATION->AddChainItem($this->arResult["NAME"], $this->arResult["DETAIL_PAGE_URL"]);
    }

    private function getJsonLd(_CIBElement $element): string {
        $fields = $element->GetFields();
        $pictureSrc = CFile::GetPath($fields['DETAIL_PICTURE'] ?: $fields['PREVIEW_PICTURE']);
        $seo = (new ElementValues($fields['IBLOCK_ID'], $fields['ID']))->getValues();

        $result = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $fields["NAME"],
            'description' => $seo['ELEMENT_META_DESCRIPTION'],
            'offers' => $this->getJsonLdOffers($fields)
        ];
        if ($pictureSrc) $result['image'] = HOST . $pictureSrc;
        try {
            return Json::encode($result);
        } catch (Exception) {
            return '{}';
        }
    }

    private function getJsonLdOffers(array $element): array {
        $result = [];
        try {
            $sizes = Main::getHLObject(HL_SIZES)::query()
                ->addSelect('*')
                ->where('UF_PRODUCT', $element['ID'])
                ->fetchAll();
        } catch (Exception) {
            $sizes = [];
        }

        $priceValidUntil = (new BitrixDate())->add('+1 year')->format('Y-m-d');

        foreach ($sizes as $size) {
            $result[] = [
                '@type' => 'Offer',
                'url' => HOST . $element['DETAIL_PAGE_URL'],
                'priceCurrency' => 'RUB',
                'price' => $size['UF_PRICE_1'],
                'itemCondition' => 'https://schema.org/NewCondition',
                'sku' => $size['UF_CODE'],
                'description' => $size['UF_SIZE'],
                'priceValidUntil' => $priceValidUntil,
                'availability' => 'https://schema.org/InStock'
            ];
        }
        return $result;
    }

    public function configureActions(): array
    {
        return [
            'getPriceTable' => [
                'prefilters' => [
//                    new Csrf()
                ]
            ]
        ];
    }
}
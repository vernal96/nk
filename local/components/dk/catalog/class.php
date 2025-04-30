<?php

use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Loader;
use DK\NK\Cart;
use DK\NK\Helper\Catalog;
use DK\NK\Helper\Main;

class DKCatalogComponent extends CBitrixComponent implements Controllerable
{


    protected array $arComponentVariables = [
        'CODE_SECTION',
        'PAGE'
    ];

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent()
    {
        global $APPLICATION;
        Loader::includeModule('iblock');
        $componentPage = $this->arParams["SEF_MODE"] === "Y" ? $this->sefMode() : $this->noSefMode();
        if (!$componentPage && $APPLICATION->GetCurPage() != $this->arParams["SEF_FOLDER"]) {
            Tools::process404();
        } elseif ($componentPage == "sectionStart" || $componentPage == "root") {
            $componentPage = "section";
        } elseif (!$componentPage) {
            $componentPage = "section";
        }
        if ($this->arResult["VARIABLES"]["PAGE"] == 1) {
            Tools::process404();
        }
        $this->IncludeComponentTemplate($componentPage);
    }

    protected function sefMode(): false|string
    {
        $arDefaultVariableAliases404 = [];

        $arDefaultUrlTemplates404 = [
            "section" => "#SECTION_CODE#/page-#PAGE#/",
            "root" => "page-#PAGE#/",
            "sectionStart" => "#SECTION_CODE#/",
            "element" => "#SECTION_CODE#/#ELEMENT_CODE#",
        ];
        $arVariables = [];
        $engine = new CComponentEngine($this);
        $arUrlTemplates = CComponentEngine::makeComponentUrlTemplates(
            $arDefaultUrlTemplates404,
            $this->arParams["SEF_URL_TEMPLATES"]
        );
        $arVariableAliases = CComponentEngine::makeComponentVariableAliases(
            $arDefaultVariableAliases404,
            $this->arParams["VARIABLE_ALIASES"]
        );
        $componentPage = $engine->guessComponentPath(
            $this->arParams["SEF_FOLDER"],
            $arUrlTemplates,
            $arVariables
        );
        CComponentEngine::initComponentVariables(
            $componentPage,
            $this->arComponentVariables,
            $arVariableAliases,
            $arVariables
        );
        $this->arResult = [
            "VARIABLES" => $arVariables,
            "ALIASES" => $arVariableAliases
        ];
        return $componentPage;
    }

    protected function noSefMode(): string
    {
        $componentPage = "";
        $arDefaultVariableAliases = [];
        $arVariableAliases = CComponentEngine::makeComponentVariableAliases(
            $arDefaultVariableAliases,
            $this->arParams["VARIABLE_ALIASES"]
        );
        $arVariables = [];
        CComponentEngine::initComponentVariables(
            false,
            $this->arComponentVariables,
            $arVariableAliases,
            $arVariables
        );
        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        $rDir = $request->getRequestedPageDirectory();
        if ($arVariableAliases["CATALOG_URL"] == $rDir) {
            $componentPage = "section";
        }
        if ((isset($arVariables["ELEMENT_ID"]) && intval($arVariables["ELEMENT_ID"]) > 0) || (isset($arVariables["ELEMENT_CODE"]) && $arVariables["ELEMENT_CODE"] <> '')) {
            $componentPage = "element";
        }
        if ((isset($arVariables["SECTION_ID"]) && intval($arVariables["SECTION_ID"]) > 0) || (isset($arVariables["SECTION_CODE"]) && $arVariables["SECTION_CODE"] <> '')) {
            $componentPage = "section";
        }
        $this->arResult = [
            "VARIABLES" => $arVariables,
            "ALIASES" => $arVariableAliases
        ];
        return $componentPage;
    }

    public function getTreeAction(): array
    {
        $isHeader = (bool)$this->request->get('header');
        return Catalog::getCompactTree($isHeader);
    }

    public function getProductsAction(): ?array
    {
        return Catalog::getCompactProducts(
            $this->request->get("sectionId"),
            $this->request->get("pageSize"),
            $this->request->get("page")
        );

    }

    public function getPricesAction(): array
    {
        $data = null;
        $sizes = array_map(function ($size) {
            $cartCount = Cart::getInstance()->getSizeCount(+$size["ID"], +$size["UF_PRODUCT"]);
            $cartSum = Cart::getInstance()->getSizeSum(+$size["ID"], +$size["UF_PRODUCT"]);
            return [
                "id" => +$size["ID"],
                "title" => $size["UF_SIZE"],
                "box" => $size["UF_BOX_COUNT"],
                "price" => Main::priceFormat($size["UF_PRICE_" . Main::getUserType()]),
                "cart" => [
                    "count" => [
                        "value" => $cartCount,
                        "format" => Main::numberFormat($cartCount)
                    ],
                    "sum" => [
                        "value" => $cartSum,
                        "format" => Main::priceFormat($cartSum)
                    ]
                ]
            ];
        }, Catalog::getProductPrices($this->request->get("id"), $this->request->get("sizes") ?: []));
        if ($this->request->get("full")) {
            $data = Catalog::getProduct($this->request->get("id"));

            $image = $data["PREVIEW_PICTURE"] ?: Main::getFileIdBySrc(Option::get(NK_MODULE_NAME, "NOPHOTO"));

            $data = [
                "image" => CFile::ResizeImageGet($image, ["width" => 60, "height" => 60], 2)["src"],
                "fullImage" => $data["PREVIEW_PICTURE"]["SRC"],
                "title" => $data["~NAME"],
                "url" => $data["DETAIL_PAGE_URL"]
            ];
        }
        return [
            "sizes" => $sizes,
            "data" => $data
        ];
    }

    public function cartUpdateAction(): array
    {
        $cart = Cart::getInstance();
        $sizeId = (int)$this->request->get("id");
        $productId = (int)$this->request->get("productId");
        return $cart->set($sizeId, $productId, (int)$this->request->get("count"));
    }

    public function configureActions(): array
    {
        return [
            "getTree" => [
                "prefilters" => [new Csrf()]
            ],
            "getProducts" => [
                "prefilters" => [new Csrf()]
            ],
            "getPrices" => [
                "prefilters" => [new Csrf()]
            ],
            "cartUpdate" => [
                "prefilters" => [new Csrf()]
            ]
        ];
    }
}
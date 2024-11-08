<?php

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Mail\Event;
use DK\NK\Cart;
use DK\NK\Helper\Main;
use DK\NK\Services\Bitrix24;
use DK\NK\Services\DaData;
use DK\NK\Valid;

class DKCartComponent extends CBitrixComponent implements Controllerable
{

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent(): void
    {
        $this->includeComponentTemplate();
    }

    public function getDeliveryCitiesAction(): array
    {
        $deliveryCitiesCollection = Main::getHLObject(HL_DELIVERY_CITIES)::query()
            ->addSelect("UF_NAME")
            ->setOrder(["UF_SORT" => "ASC", "UF_NAME" => "ASC", "ID" => "ASC"])
            ->setCacheTtl(CACHE_TIME)
            ->fetchCollection();
        $result = [];
        foreach ($deliveryCitiesCollection as $deliveryCity) {
            $result[] = [
                "value" => $deliveryCity["UF_NAME"],
                "title" => $deliveryCity["UF_NAME"]
            ];
        }
        return $result;
    }

    public function getMarketsAction(): array
    {
        $marketCollection = ElementTable::query()
            ->setSelect(["NAME", "ID"])
            ->where("IBLOCK_ID", IBLOCK_MARKET)
            ->where("ACTIVE", true)
            ->setOrder(["SORT" => "ASC", "ID" => "ASC"])
            ->setCacheTtl(CACHE_TIME)
            ->fetchCollection();
        $result = [];
        foreach ($marketCollection as $market) {
            $result[] = [
                "value" => (string)$market->getId(),
                "title" => $market->getName()
            ];
        }
        return $result;
    }

    public function getAgreeTextAction(): string
    {
        ob_start();
        Main::include("form_confirm", ["TEXT_ONLY" => true]);
        return ob_get_contents();
    }

    public function getTotalAction(): array
    {
        return [
            "sum" => Cart::getInstance()->getTotalSum(),
            "count" => Cart::getInstance()->getTotalCount(),
        ];
    }

    public function getItemsAction(): array
    {
        $cart = Cart::getInstance();
        return $cart->getProducts();
    }

    public function getCompanyByInnAction()
    {
        return DaData::getRqByInn((int)$this->request->get("inn")) ?: null;
    }

    public function getUserDataAction(): ?array
    {
        return Cart::getInstance()->getUserData();
    }

    public function saveOrderFieldAction(): void
    {
        $field = $this->request->get("field");
        $value = $this->request->get("value");
        Cart::getInstance()->setUserField($field, $value);
    }

    public function submitAction(): array
    {
        $errorFields = [];
        $fields = Cart::getInstance()->getUserData();
        $fastPay = $this->request->get("fastpay");

        if (!Valid::notEmpty($fields["name"])) $errorFields[] = "name";
        if (!Valid::phone($fields["phone"])) $errorFields[] = "phone";
        if ($fields["email"]) {
            if (!Valid::email($fields["email"])) $errorFields[] = "email";
        }
        if (!Valid::notEmpty($fields["ft"])) $errorFields[] = "ft";
        else {
            if ($fields["ft"] == "jur") {
                if (!Valid::notEmpty($fields["inn"])) {
                    $errorFields[] = "inn";
                } else {
                    if (!DaData::getRqByInn($fields["inn"])) $errorFields[] = "inn";
                }
            }
        }
        if ($fields["delivery"] == "self") {
            if (!Valid::market($fields["marketId"])) $errorFields[] = "marketId";
        } else {
            if (!Valid::city($fields["city"])) $errorFields[] = "city";
            if (!Valid::notEmpty($fields["street"])) $errorFields[] = "street";
            if (!Valid::notEmpty($fields["house"])) $errorFields[] = "house";
        }

        if (!empty($errorFields)) {
            return [
                "success" => false,
                "fields" => $errorFields
            ];
        }

        $this->sendOrder($fastPay);

        if (Cart::getInstance()->getDeal()) {
            if ($fastPay) {
                ob_start();
                Main::include("form_success", [
                    "successTitle" => Loc::getMessage("FASTPAY_SUCCESS_TITLE", ["#DEAL#" => Cart::getInstance()->getDeal()]),
                    "successDescription" => Loc::getMessage("FASTPAY_SUCCESS_DESCRIPTION")
                ]);
                return [
                    "success" => true,
                    "message" => ob_get_clean()
                ];
            } else {
                Cart::getInstance()->destroy();
                return [
                    "success" => true
                ];
            }
        } else {
            return [
                "success" => false,
                "error" => Loc::getMessage("ERROR_SUBMIT_BX24")
            ];
        }


    }

    private function sendOrder($fastPay): void
    {
        $cart = Cart::getInstance();
        $bx24 = new Bitrix24();
        $userData = $cart->getUserData();

        $responsibleId = Option::get(NK_MODULE_NAME, "BX24_FEEDBACK_RESPONSIBLE");
        $deliveryData = [];
        if ($userData["delivery"] == "self") {
            if ($userData["marketId"]) {
                $market = CIBlockElement::GetByID($userData["marketId"])->GetNextElement();
                $marketFields = $market->getFields();
                $marketProperties = $market->GetProperties(false, ["CODE" => "RESPONSIBLE"]);
                $responsibleId = $marketProperties["RESPONSIBLE"]["VALUE"] ?: $responsibleId;
                $deliveryData["UF_CRM_1729167948"] = $marketFields["NAME"];
                $deliveryData["UF_CRM_1729167813"] = 47;
            }
        } else {
            $deliveryData = [
                "UF_CRM_1729167813" => 45,
                "UF_CRM_1729019615" => $userData["city"], // город
                "UF_CRM_1729019691" => $userData["street"], // улица
                "UF_CRM_1729019698" => $userData["house"], // дом
                "UF_CRM_1729019705" => $userData["corpus"], // корпус
                "UF_CRM_1729019711" => $userData["entrance"], // подъезд
                "UF_CRM_1729019720" => $userData["office"], // квартира
            ];
        }

        $cartItems = $cart->getList();

        $companyId = 0;
        if ($userData["ft"] == "jur") {
            $companyId = \DK\NK\Helper\Bitrix24::addCompany(["inn" => $userData["inn"], "email" => [$userData["email"]]]);
        }

        $contactList = \DK\NK\Helper\Bitrix24::addContact([
            "name" => $userData["name"],
            "phone" => $userData["phone"],
            "email" => $userData["email"],
            "companyId" => $companyId
        ], $bx24);

        $filesData = [];
        if ($_FILES["files"] && $_FILES["files"]["size"] < Option::get(NK_MODULE_NAME, "MAX_FILE_SIZE") * 1000000) {
            $filesData["UF_CRM_1729236355"] = [
                "fileData" => [
                    $_FILES["files"]["name"],
                    base64_encode(file_get_contents($_FILES["files"]["tmp_name"]))
                ]
            ];
        }

        $bx24->batchAdd("deal", "crm.deal.add", ["fields" => [
            "COMMENTS" => $userData["comment"] ?? "",
            "IS_NEW" => "Y",
            "SOURCE_ID" => "WEB",
            "ASSIGNED_BY_ID" => $responsibleId,
            "OPPORTUNITY" => $cart->getTotalSum()["value"],
            "IS_MANUAL_OPPORTUNITY" => "Y",
            "OPENED" => "Y", // заменить
            "UF_CRM_1700415164161" => $fastPay ? null : $this->getOrderFile($cartItems),
            ...$deliveryData,
            ...$filesData
        ]], 20);

        $bx24->batchAdd("deal.contact", "crm.deal.contact.add", [
            "id" => '$result[deal]',
            "fields" => [
                "CONTACT_ID" => empty($contactList) ? '$result[contact]' : $contactList[0]["ID"]
            ]
        ], 30);
        if (!$fastPay) {
            $bx24->batchAdd("products", "crm.deal.productrows.set", [
                "id" => '$result[deal]',
                "rows" => $this->addProductsToOrder($cartItems)
            ]);
        }

        $bx24->batchCall();
        $dealId = $bx24->batchResult[0]["result"]["result"]["deal"];
        if ($dealId) {
            $cart->setDeal($dealId);
            $this->sendUserEmail();
        }
    }

    private function sendUserEmail(): void
    {
        $cart = new Cart();
        $userData = $cart->getUserData();
        if (!$userData["email"]) return;

        $items = "";

        ob_start();
        foreach ($cart->getList() as $product) {
            if (!$product["count"]) continue;
            ob_start();
            Main::include("product", [
                "IMAGE" => $product["image"],
                "NAME" => $product["name"] . " " . $product["size"],
                "COUNT" => $product["count"],
                "SUM" => $product["price" . Main::getUserType()] * $product["count"],
            ], EMAIL_TEMPLATE_PATH);
            $items .= ob_get_clean();
        }

        Event::send([
            "EVENT_NAME" => "ORDER_SUCCESS",
            "LID" => SITE_ID,
            "C_FIELDS" => [
                "EMAIL" => $userData["email"],
                "NUMBER" => $cart->getDeal(),
                "TOTAL_SUM" => $cart->getTotalSum()["format"],
                "ITEMS" => $items,
            ]
        ]);
    }

    private function getOrderFile(array $cartItems): array
    {
        $result = "Array\n(\n";

        foreach ($cartItems as $index => $item) {
            if (!$item["count"]) continue;
            $sum = $item["count"] * $item["price" . Main::getUserType()];
            $result .= "\t[$index] => ;$item[xml] ; $item[name] ; $item[size] ; $item[price1] ; $item[price2] ; $item[price3] ; $item[count] ; $sum ;\n";
        }
        $result .= ")";
        $result = iconv("utf-8", "cp1251", $result);

        return [
            "fileData" => [
                "Заказ.csv",
                base64_encode($result)
            ]
        ];

    }

    private function addProductsToOrder(array $cartItems): array
    {

        $bx24 = new Bitrix24();

        $bx24ProductsIdList = $bx24->query("crm.product.list", [
            "filter" => [
                "XML_ID" => array_column($cartItems, "xml")
            ],
            "select" => ["XML_ID", "ID"]
        ], "result");

        if ($bx24ProductsIdList["error"]) throw new Exception($bx24ProductsIdList["error_description"]);

        $rows = [];
        foreach ($cartItems as $item) {
            $bxFilter = array_filter($bx24ProductsIdList, fn($bxItem) => $bxItem["XML_ID"] == $item["xml"]);
            if (empty($bxFilter)) continue;
            $bxItem = current($bxFilter);
            $rows[] = [
                "PRODUCT_ID" => $bxItem["ID"],
                "PRICE" => $item["price" . Main::getUserType()],
                "QUANTITY" => $item["count"]
            ];
        }

        return $rows;
    }

    public function configureActions(): array
    {
        return [
            "getDeliveryCities" => [
                "prefilters" => [new Csrf()]
            ],
            "getMarkets" => [
                "prefilters" => [new Csrf()]
            ],
            "getAgreeText" => [
                "prefilters" => [new Csrf()]
            ],
            "getItems" => [
                "prefilters" => [new Csrf()]
            ],
            "getTotal" => [
                "prefilters" => [new Csrf()]
            ],
            "getCompanyByInn" => [
                "prefilters" => [new Csrf()]
            ],
            "getUserData" => [
                "prefilters" => [new Csrf()]
            ],
            "saveOrderField" => [
                "prefilters" => [new Csrf()]
            ],
            "submit" => [
                "prefilters" => [new Csrf()]
            ],
        ];
    }
}
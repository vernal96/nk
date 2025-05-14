<?php

use Bitrix\Iblock\ElementTable;
use DK\NK\ActionFilter\Csrf;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use DK\NK\Cart;
use DK\NK\FieldsException;
use DK\NK\Helper\Main;
use DK\NK\Order;
use DK\NK\Services\DaData;

class DKCartComponent extends CBitrixComponent implements Controllerable
{

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent(): void
    {
        if ($this->startResultCache()) {
            $this->arResult['ITEMS'] = $this->arParams['ITEMS']
                ? Main::getHLObject(HL_DELIVERY_INFO)::query()
                    ->setSelect(['title' => 'UF_TITLE', 'description' => 'UF_DESCRIPTION'])
                    ->addOrder('UF_SORT')
                    ->whereIn('ID', $this->arParams['ITEMS'])
                    ->fetchAll()
                : [];

            $this->setResultCacheKeys([]);
            $this->endResultCache();
        }
        $this->includeComponentTemplate();
    }

    public function getDeliveryCitiesAction(): array
    {
        $deliveryCitiesCollection = Main::getHLObject(HL_DELIVERY_CITIES)::query()
            ->setSelect(["UF_NAME", "ID"])
            ->setOrder(["UF_SORT" => "ASC", "UF_NAME" => "ASC", "ID" => "ASC"])
            ->setCacheTtl(CACHE_TIME)
            ->fetchCollection();
        $result = [];
        foreach ($deliveryCitiesCollection as $deliveryCity) {
            $result[] = [
                "value" => $deliveryCity["ID"],
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
        return ob_get_clean();
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

    public function getCompanyByInnAction(): ?array
    {
        try {
            return (new DaData())->getRqByInn((int)$this->request->get("inn")) ?: null;
        } catch (Throwable) {
            return null;
        }
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

    /**
     * @throws LoaderException
     */
    public function submitAction(): array
    {
        $fastPay = (bool)$this->request->get("fastpay");
        $cart = Cart::getInstance();
        $order = new Order($cart);
        try {
            $number = $order->addOrder(!$fastPay);

            $message = '';

            $order->sendManagerEmail();
            $order->sendClientEmail();

            if ($fastPay) {
                ob_start();
                Main::include("form_success", [
                    "successTitle" => Loc::getMessage("FASTPAY_SUCCESS_TITLE", [
                        "#DEAL#" => Main::getApplicationFormat($number)
                    ]),
                    "successDescription" => Loc::getMessage("FASTPAY_SUCCESS_DESCRIPTION")
                ]);
                $message = ob_get_clean();
            } else {
                $cart->setDeal(Main::getApplicationFormat($number));
                $cart->destroy();
            }

            return [
                "success" => true,
                'message' => $message,
                'sum' => $cart->getTotalSum()
            ];
        } catch (FieldsException $exception) {
            return [
                "success" => false,
                "fields" => $exception->getFields()
            ];
        } catch (Throwable $exception) {
            addUncaughtExceptionToLog($exception);
            return [
                "success" => false,
                "fields" => [],
                "error" => Loc::getMessage('UNCAUGHT_ERROR')
            ];
        }
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
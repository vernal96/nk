<?php

namespace DK\NK;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Session\SessionInterface;
use CUser;
use DK\NK\Entity\CartTable;
use DK\NK\Helper\Catalog;
use DK\NK\Helper\Main;

class Cart
{

    private static self $instance;
    private array $cart;
    private array $order;

    private array $totalSum;
    private array $totalCount;
    private SessionInterface $session;

    private ?array $userData = null;

    private string $cartSessionName = "CART";
    private string $orderSessionName = "ORDER";
    private string $dealSessionName = "DEAL";
    private string $lastUpdateSessionName = "LAST_UPDATE";

    public function __construct()
    {
        $this->session = Application::getInstance()->getSession();
        $this->setUserData();
        $this->cartInit();
        $this->orderInit();
        $this->setTotalSum();
        $this->setTotalCount();
    }

    public static function getInstance(): self
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function setUserData(): void
    {
        global $USER;
        $cache = Cache::createInstance();
        $taggedCache = Application::getInstance()->getTaggedCache();
        $cacheUnique = "dk.users." . $USER->GetID();
        $cachePath = SITE_ID . "/dk/users";
        $userData = null;
        if ($USER->IsAuthorized()) {
            if ($cache->initCache(CACHE_TIME, $cacheUnique, $cachePath)) {
                $userData = $cache->getVars();
            } elseif ($cache->startDataCache()) {

                $taggedCache->startTagCache($cachePath);

                $userData = CUser::GetList("", "", ["ID" => $USER->GetID()], [
                    "SELECT" => ["street" => "UF_STREET", "home" => "UF_HOME", "corpus" => "UF_CORPUS", "entrance" => "UF_ENTRANCE", "office" => "UF_OFFICE"],
                    "FIELDS" => ["id" => "ID", "NAME", "LAST_NAME", "SECOND_NAME", "email" => "EMAIL", "phone" => "PERSONAL_MOBILE", "city" => "PERSONAL_CITY", "inn" => "WORK_COMPANY"]
                ])->Fetch();
                if (!$userData) {
                    $taggedCache->endTagCache();
                    $cache->abortDataCache();
                } else {
                    $userData = [
                        "id" => $userData["ID"],
                        "name" => trim("$userData[LAST_NAME] $userData[NAME] $userData[SECOND_NAME]"),
                        "email" => $userData["EMAIL"],
                        "phone" => $userData["PERSONAL_MOBILE"],
                        "inn" => $userData["WORK_COMPANY"],
                        "city" => $userData["PERSONAL_CITY"],
                        "street" => $userData["UF_STREET"],
                        "house" => $userData["UF_HOME"],
                        "corpus" => $userData["UF_CORPUS"],
                        "entrance" => $userData["UF_ENTRANCE"],
                        "office" => $userData["UF_OFFICE"],
                    ];

                    $taggedCache->registerTag("user_" . $USER->GetID());
                    $taggedCache->endTagCache();
                    $cache->endDataCache($userData);
                }
            }
            $this->userData = $userData;
        }
    }

    public function getList(): array
    {
        $arItems = [];
        foreach ($this->cart as $items) {
            foreach ($items as $itemId => $count) {
                $arItems[$itemId] = $count;
            }
        }
        $itemCollection = Main::getHLObject(HL_SIZES)::query()
            ->setSelect(["*", "PRODUCT"])
            ->registerRuntimeField("PRODUCT", new Reference("PRODUCT", ElementTable::class, Join::on("this.UF_PRODUCT", "ref.ID")))
            ->whereIn("ID", array_keys($arItems))
            ->fetchCollection();
        $result = [];
        foreach ($itemCollection as $item) {
            $result[] = [
                "count" => $arItems[$item["ID"]],
                "size" => $item->getUfSize(),
                "xml" => $item->getUfCode(),
                "price1" => $item->get("UF_PRICE_1"),
                "price2" => $item->get("UF_PRICE_2"),
                "price3" => $item->get("UF_PRICE_3"),
                "box" => $item->getUfBoxCount(),
                "name" => $item->get("PRODUCT")->getName()
            ];

        }
        return $result;
    }

    private function cartInit(): void
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $this->cart = [];
            $cart = CartTable::query()->addSelect("CART")->where("USER_ID", $USER->GetID())->fetchObject();
            $this->cart = $cart ? $cart->getCart() : [];

        } else {
            $this->cart = $this->session->get($this->cartSessionName) ?: [];
        }
        $this->session->set($this->cartSessionName, $this->cart);
        $this->validActual();
    }

    public function set(int $id, int $productId, int $count): array
    {
        $productSizes = Catalog::getProductPrices($productId);
        if (!$productSizes) $this->getResponse();
        if (!in_array($id, array_column($productSizes, "ID"))) $this->getResponse();
        $this->cart[$productId][$id] = $count;
        $this->save();
        return $this->getResponse($id, $productId);
    }

    public function getResponse(int $id = null, int $productId = null): array
    {
        $totalSum = $this->getTotalSum();
        $totalCount = $this->getTotalCount();
        $result = [];
        if ($id && $productId) {
            $count = $this->cart[$productId][$id];
            $sum = $count * Catalog::getSizePrice($id, $productId);
            $result = [
                "count" => [
                    "value" => $count,
                    "format" => Main::numberFormat($count)
                ],
                "sum" => [
                    "value" => $sum,
                    "format" => Main::priceFormat($sum)
                ]
            ];
        }
        return [
            "total" => [
                "count" => $totalCount,
                "sum" => $totalSum,
            ],
            ...$result
        ];
    }

    public function getTotalSum(): array
    {
        return $this->totalSum;
    }

    public function getTotalCount(): array
    {
        return $this->totalCount;
    }

    private function save(): void
    {
        global $USER;
        if ($this->session->has($this->dealSessionName)) {
            $this->session->remove($this->dealSessionName);
        }
        $this->setTotalSum();
        $this->setTotalCount();
        Application::getInstance()->getSession()->set($this->cartSessionName, $this->cart);
        if ($USER->IsAuthorized()) {
            $cart = CartTable::query()->addSelect("ID")->where("USER_ID", $USER->GetID())->fetchObject();
            if ($cart) {
                $cart->setCart($this->cart)->save();
            } else {
                CartTable::createObject()
                    ->setCart($this->cart)
                    ->setUserId($USER->GetID())
                    ->save();
            }
        }
    }

    private function setTotalSum(): void
    {
        $sum = 0;
        foreach ($this->cart as $productId => $sizes) {
            foreach ($sizes as $id => $count) {
                $sum += $count * Catalog::getSizePrice($id, $productId);
            }
        }
        $this->totalSum = [
            "value" => $sum,
            "format" => $sum ? Main::priceFormat($sum, true) : Loc::getMessage("CART_EMPTY")
        ];
    }

    private function setTotalCount(): void
    {
        $count = 0;
        foreach ($this->cart as $sizes) {
            if ($sizes) {
                $count += array_sum($sizes);
            }
        }
        $this->totalCount = [
            "value" => $count,
            "format" => Main::numberFormat($count)
        ];
    }

    private function validActual(): void
    {
        $lastUpdate = Option::get(NK_MODULE_NAME, "LAST_UPDATE");
        if (!$this->session->has($this->lastUpdateSessionName)) {
            $this->session->set($this->lastUpdateSessionName, $lastUpdate);
        } else {
            if ($this->session->get($this->lastUpdateSessionName) === $lastUpdate) return;
            $this->session->set($this->lastUpdateSessionName, $lastUpdate);
            $newCart = [];
            foreach ($this->cart as $productId => $sizes) {
                $product = Catalog::getProduct($productId);
                if (!$product || $product["ACTIVE"] != "Y") continue;
                $actualSizes = Catalog::getProductPrices($productId);
                $actualIds = array_column($actualSizes, "ID");
                foreach ($sizes as $sizeId => $count) {
                    if (in_array($sizeId, $actualIds)) {
                        $newCart[$productId][$sizeId] = $count;
                    }
                }
            }
            $this->cart = $newCart;
            $this->save();
        }
    }

    private function orderInit(): void
    {
        if (!$this->session->get($this->orderSessionName)) {
            $this->order = [];
            if ($this->userData) {
                $this->order = $this->userData;
            }
            $this->session->set($this->orderSessionName, $this->order);
        } else {
            $this->order = $this->session->get($this->orderSessionName);
        }
    }

    public function getUserData(): ?array
    {
        return $this->order;
    }

    public function setUserField($field, $value): void
    {
        if (!$field) return;
        $this->order[$field] = $value;
        $this->session->set($this->orderSessionName, $this->order);
    }

    public function getProducts(): array
    {
        $result = [];
        foreach ($this->cart as $productId => $product) {
            if (array_sum($product)) {
                $result[] = [
                    "id" => $productId,
                    "sizes" => array_keys(array_filter($product, fn($prod) => $prod))
                ];
            }
        }
        return $result;
    }

    public function getSizeCount(int $id, int $productId): int
    {
        return $this->cart[$productId][$id] ?? 0;
    }

    public function getSizeSum(int $id, int $productId): int
    {
        if (!isset($this->cart[$productId][$id])) return 0;
        return $this->cart[$productId][$id] * Catalog::getSizePrice($id, $productId);
    }

    public function setDeal(int $deal): void
    {
        $deal = str_pad($deal, 7, "0", STR_PAD_LEFT);
        $this->session->set($this->dealSessionName, $deal);
    }

    public function getDeal(): string
    {
        return $this->session->get($this->dealSessionName) ?: "";
    }

    public function destroy(): void
    {
        global $USER;
        $this->session->remove($this->cartSessionName);
        $this->session->remove($this->orderSessionName);
        $this->cart = [];
        $this->order = [];
        $this->setTotalSum();
        $this->setTotalCount();
        if ($USER->IsAuthorized()) {
            CartTable::query()
                ->addSelect("ID")
                ->where("USER_ID", $USER->GetID())
                ->fetchObject()
                ?->setCart($this->cart)
                ->save();
        }
    }

}
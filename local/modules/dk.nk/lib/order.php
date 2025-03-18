<?php

namespace DK\NK;

use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\DB\SqlQueryException;
use Bitrix\Main\Entity\AddResult;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Mail\Event;
use Bitrix\Main\SystemException;
use Bitrix\Rest\RestException;
use CFile;
use DK\NK\Entity\OrderItemsTable;
use DK\NK\Entity\OrderTable;
use DK\NK\Helper\Main as MainHelper;
use DK\NK\Object\Order as ObjectOrder;
use DK\NK\Services\DaData;
use Throwable;

class Order
{

    private Cart $cart;
    private ObjectOrder $order;

    /**
     * @throws LoaderException
     */
    public function __construct(Cart $cart)
    {
        Loader::includeModule('iblock');
        $this->cart = $cart;
    }

    /**
     * @throws FieldsException
     * @throws SqlQueryException
     * @throws SystemException
     */
    public function addOrder($addItemsToOrder = true): int
    {
        $this->validUserData();
        $dbConnection = Application::getConnection();

        $dbConnection->startTransaction();

        $order = $this->createOrder();
        if ($addItemsToOrder) {
            $this->addItemsToOrder($order->getId());
        }

        $dbConnection->commitTransaction();
        $this->order = $order;
        return $order->getId();
    }

    public function sendManagerEmail(): AddResult
    {
        $params = [
            'EVENT_NAME' => 'ORDER_NEW',
            'C_FIELDS' => [
                'ORDER_ID' => $this->order->getId(),
                'NUMBER' => MainHelper::getApplicationFormat($this->order->getId())
            ],
            'LID' => SITE_ID
        ];
        if ($this->order->getFileId()) {
            $params['FILE'] = [$this->order->getFileId()];
        }
        logToFile($params);
        return Event::send($params);
    }

    public function sendClientEmail(): AddResult|bool
    {
        $userEmail = $this->cart->getUserData()['email'];
        if (!$userEmail) return false;
        return Event::send([
            'EVENT_NAME' => 'ORDER_SUCCESS',
            'C_FIELDS' => [
                'ORDER_ID' => $this->order->getId(),
                'EMAIL_TO' => $userEmail,
                'NUMBER' => MainHelper::getApplicationFormat($this->order->getId())
            ],
            'LID' => SITE_ID
        ]);
    }

    /**
     * @throws SystemException
     * @throws ArgumentException
     */
    private function createOrder(): ObjectOrder
    {
        $userData = $this->cart->getUserData();
        $newOrder = OrderTable::createObject()
            ->setName($userData['name'])
            ->setPhone(MainHelper::setFormatPhone($userData['phone']))
            ->setFt($userData['ft'] === 'jur' ? OrderTable::FT_LEGAL_ENTITY : OrderTable::FT_PHYSICAL_PERSON)
            ->setDelivery($userData['delivery'] === 'delivery' ? OrderTable::DELIVERY : OrderTable::SELF);

        if ($userData['id']) $newOrder->setUserId((int)$userData['id']);
        if ($userData['email']) $newOrder->setEmail($userData['email']);
        if ($userData['comment']) $newOrder->setComment($userData['comment']);

        if ($userData['delivery'] === 'delivery') {
            $newOrder->setCityId($userData['city']);
            $newOrder->setStreet($userData['street']);
            $newOrder->setHouse($userData['house']);
            $newOrder->setCorpus($userData['corpus']);
            $newOrder->setEntrance($userData['entrance']);
            $newOrder->setOffice($userData['office']);
        } else {
            $newOrder->setMarketId((int)$userData['marketId']);
        }

        if (
            $_FILES['files']
            && !$_FILES['files']['error']
            && $_FILES['files']['size'] < Option::get(NK_MODULE_NAME, 'MAX_FILE_SIZE') * 1000000
        ) {
            $fileId = CFile::SaveFile($_FILES['files'], 'order/user_files');
            if ($fileId) {
                $newOrder->setFileId($fileId);
            }
        }
        $result = $newOrder->save();
        if ($result->isSuccess()) {
            return $newOrder;
        } else {
            throw new SystemException($result->getError()->getMessage());
        }
    }

    /**
     * @throws SystemException
     * @throws ArgumentException
     */
    private function addItemsToOrder(int $orderId): void
    {
        $items = $this->cart->getList();
        foreach ($items as $item) {
            if (!$item['count']) continue;
            OrderItemsTable::createObject()
                ->setOrderId($orderId)
                ->setItemId($item['id'])
                ->setCount($item['count'])
                ->setPrice($item['price' . Main::getUserType()])
                ->save();
        }
    }

    /**
     * @throws FieldsException
     */
    private function validUserData(): void
    {
        $errorFields = [];
        $fields = $this->cart->getUserData();

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
        if ($errorFields) {
            throw new FieldsException($errorFields);
        }
    }

    /**
     * @throws RestException
     */
    public static function getList(): array
    {
        try {
            $result = [];
            $orders = OrderTable::query()
                ->setSelect(['*', 'ITEMS', 'CITY', 'MARKET', 'ITEMS.SIZE'])
                ->where('REGISTERED_IC', false)
                ->fetchCollection();
            foreach ($orders as $order) {
                $items = [];
                foreach ($order->getItems() as $item) {
                    $items[] = [
                        'code' => $item->getSize()?->getUfCode(),
                        'count' => $item->getCount(),
                        'price' => $item->getPrice(),
                    ];
                }
                $result[] = [
                    'id' => $order->getId(),
                    'dateTime' => $order->getCreatedDate()->format(DATE_ATOM),
                    'name' => $order->getName(),
                    'phone' => $order->getPhone(),
                    'email' => $order->getEmail(),
                    'inn' => $order->getInn(),
                    'comment' => $order->getComment(),
                    'file' => $order->getFileId() ? ($_SERVER['SERVER_ADDR'] . CFile::GetPath($order->getFileId())) : null,
                    'ft' => [
                        'code' => $order->getFt(),
                        'text' => $order->getTextFt()
                    ],
                    'delivery' => [
                        'code' => $order->getDelivery(),
                        'text' => $order->getTextDelivery()
                    ],
                    'deliveryData' => [
                        'city' => [
                            'id' => $order->getCityId(),
                            'text' => $order->getCity()?->getUfName()
                        ],
                        'street' => $order->getStreet(),
                        'house' => $order->getHouse(),
                        'corpus' => $order->getCorpus(),
                        'entrance' => $order->getEntrance(),
                        'office' => $order->getOffice(),
                        'market' => [
                            'code' => $order->getMarket()?->getCode(),
                            'text' => $order->getMarket()?->getName()
                        ]
                    ],
                    'items' => $items
                ];
            }
            return $result;
        } catch (Throwable $exception) {
            addUncaughtExceptionToLog($exception);
            throw new RestException('Unknown error', 'UNKNOWN_ERROR');
        }

    }

    /**
     * @throws RestException
     */
    public static function registerIn1C(array $fields): bool
    {
        try {
            $id = $fields['id'];
            if (!$id || !is_numeric($id)) {
                throw new RestException('The order Id is not specified', 'ORDER_ID_NOT_SPECIFIED');
            }
            $order = OrderTable::getById($id)->fetchObject();
            if (!$order) {
                throw new RestException('Order not found', 'ORDER_NOT_FOUND');
            }
            $saveResult = $order->setRegisteredIc(true)->save();
            return $saveResult->isSuccess();
        } catch (RestException $exception) {
            throw new RestException($exception->getMessage(), $exception->getErrorCode());
        } catch (Throwable $exception) {
            addUncaughtExceptionToLog($exception);
            throw new RestException('Unknown error', 'UNKNOWN_ERROR');
        }
    }

    public static function registerInBitrix24(): string
    {
        if (BITRIX24_DISABLED) return __METHOD__ . '();';
        try {
            $orders = OrderTable::query()
                ->setSelect(['*'])
                ->whereNull('BITRIX_ID')
                ->fetchCollection();
            foreach ($orders as $order) {
                $bx24Order = new OrderBitrix24($order);
                try {
                    $orderId = $bx24Order->addOrder();
                    if ($orderId) {
                        $order->setBitrixId($orderId)->save();
                    }
                } catch (SystemException) {
                }
            }
        } catch (SystemException $exception) {
            addUncaughtExceptionToLog($exception);
        } finally {
            return __METHOD__ . '();';
        }
    }

}
<?php

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use DK\NK\Entity\OrderTable;
use DK\NK\Helper\Main as MainHelper;
use DK\NK\Object\Order;

class EmailOrderComponent extends CBitrixComponent
{

    private Order $order;

    public function executeComponent(): void
    {
        try {
            $order = $this->getOrder();
            if (!$order) return;
            $this->order = $order;
            $this->arResult['NUMBER'] = MainHelper::getApplicationFormat($order->getId());
            $this->arResult['ITEMS'] = $this->getItems();
            $this->arResult['USER_DATA'] = $this->getUserData();
            $this->arResult['DELIVERY_DATA'] = $this->getDeliveryData();
            $this->arResult['TOTAL_SUM'] = MainHelper::priceFormat($order->getTotalSum(), true);
            $this->includeComponentTemplate();
        } catch (Throwable) {
        }
    }

    private function getItems(): array
    {
        $result = [];
        foreach ($this->order->getItems() as $item) {
            $item->fillSize();
            $size = $item->getSize();
            $size->fillProduct();
            $product = $size->getProduct();

            $imageId = $product->getDetailPicture()
                ?: $product->getPreviewPicture()
                    ?: MainHelper::getFileIdBySrc(Option::get(NK_MODULE_NAME, "NOPHOTO"));

            $result[] = [
                'IMAGE' => CFile::ResizeImageGet(
                    $imageId, ["width" => 40, "height" => 40], BX_RESIZE_IMAGE_EXACT
                )['src'],
                'NAME' => "{$product->getName()} {$size->getUfSize()}",
                'COUNT' => $item->getCount(),
                'SUM' => MainHelper::priceFormat($item->getCount() * $item->getPrice())
            ];
        }
        return $result;
    }

    /**
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private function getOrder(): ?Order
    {
        if (!$this->arParams['ORDER_ID']) return null;
        return OrderTable::query()
            ->setSelect(['*', 'FILE_ID', 'ITEMS', 'ITEMS.SIZE', 'ITEMS.SIZE.PRODUCT', 'MARKET.NAME', 'CITY'])
            ->where('ID', $this->arParams['ORDER_ID'])
            ->fetchObject();
    }

    private function getUserData(): array
    {
        $order = $this->order;
        return array_filter([
            'Имя' => $order->getName(),
            'E-mail' => $order->getEmail(),
            'Телефон' => $order->getPhone(),
            'ИНН' => $order->getInn(),
            'Тип лица' => $order->getTextFt(),
            'Комментарий' => $order->getComment()
        ], fn($property) => $property);
    }

    private function getDeliveryData(): array
    {
        $order = $this->order;
        return array_filter([
            'Вид доставки' => $order->getTextDelivery(),
            'Магазин' => $order->getMarket()?->getName(),
            'Город' => $order->getCity()?->getUfName(),
            'Улица' => $order->getStreet(),
            'Дом' => $order->getHouse(),
            'Корпус' => $order->getCorpus(),
            'Подъезд' => $order->getEntrance(),
            'Квартира/офис' => $order->getOffice()
        ], fn($property) => $property);
    }
}
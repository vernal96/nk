<?php

namespace DK\NK\Object;

use DK\NK\Entity\EO_Order;
use DK\NK\Entity\OrderTable;

class Order extends EO_Order
{

    public function getTextFt(): string
    {
        if ($this->getFt() === OrderTable::FT_PHYSICAL_PERSON)
            return 'Физическое лицо';
        elseif ($this->getFt() === OrderTable::FT_LEGAL_ENTITY)
            return 'Юридическое лицо';
        else return '';
    }

    public function getTextDelivery(): string
    {
        if ($this->getDelivery() === OrderTable::DELIVERY)
            return 'Доставка';
        elseif ($this->getDelivery() === OrderTable::SELF)
            return 'Самовывоз';
        else return '';
    }

    public function getTotalSum(): float
    {
        $this->fillItems();
        $sum = 0;
        foreach ($this->getItems() as $item) {
            $sum += $item->getPrice() * $item->getCount();
        }
        return $sum;
    }

}
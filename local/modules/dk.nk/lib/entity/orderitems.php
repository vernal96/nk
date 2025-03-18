<?php

namespace DK\NK\Entity;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;

class OrderItemsTable extends DataManager
{

    public static function getTableName(): string
    {
        return 'dk_order_items';
    }

    /**
     * @throws SystemException
     * @throws ArgumentException
     */
    public static function getMap(): array
    {
        return [
            (new Fields\IntegerField('ID'))->configurePrimary()->configureAutocomplete(),
            (new Fields\IntegerField('ORDER_ID'))->configureRequired(),
            (new Fields\IntegerField('ITEM_ID'))->configureRequired(),
            (new Fields\IntegerField('COUNT'))->configureRequired(),
            (new Fields\FloatField('PRICE'))->configureRequired(),
            (new Fields\Relations\Reference('ORDER', OrderTable::class, Join::on('this.ORDER_ID', 'ref.ID'))),
            (new Fields\Relations\Reference('SIZE', SizesTable::class, Join::on('this.ITEM_ID', 'ref.ID'))),
        ];
    }

}
<?php

namespace DK\NK\Entity;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\FileTable;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime as BitrixDateTime;
use Bitrix\Main\UserTable;
use DK\NK\Object\Order;

class OrderTable extends DataManager
{

    const FT_LEGAL_ENTITY = 'J';
    const FT_PHYSICAL_PERSON = 'P';

    const DELIVERY = 'D';
    const SELF = 'S';

    public static function getTableName(): string
    {
        return 'dk_order';
    }

    public static function getObjectClass(): string
    {
        return Order::class;
    }

    /**
     * @throws ArgumentTypeException
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return [
            (new Fields\IntegerField('ID'))->configurePrimary()->configureAutocomplete(),
            (new Fields\StringField('NAME'))->configureRequired(),
            (new Fields\StringField('PHONE'))->configureRequired(),
            (new Fields\StringField('EMAIL'))->configureNullable(),
            (new Fields\StringField('FT'))
                ->configureRequired()
                ->addValidator(new Fields\Validators\LengthValidator(1, 1)),
            (new Fields\StringField('DELIVERY'))
                ->configureRequired()
                ->addValidator(new Fields\Validators\LengthValidator(1, 1)),
            (new Fields\IntegerField('USER_ID'))->configureNullable(),
            (new Fields\IntegerField('CITY_ID'))->configureNullable(),
            (new Fields\StringField('STREET'))->configureNullable(),
            (new Fields\StringField('HOUSE'))->configureNullable(),
            (new Fields\StringField('CORPUS'))->configureNullable(),
            (new Fields\StringField('ENTRANCE'))->configureNullable(),
            (new Fields\IntegerField('OFFICE'))->configureNullable(),
            (new Fields\IntegerField('MARKET_ID'))->configureNullable(),
            (new Fields\TextField('COMMENT'))->configureNullable(),
            (new Fields\StringField('INN'))->configureNullable(),
            (new Fields\IntegerField('FILE_ID'))->configureNullable(),
            (new Fields\DatetimeField('CREATED_DATE'))->configureDefaultValue(new BitrixDateTime()),
            (new Fields\BooleanField('REGISTERED_IC'))
                ->configureValues('N', 'Y')
                ->configureDefaultValue(false),
            (new Fields\IntegerField('BITRIX_ID'))->configureNullable(),
            (new Fields\Relations\OneToMany('ITEMS', OrderItemsTable::class, 'ORDER')),
            (new Fields\Relations\Reference('USER', UserTable::class, Join::on('this.USER_ID', 'ref.ID'))),
            (new Fields\Relations\Reference('FILE', FileTable::class, Join::on('this.FILE_ID', 'ref.ID'))),
            (new Fields\Relations\Reference('CITY', DeliveryCitiesTable::class, Join::on('this.CITY_ID', 'ref.ID'))),
            (new Fields\Relations\Reference('MARKET', ElementTable::class, Join::on('this.MARKET_ID', 'ref.ID'))),
        ];
    }

}
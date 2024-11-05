<?php

namespace DK\NK\Entity;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\UserTable;

class CartTable extends DataManager
{
    public static function getTableName(): string
    {
        return 'dk_user_cart';
    }

    public static function getMap(): array
    {
        return [
            (new Fields\IntegerField("ID"))->configurePrimary()->configureAutocomplete(),
            (new Fields\IntegerField("USER_ID")),
            (new Fields\ArrayField("CART"))
                ->configureNullable()
                ->configureSerializeCallback("serialize")
                ->configureUnserializeCallback("unserialize"),
            (new Fields\Relations\Reference("USER", UserTable::class, Join::on("this.USER_ID", "ref.ID")))
        ];
    }
}
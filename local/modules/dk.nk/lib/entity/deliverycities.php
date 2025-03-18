<?php
namespace DK\NK\Entity;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields;

class DeliveryCitiesTable extends DataManager
{

    public static function getTableName(): string
    {
        return 'dk_delivery_cities';
    }


    public static function getMap(): array
    {
        return [
            (new Fields\IntegerField('ID'))->configurePrimary()->configureAutocomplete(),
            (new Fields\StringField('UF_NAME')),
            (new Fields\IntegerField('UF_SORT'))->configureDefaultValue(100)
        ];
    }
}
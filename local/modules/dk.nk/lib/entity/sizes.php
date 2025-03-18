<?php
namespace DK\NK\Entity;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;

class SizesTable extends DataManager
{

    public static function getTableName(): string
    {
        return 'dk_product_sizes';
    }

    /**
     * @throws SystemException
     * @throws ArgumentException
     */
    public static function getMap(): array
    {
        return [
            (new Fields\IntegerField('ID'))->configurePrimary()->configureAutocomplete(),
            (new Fields\StringField('UF_SIZE')),
            (new Fields\FloatField('UF_PRICE_1')),
            (new Fields\FloatField('UF_PRICE_2')),
            (new Fields\FloatField('UF_PRICE_3')),
            (new Fields\IntegerField('UF_SORT'))->configureDefaultValue(500),
            (new Fields\IntegerField('UF_PRODUCT')),
            (new Fields\StringField('UF_CODE')),
            (new Fields\StringField('UF_NAME')),
            (new Fields\StringField('UF_BOX_COUNT')),
            (new Fields\Relations\Reference(
                'PRODUCT', ElementTable::class, Join::on("this.UF_PRODUCT", "ref.ID"))
            )
        ];
    }
}
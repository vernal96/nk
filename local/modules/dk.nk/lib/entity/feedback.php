<?php

namespace DK\NK\Entity;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields;
use Bitrix\Main\Type\DateTime as BitrixDateTime;

class FeedbackTable extends DataManager
{

    public static function getTableName(): string
    {
        return 'dk_feedback';
    }

    public static function getMap(): array {
        return [
            (new Fields\IntegerField("ID"))->configurePrimary()->configureAutocomplete(),
            (new Fields\StringField("NAME"))->configureNullable(),
            (new Fields\StringField("PHONE"))->configureNullable(),
            (new Fields\StringField("EMAIL"))->configureNullable(),
            (new Fields\TextField("COMMENT"))->configureNullable(),
            (new Fields\IntegerField('BITRIX_ID'))->configureNullable(),
            (new Fields\DatetimeField('CREATED_DATE'))->configureDefaultValue(new BitrixDateTime()),
        ];
    }

}
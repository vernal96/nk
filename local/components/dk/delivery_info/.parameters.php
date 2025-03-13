<?php

use Bitrix\Main\Localization\Loc;
use DK\NK\Helper\Main;

$arComponentParameters = [
    "PARAMETERS" => [
        "ITEMS" => [
            "NAME" => Loc::getMessage("ABOUT_PROPERTIES"),
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "MULTIPLE" => "Y",
            "VALUES" => array_column(Main::getHLObject(HL_DELIVERY_INFO)::getList()->fetchAll(), "UF_TITLE", "ID")
        ],
        "CACHE_TIME" => ["DEFAULT" => CACHE_TIME]
    ]
];
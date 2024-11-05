<?php

use Bitrix\Main\Localization\Loc;
use DK\NK\Helper\Main;

$arComponentParameters = [
    "GROUPS" => [
        "IMAGE" => [
            "NAME" => Loc::getMessage("ABOUT_IMAGE_GROUP_NAME")
        ]
    ],
    "PARAMETERS" => [
        "LINK" => [
            "NAME" => Loc::getMessage("ABOUT_LINK"),
            "PARENT" => "BASE",
            "type" => "STRING"
        ],
        "IMAGE" => [
            "PARENT" => "IMAGE",
            "NAME" => Loc::getMessage("ABOUT_IMAGE"),
            "TYPE" => "FILE",
            "FD_TARGET" => "F",
            "FD_UPLOAD" => true,
            "FD_USE_MEDIALIB" => true
        ],
        "IMAGE_ALT" => [
            "PARENT" => "IMAGE",
            "NAME" => Loc::getMessage("ABOUT_IMAGE_ALT"),
            "type" => "STRING"
        ],
        "IMAGE_TITLE" => [
            "PARENT" => "IMAGE",
            "NAME" => Loc::getMessage("ABOUT_IMAGE_TITLE"),
            "type" => "STRING"
        ],
        "PROPERTIES" => [
            "NAME" => Loc::getMessage("ABOUT_PROPERTIES"),
            "PARENT" => "BASE",
            "TYPE" => "LIST",
            "ADDITIONAL_VALUES" => "Y",
            "MULTIPLE" => "Y",
            "VALUES" => array_column(Main::getHLObject(HL_ABOUT_PROPERTIES)::getList(["order" => ["UF_SORT" => "ASC"]])->fetchAll(), "UF_TEXT", "ID")
        ],
        "CACHE_TIME" => ["DEFAULT" => CACHE_TIME]
    ]
];
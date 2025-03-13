<?php

namespace DK\NK\Handler\Main;

use Bitrix\Main\Application;
use Bitrix\Main\FileTable;
use Bitrix\Main\Localization\Loc;
use ErrorException;

class OnFileSave
{

    public static function run(&$arFile): void
    {
        $request = Application::getInstance()->getContext()->getRequest()->getPost("controlID");
        if ($request === "parser_images") {
            $arFile["MODULE_ID"] = NK_MODULE_NAME;
            $files = FileTable::getList([
                "filter" => [
                    "MODULE_ID" => NK_MODULE_NAME,
                    "ORIGINAL_NAME" => $arFile["ORIGINAL_NAME"]
                ]
            ])->fetchAll();
            if (count($files) > 0) {
                throw new ErrorException(Loc::getMessage("NK_ONFILESAVE_FILE_EXISTS"));
            }
        }
    }

}
<?php

namespace DK\NK\BX24\Handler;

use DK\NK\Services\Bitrix24;

class OnCrmRequisiteAdd
{
    public static function run(int $id): bool
    {
        $requisites = Bitrix24::getInstance()->query("crm.requisite.get", ["id" => $id], "result");
        if ($requisites["PRESET_ID"] == 1) {
            $result = "ИНН {$requisites["RQ_INN"]}, КПП {$requisites["RQ_KPP"]}";
        } else {
            $result = "ИНН {$requisites["RQ_INN"]}";
        }
        Bitrix24::getInstance()->query("crm.requisite.update", ["id" => $id, "fields" => [
            "UF_CRM_1709661901" => $result
        ]]);
        return true;
    }
}
<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Localization\Loc;
use DK\NK\Helper\Main;
use DK\NK\Services\Bitrix24;
use DK\NK\Valid;

class NkAjaxForm extends CBitrixComponent implements Controllerable
{

    private array $validFields = [
        "tel" => "phone",
    ];

    public function executeComponent(): void
    {
        $this->arResult["TEL"] = $this->request["tel"];
        $this->arResult["NAME"] = $this->request["name"];
        $this->includeComponentTemplate();
    }

    public function configureActions(): array
    {
        return [
            'submit' => [
                "prefilters" => [new Csrf()]
            ]
        ];
    }

    public function submitAction(): array
    {
        $horizontal = $this->request->get("st") == 1;
        $error = false;
        if (!check_bitrix_sessid()) {
            throw new Exception("");
        }
        if (!Valid::reV3($this->request["g-token"])) {
            ob_start();
            Main::include("form_success", [
                "horizontal" => $horizontal,
                "error" => true,
                "successTitle" => Loc::getMessage("ERROR_RV3_TITLE"),
                "successDescription" => Loc::getMessage("ERROR_RV3_DESCRIPTION")
            ]);
            return [
                "success" => false,
                "message" => ob_get_contents()
            ];
        }
        foreach ($this->validFields as $fieldName => $validName) {
            if (!Valid::{$validName}($this->request[$fieldName])) {
                return [
                    "success" => false,
                    "field" => $fieldName
                ];
            }
        }
        $dealId = $this->sendBX24($this->request);
        $formatDealId = str_pad($dealId, 6, "0", STR_PAD_LEFT);
        ob_start();
        Main::include("form_success", [
            "horizontal" => $horizontal,
            "error" => $error,
            "formatDealId" => $formatDealId,
        ]);
        return [
            "success" => true,
            "message" => ob_get_contents()
        ];
    }

    private function sendBX24($request): ?int
    {
        $comment = $request["comment"] ?? "";
        $bx24 = new Bitrix24();
        $contactList = \DK\NK\Helper\Bitrix24::addContact([
            "name" => $request["name"],
            "phone" => $request["tel"],
            "email" => $request["email"]
        ], $bx24);
        $bx24->batchAdd("deal", "crm.deal.add", ["fields" => [
            "TITLE" => Loc::getMessage("NEW_DEAL") . ". " . date('d.m.Y H:i'),
            "TYPE_ID" => "SERVICE",
            "CATEGORY_ID" => 7,
            "STAGE_ID" => "C7:NEW",
            "ASSIGNED_BY_ID" => Option::get(NK_MODULE_NAME, "BX24_FEEDBACK_RESPONSIBLE"),
            "COMMENTS" => $comment,
            "OPENED" => "Y", // Заменить на Y доступно для всех
            "SOURCE_ID" => "WEB",
        ]], 20);
        $bx24->batchAdd("deal.contact", "crm.deal.contact.add", [
            "id" => '$result[deal]',
            "fields" => [
                "CONTACT_ID" => empty($contactList) ? '$result[contact]' : $contactList[0]["ID"]
            ]
        ], 30);
        $bx24->batchCall();
        if ($bx24->batchResult[0]["result"]["result"]["deal"]) return $bx24->batchResult[0]["result"]["result"]["deal"];
        throw new Exception(Loc::getMessage("ERROR_SUBMIT_BX24"));
    }

}
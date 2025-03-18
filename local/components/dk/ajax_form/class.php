<?php

use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Mail\Event;
use DK\NK\Entity\FeedbackTable;
use DK\NK\Helper\Main;
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

    /**
     * @throws Exception
     */
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
        $dealId = FeedbackTable::createObject()
            ->setName($this->request["name"] ?: null)
            ->setPhone($this->request["tel"] ?: null)
            ->setEmail($this->request["email"] ?: null)
            ->setComment($this->request["comment"] ?: null)
            ->save()
            ->getId();
        $this->sendEmail($dealId);
        ob_start();
        Main::include("form_success", [
            "horizontal" => $horizontal,
            "error" => $error,
            "formatDealId" => Main::getApplicationFormat($dealId),
        ]);
        return [
            "success" => true,
            "message" => ob_get_contents()
        ];
    }

    private function sendEmail(int $id): void {
        Event::send([
            'EVENT_NAME' => 'FEEDBACK_FORM',
            'C_FIELDS' => [
                'NUMBER' => Main::getApplicationFormat($id),
                'ID' => $id
            ],
            'LID' => SITE_ID
        ]);
    }

}
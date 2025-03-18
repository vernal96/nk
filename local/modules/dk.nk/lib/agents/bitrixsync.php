<?php

namespace DK\NK\Agents;

use Bitrix\Main\Config\Option;
use DK\NK\Entity\FeedbackTable;
use DK\NK\Services\Bitrix24;
use DK\NK\Helper;
use Throwable;

class BitrixSync
{

    public static function sendFeedback(): string {
        if (Option::get(NK_MODULE_NAME, 'BX24_DISABLED')) return __METHOD__ . '();';
        try {
            $feedbacks = FeedbackTable::query()
                ->setSelect(['*'])
                ->whereNull('BITRIX_ID')
                ->fetchCollection();
            foreach ($feedbacks as $feedback) {
                $comment = $request["comment"] ?? "";
                $bx24 = new Bitrix24();
                $contactList = Helper\Bitrix24::addContact([
                    "name" => $feedback->getName(),
                    "phone" => $feedback->getPhone(),
                    "email" => $feedback->getEmail()
                ], $bx24);
                $bx24->batchAdd("deal", "crm.deal.add", ["fields" => [
                    "TITLE" => 'Заявка с сайта от ' . $feedback->getCreatedDate()->format('d.m.Y H:i'),
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
                $dealId = (int)$bx24->batchResult[0]["result"]["result"]["deal"];
                if ($dealId) {
                    $feedback->setBitrixId($dealId)->save();
                }
            }
        } catch (Throwable $exception) {
            addUncaughtExceptionToLog($exception);
        } finally {
            return __METHOD__ . '();';
        }

    }

}
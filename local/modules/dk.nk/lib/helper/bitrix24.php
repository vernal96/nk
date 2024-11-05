<?php

namespace DK\NK\Helper;

use Bitrix\Main\Config\Option;
use DK\NK\Services\Bitrix24 as BX24;
use DK\NK\Services\DaData;

class Bitrix24
{

    /**
     * @param array $data phone, email, name
     */

    public static function addContact(array $data = [], BX24 $classInstance = null, int $order = 10): array
    {
        $contactFilter = [];
        $fields = [];
        $phone = "";
        $email = "";
        $bx24 = $classInstance ?: new BX24();
        if ($data["email"]) {
            $email = $contactFilter["EMAIL"] = $data["email"];
        }
        if ($data["phone"]) {
            $phone = $contactFilter["PHONE"] = Main::getPhone($data["phone"]);
        }
        $contactList = $bx24->query("crm.contact.list", [
            "filter" => $contactFilter,
        ], "result");
        if (empty($contactList)) {
            $fields["NAME"] = $data["name"];
            $fields["CREATED_BY_ID"] = Option::get(NK_MODULE_NAME, "BX24_CREATOR");
        }
        if ($email) {
            $fields["EMAIL"] = [
                [
                    "VALUE" => $email,
                    "VALUE_TYPE" => "OTHER"
                ]
            ];
        }
        if ($phone) {
            $fields["PHONE"] = [
                [
                    "VALUE" => $phone,
                    "VALUE_TYPE" => "OTHER"
                ]
            ];
        }
        if ($data["companyId"]) {
            $fields["COMPANY_ID"] = $data["companyId"];
        }

        if ($classInstance) {
            if (empty($contactList)) {
                $classInstance->batchAdd("contact", "crm.contact.add", [
                    "fields" => $fields
                ], $order);
            } else {
                $classInstance->batchAdd("contact", "crm.contact.update", [
                    "id" => $contactList[0]["ID"],
                    "fields" => $fields
                ], $order);
            }
            return $contactList;
        } else {
            if (empty($contactList)) {
                return $bx24->query("crm.contact.add", [
                    "fields" => $fields
                ]);
            } else {
                return $bx24->query("crm.contact.update", [
                    "id" => $contactList[0]["ID"],
                    "fields" => $fields
                ]);
            }
        }
    }

    public static function addCompany(array $data): int
    {
        $bx24 = new BX24();
        $requisites = $bx24->query("crm.requisite.list", [
            "filter" => [
                "RQ_INN" => $data["inn"]
            ]
        ], "result.0");
        if (empty($requisites)) {
            $companyInfo = DaData::getRqByInn($data["inn"]);
            $bx24->batchAdd("company", "crm.item.add", [
                "entityTypeId" => 4,
                "fields" => [
                    "title" => $companyInfo["name"]["short_with_opf"],
                    "industry" => "OTHER",
                    "createdBy" => Option::get(NK_MODULE_NAME, "BX24_CREATOR"),
                    "assignedById" => Option::get(NK_MODULE_NAME, "BX24_CREATOR"),
                ],
                "industry" => "OTHER",
            ], 0);

            if ($data["email"]) {
                $bx24->batchAdd("companyContacts", "crm.company.update", [
                    "id" => '$result[company][item][id]',
                    "fields" => [
                        "EMAIL" => array_map(function ($email) {
                            return [
                                "VALUE_TYPE" => "WORK",
                                "VALUE" => $email,
                                "TYPE_ID" => "EMAIL"
                            ];
                        }, $data["email"])
                    ]
                ], 1);
            }

            $requisitesData = $companyInfo["type"] != "INDIVIDUAL" ? [
                "ENTITY_TYPE_ID" => 4,
                "ENTITY_ID" => '$result[company][item][id]',
                "PRESET_ID" => 1,
                "NAME" => $companyInfo["name"]["short_with_opf"],
                "RQ_COMPANY_NAME" => $companyInfo["name"]["short_with_opf"],
                "RQ_COMPANY_FULL_NAME" => $companyInfo["name"]["full_with_opf"],
                "RQ_COMPANY_REG_DATE" => $companyInfo["state"]["registration_date"],
                "RQ_DIRECTOR" => $companyInfo["management"]["name"] ?: "",
                "RQ_CEO_NAME" => $companyInfo["management"]["name"] ?: "",
                "RQ_CEO_WORK_POS" => $companyInfo["management"]["post"] ?: "",
                "RQ_INN" => $companyInfo["inn"],
                "RQ_KPP" => $companyInfo["kpp"] ?: "",
                "RQ_OGRN" => $companyInfo["ogrn"],
                "RQ_OKPO" => $companyInfo["okpo"],
                "RQ_OKTMO" => $companyInfo["oktmo"],
                "RQ_OKVED" => $companyInfo["okved"],
            ] : [
                "ENTITY_TYPE_ID" => 4,
                "ENTITY_ID" => '$result[company][item][id]',
                "PRESET_ID" => 3,
                "NAME" => $companyInfo["name"]["short_with_opf"],
                "RQ_COMPANY_NAME" => $companyInfo["name"]["short_with_opf"],
                "RQ_COMPANY_FULL_NAME" => $companyInfo["name"]["full_with_opf"],
                "RQ_COMPANY_REG_DATE" => $companyInfo["state"]["registration_date"],
                "RQ_FIRST_NAME" => $companyInfo["fio"]["name"],
                "RQ_LAST_NAME" => $companyInfo["fio"]["surname"],
                "RQ_SECOND_NAME" => $companyInfo["fio"]["patronymic"],
                "RQ_INN" => $companyInfo["inn"],
                "RQ_OKPO" => $companyInfo["okpo"],
                "RQ_OGRNIP" => $companyInfo["ogrn"],
                "RQ_OKTMO" => $companyInfo["oktmo"],
                "RQ_OKVED" => $companyInfo["okved"],
            ];
            $addressData = $companyInfo["type"] != "INDIVIDUAL" ? [
                "TYPE_ID" => 6,
                "ENTITY_TYPE_ID" => 8,
                "ENTITY_ID" => '$result[requisites]',
                "ADDRESS_1" => $companyInfo["address"]["data"]["street_with_type"] . ", " . $companyInfo["address"]["data"]["house_type"] . ". " . $companyInfo["address"]["data"]["house"],
                "CITY" => $companyInfo["address"]["data"]["city_with_type"],
                "POSTAL_CODE" => $companyInfo["address"]["data"]["postal_code"],
                "PROVINCE" => $companyInfo["address"]["data"]["region_with_type"],
                "REGION" => $companyInfo["address"]["data"]["federal_district"],
                "COUNTRY" => $companyInfo["address"]["data"]["country"],
                "COUNTRY_CODE" => $companyInfo["address"]["data"]["country_iso_code"]
            ] : [
                "TYPE_ID" => 6,
                "ENTITY_TYPE_ID" => 8,
                "ENTITY_ID" => '$result[requisites]',
                "CITY" => $companyInfo["address"]["data"]["city_with_type"],
                "POSTAL_CODE" => $companyInfo["address"]["data"]["postal_code"],
                "PROVINCE" => $companyInfo["address"]["data"]["region_with_type"],
                "REGION" => $companyInfo["address"]["data"]["federal_district"],
                "COUNTRY" => $companyInfo["address"]["data"]["country"],
                "COUNTRY_CODE" => $companyInfo["address"]["data"]["country_iso_code"]
            ];
            $bx24->batchAdd("requisites", "crm.requisite.add", [
                "fields" => $requisitesData
            ], 30);
            $bx24->batchAdd("address", "crm.address.add", [
                "fields" => $addressData
            ], 40);
            $bx24->batchCall();
            return (int)$bx24->batchResult["result"]["result"]["company"]["item"]["id"];
        } else {
            return (int)$requisites["ENTITY_ID"];
        }
    }

}
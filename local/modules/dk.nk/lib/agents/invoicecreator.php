<?php

namespace DK\NK\Agents;

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use CDataXML;
use CIBlockElement;
use DK\NK\Helper\Main;
use DK\NK\Services\Bitrix24;
use Throwable;

class InvoiceCreator
{

    static public string $path = "/upload/integration/inovice/";
    static private int $crmDocumentTemplateSimple = 69;
    static private int $crmDocumentTemplateSimpleDiscount = 67;

    static function run(): string
    {
        $files = Main::getDirFiles(self::$path);
        if (!$files) return self::class . "::run();";
        Loader::includeModule("iblock");
        $xml = new CDataXML();
        foreach (Main::getDirFiles(self::$path) as $file) {
            try {
                $xml->Load($file);
                $xmlData = $xml->GetTree()->children[0]->children()[0]->children()[0]->children();
                $xmlHeader = array_shift($xmlData);
                // data init
                $title = trim($xmlHeader->getAttribute("НомерДок"));
                $number = preg_replace("/^[a-z]+0*/i", "", $title);
                $marketInfo = self::getMarketInfo($title);
                $emailList = self::getContactArray($xmlHeader->getAttribute("E-mail2"));

                $responsibleId = self::getUserByInitials(trim($xmlHeader->getAttribute("Фамилия")), trim($xmlHeader->getAttribute("Имя")));

                $inn = trim($xmlHeader->getAttribute("ИНН2"));
                $discount = (int)$xmlHeader->getAttribute("Скидка");

                $companyId = \DK\NK\Helper\Bitrix24::addCompany([
                    "inn" => $inn,
                    "email" => $emailList
                ]);

                $totalSum = $xmlHeader->getAttribute("СуммаВзаиморасчетов");
                $date = (new DateTime($xmlHeader->getAttribute("ДатаДок"), "d.m.Y"))->format("c");
                $productsData = [];
                foreach ($xmlData as $product) {
                    $price = (double)$product->getAttribute("Цена");
                    $productsData[] = [
                        "productName" => trim($product->getAttribute("Номенклатура")),
                        "price" => $discount ? ($price - $price / 100 * $discount) : $price,
                        "quantity" => $product->getAttribute("Количество"),
                        "taxRate" => preg_replace("/\D/", "", $product->getAttribute("СтавкаНДС")),
                        "taxIncluded" => "Y",
                        "discountTypeId" => 2,
                        "discountRate" => $discount
                    ];
                }
                // end data init
                Bitrix24::getInstance()->batchAdd("invoice", "crm.item.add", [
                    "entityTypeId" => 31,
                    "fields" => [
                        "title" => "Счет № $number от " . $xmlHeader->getAttribute("ДатаДок"),
                        "accountNumber" => $title,
                        "assignedById" => $responsibleId,
                        "companyId" => $companyId,
                        "opportunity" => $totalSum,
                        "begindate" => $date,
                        "ufCrm_SMART_INVOICE_1708426397781" => $marketInfo["PHONE"],
                        "ufCrm_SMART_INVOICE_1708426411414" => $marketInfo["ADDRESS"] . "|" . $marketInfo["COORD"],
                        "ufCrm_SMART_INVOICE_1708426529567" => $marketInfo["EMAIL"],
                        "ufCrm_SMART_INVOICE_1709661293825" => $number
                    ]
                ], 5);
                Bitrix24::getInstance()->batchAdd("productrow", "crm.item.productrow.set", [
                    "ownerType" => "SI",
                    "ownerId" => '$result[invoice][item][id]',
                    "productRows" => $productsData
                ], 10);
                Bitrix24::getInstance()->batchAdd("document", "crm.documentgenerator.document.add", [
                    "templateId" => $discount ? self::$crmDocumentTemplateSimpleDiscount : self::$crmDocumentTemplateSimple,
                    "entityTypeId" => 31,
                    "entityId" => '$result[invoice][item][id]',
                    "values" => [],
                    "stampsEnabled" => 1
                ], 100);
                Bitrix24::getInstance()->batchCall();
                unlink($file);
            } catch (Throwable $throw) {
                Application::getInstance()->createExceptionHandlerLog()->write($throw, 5);
            }
        }
        return self::class . "::run();";
    }

    private static function getMarketInfo($number): array
    {
        preg_match("/^[a-z]+/i", $number, $marketType);
        $market = CIBlockElement::getList(
            [],
            [
                "IBLOCK_ID" => IBLOCK_MARKET,
                "CODE" => $marketType[0]
            ], false, false, ["ID", "NAME", "IBLOCK_ID"]
        )->GetNextElement();
        $arMarket = $market->GetFields();
        $arMarket["PROPERTIES"] = $market->GetProperties();
        return [
            "ADDRESS" => $arMarket["NAME"],
            "PHONE" => $arMarket["PROPERTIES"]["PHONE"]["VALUE"],
            "EMAIL" => $arMarket["PROPERTIES"]["EMAIL"]["VALUE"],
            "COORD" => str_replace(",", ";", $arMarket["PROPERTIES"]["COORD"]["VALUE"])
        ];
    }

    private static function getContactArray(string $contactString): array
    {
        $contactArray = explode(",", $contactString);
        return array_filter(array_map(function ($item) {
            return trim($item);
        }, $contactArray), fn($item) => $item != "");
    }

    private static function getUserByInitials($lastName, $nameInitial): int
    {
        $result = Bitrix24::getInstance()->query("user.search", [
            "FILTER" => [
                "LAST_NAME" => $lastName
            ]
        ], "result");
        if (empty($result)) return Option::get("NK_MODULE_NAME", "BX24_CREATOR");
        if (count($result) == 1) return current($result)["ID"];
        $nameInitial = preg_replace("/[^а-я]+/ui", "", $nameInitial);
        $filterResult = array_filter($result, fn($item) => preg_match("/^" . $nameInitial . "/iu", $item["NAME"]));
        if (!empty($filterResult)) {
            return current($filterResult)["ID"];
        } else {
            return current($result)["ID"];
        }
    }

}
<?php

namespace DK\NK;

use Bitrix\Iblock\Iblock;
use Bitrix\Main\Config\Option;
use Bitrix\Main\SystemException;
use CFile;
use DK\NK\Entity\OrderTable;
use DK\NK\Helper\Bitrix24 as Bitrix24Helper;
use DK\NK\Helper\Main as MainHelper;
use DK\NK\Object\Order as ObjectOrder;
use DK\NK\Services\Bitrix24;
use Throwable;

class OrderBitrix24
{
    private Bitrix24 $bx24;
    private int $responsible;
    private ObjectOrder $order;

    const BX24_SELF_ID = 47;
    const BX24_DELIVERY_ID = 45;

    public function __construct(ObjectOrder $order)
    {
        $this->bx24 = new Bitrix24();
        $this->order = $order;
        $this->responsible = (int)Option::get(NK_MODULE_NAME, "BX24_FEEDBACK_RESPONSIBLE");
        $this->prepareOrderObject();
    }

    /**
     * @throws SystemException
     */
    public function addOrder(): int
    {
        $params = $this->getDeliveryData();
        $companyId = $this->getCompanyId();
        $contacts = Bitrix24Helper::addContact([
            'name' => $this->order->getName(),
            'phone' => $this->order->getPhone(),
            'email' => $this->order->getEmail(),
            'companyId' => $companyId
        ], $this->bx24);
        if ($this->order->getFileId()) {
            $file = CFile::GetFileArray($this->order->getFileId());
            $params['UF_CRM_1729236355'] = [
                'fileData' => [
                    $file['ORIGINAL_NAME'],
                    base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . $file['SRC']))
                ]
            ];
        }
        $this->bx24->batchAdd('deal', 'crm.deal.add', ['fields' => [
            'TITLE' => MainHelper::getApplicationFormat($this->order->getId()),
            'COMMENTS' => $userData['comment'] ?? '',
            'IS_NEW' => 'Y',
            'SOURCE_ID' => 'WEB',
            'CATEGORY_ID' => 7,
            'STAGE_ID' => 'C7:UC_Y3ERIM',
            'ASSIGNED_BY_ID' => $this->responsible,
            'OPPORTUNITY' => $this->order->getTotalSum(),
            'IS_MANUAL_OPPORTUNITY' => 'Y',
            'OPENED' => 'Y', // заменить
            'UF_CRM_1700415164161' => $this->getOrderFile(),
            ...$params
        ]], 20);

        $this->bx24->batchAdd("deal.contact", "crm.deal.contact.add", [
            "id" => '$result[deal]',
            "fields" => [
                "CONTACT_ID" => empty($contacts) ? '$result[contact]' : $contacts[0]["ID"]
            ]
        ], 30);

        if ($this->order->getItems()->count() > 0) {
            try {
                $this->bx24->batchAdd("products", "crm.deal.productrows.set", [
                    "id" => '$result[deal]',
                    "rows" => $this->addProductsToOrder()
                ]);
            } catch (Throwable $exception) {
                addUncaughtExceptionToLog($exception);
            }
        }

        $this->bx24->batchCall();



        $dealId = $this->bx24->batchResult[0]["result"]["result"]["deal"];

        if (!$dealId) {
            throw new SystemException('Bitrix 24 connect error!');
        }
        return (int)$dealId;
    }

    /**
     * @throws SystemException
     */
    private function addProductsToOrder(): array
    {
        $xmls = [];
        foreach ($this->order->getItems() as $item) {
            $size = $item->fillSize();
            $xmls[] = $size->getUfCode();
        }

        $bx24ProductsIdList = $this->bx24->query("crm.product.list", [
            "filter" => [
                "XML_ID" => $xmls
            ],
            "select" => ["XML_ID", "ID"]
        ], "result");

        if ($bx24ProductsIdList["error"]) throw new SystemException($bx24ProductsIdList["error_description"]);

        $rows = [];
        foreach ($this->order->getItems() as $item) {
            $bxFilter = array_filter($bx24ProductsIdList, fn($bxItem) => $bxItem["XML_ID"] == $item->getSize()->getUfCode());
            if (empty($bxFilter)) continue;
            $bxItem = current($bxFilter);
            $rows[] = [
                "PRODUCT_ID" => $bxItem["ID"],
                "PRICE" => $item->getPrice(),
                "QUANTITY" => $item->getCount()
            ];
        }

        return $rows;
    }

    private function prepareOrderObject(): void
    {
        $this->order->fillCity();
        $this->order->fillItems();
    }

    private function getCompanyId(): int
    {
        if ($this->order->getFt() === OrderTable::FT_LEGAL_ENTITY) {
            $companyId = Bitrix24Helper::addCompany([
                'inn' => $this->order->getInn(),
                'email' => [$this->order->getEmail()]
            ]);
        } else {
            $companyId = 0;
        }
        return $companyId;
    }

    private function getDeliveryData(): array
    {
        $result = [];
        try {
            $this->order->fillMarketId();
            if ($this->order->getDelivery() === OrderTable::SELF) {
                if ($this->order->getMarketId()) {
                    $market = Iblock::wakeUp(IBLOCK_MARKET)
                        ->getEntityDataClass()::getById($this->order->getMarketId())
                        ->fetchObject();
                    $market?->fill('RESPONSIBLE');
                    $responsible = $market?->get('RESPONSIBLE')?->getValue();
                    $this->responsible = $responsible ?: $this->responsible;
                    $result = [
                        'UF_CRM_1729167948' => $market?->get('NAME'),
                        'UF_CRM_1729167813' => self::BX24_SELF_ID
                    ];
                }
            } else {
                $result = [
                    'UF_CRM_1729167813' => self::BX24_DELIVERY_ID,
                    'UF_CRM_1729019615' => $this->order->getCity()?->getUfName(),
                    'UF_CRM_1729019691' => $this->order->getStreet(),
                    'UF_CRM_1729019698' => $this->order->getHouse(),
                    'UF_CRM_1729019705' => $this->order->getCorpus(),
                    'UF_CRM_1729019711' => $this->order->getEntrance(),
                    'UF_CRM_1729019720' => $this->order->getOffice()
                ];
            }
        } catch (Throwable $exception) {
            addUncaughtExceptionToLog($exception);
        } finally {
            return $result;
        }
    }

    private function getOrderFile(): ?array
    {
        $result = "Array\n(\n";
        $fileName = 'Заказ.csv';

        $items = $this->order->getItems();
        if (!$items) return null;

        foreach ($this->order->getItems() as $index => $item) {
            $size = $item->fillSize();
            $sum = $item->getCount() * $item->getPrice();
            $result .= "\t[$index] => ; {$size->getUfCode()} ; {$size->getUfName()} ; {$size->getUfSize()} ; {$size->get('UF_PRICE_1')} ; {$size->get('UF_PRICE_2')} ; {$size->get('UF_PRICE_3')} ; {$item->getCount()} ; $sum ;\n";
        }
        $result .= ")";

        $result = iconv("utf-8", "cp1251", $result);

        return [
            "fileData" => [
                $fileName,
                base64_encode($result)
            ]
        ];

    }

}
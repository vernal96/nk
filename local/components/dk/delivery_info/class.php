<?php

use DK\NK\Helper\Main as DkMain;

class DKDeliveryInfo extends CBitrixComponent
{

    public function executeComponent(): void
    {
        if ($this->startResultCache()) {
            $items = $this->arParams['ITEMS']
                ? DkMain::getHLObject(HL_DELIVERY_INFO)::query()
                    ->setSelect(['TITLE' => 'UF_TITLE', 'IMAGE' => 'UF_IMAGE', 'DESCRIPTION' => 'UF_DESCRIPTION'])
                    ->addOrder('UF_SORT')
                    ->whereIn('ID', $this->arParams['ITEMS'])
                    ->fetchAll()
                : [];

            $items = array_map(function($item) {
                $item['IMAGE'] = CFile::GetFileArray($item['IMAGE']);
                return $item;
            }, $items);

            $this->arResult['ITEMS'] = $items;

            $this->setResultCacheKeys([]);
            $this->includeComponentTemplate();
        }
    }

}

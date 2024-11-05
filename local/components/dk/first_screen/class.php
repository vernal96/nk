<?php

use Bitrix\Main\Application;
use DK\NK\Helper\Iblock;

class DKFirstScreenComponent extends CBitrixComponent
{

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent(): void
    {
        if ($this->startResultCache()) {
            Application::getInstance()->getTaggedCache()->startTagCache($this->getCachePath());
            $queryResult = CIblockElement::GetList(
                ["SORT" => "ASC", "DATE_CREATE" => "DESC"],
                [
                    "IBLOCK_ID" => IBLOCK_FS,
                    "ACTIVE" => "Y",
                    "ACTIVE_DATE" => "Y",
                ],
                false,
                false,
                ["ID", "NAME", "DETAIL_TEXT", "DETAIL_PICTURE", "IBLOCK_ID", "IBLOCK_SECTION_ID"]
            );

            while ($element = $queryResult->GetNextElement(true, false)) {
                $arElement = $element->fields;
                $arElement["PROPERTIES"] = $element->GetProperties();
                $arElement = Iblock::setResultFields($arElement, $addLink);
                $this->arResult[] = $arElement;
            }

            Application::getInstance()->getTaggedCache()->registerTag("iblock_id_" . IBLOCK_FS);
            Application::getInstance()->getTaggedCache()->endTagCache();
            $this->setResultCacheKeys([]);

            $this->includeComponentTemplate();
        }
    }

}
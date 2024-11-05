<?php


use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Application;

class DKSimpleComponent extends CBitrixComponent
{

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent(): void
    {
        global $APPLICATION;
        $pageCode = preg_replace(["/^\/?/", "/\/?$/"], "", $APPLICATION->GetCurPage());
        $taggedCache = Application::getInstance()->getTaggedCache();
        if ($this->startResultCache(false, $pageCode)) {
            $taggedCache->startTagCache($this->getCachePath());
            $element = ElementTable::getRow(["filter" => ["CODE" => $pageCode, "ACTIVE" => "Y"]]);
            if (!$element) {
                if ($APPLICATION->RestartWorkarea()) {
                    require(Application::getDocumentRoot() . "/404.php");
                    $this->abortResultCache();
                }
            } else {
                $panelButton = CIblock::GetPanelButtons($element["IBLOCK_ID"], $element["ID"]);
                $this->arResult = array_merge($element, [
                    "EDIT" => [
                        "ACTION_URL" => $panelButton["edit"]["edit_element"]["ACTION_URL"],
                        "TITLE" => $panelButton["edit"]["edit_element"]["TITLE"]
                    ]
                ]);
            }
            $taggedCache->registerTag("iblock_id_" . IBLOCK_SIMPLE);
            $taggedCache->endTagCache();
            $this->setResultCacheKeys([]);
            $this->includeComponentTemplate();
        }
    }

}
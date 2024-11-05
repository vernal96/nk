<?php

use DK\NK\Helper\Component;

class DKSocNetComponent extends CBitrixComponent
{

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent(): void
    {
        if ($this->startResultCache()) {
            $this->arResult = Component::getSocnet();
            $this->setResultCacheKeys([]);
            $this->includeComponentTemplate();
        }
    }

}
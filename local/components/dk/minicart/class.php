<?php

use DK\NK\Cart;

class DKMiniCartComponent extends CBitrixComponent
{

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent(): void
    {
        $sum = Cart::getInstance()->getTotalSum();
        $this->arResult["EMPTY"] = !$sum["value"];
        $this->arResult["TEXT"] = $sum["format"];
        $this->includeComponentTemplate();
    }

}
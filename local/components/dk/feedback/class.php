<?php

class DKFeedbackComponent extends CBitrixComponent
{

    public function __construct($component = null)
    {
        parent::__construct($component);
    }

    public function executeComponent(): void
    {
        $this->includeComponentTemplate();
    }

}
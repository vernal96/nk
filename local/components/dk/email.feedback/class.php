<?php

use DK\NK\Entity\FeedbackTable;
use DK\NK\Helper;

class EmailFeedbackComponent extends CBitrixComponent
{

    public function executeComponent(): void
    {
        try {
            $feedback = FeedbackTable::getById($this->arParams['ID'])->fetchObject();
            $this->arResult['NUMBER'] = Helper\Main::getApplicationFormat($feedback->getId());
            $fields = [];
            if ($feedback->getName()) $fields['Имя'] = $feedback->getName();
            if ($feedback->getEmail()) $fields['Email'] = $feedback->getEmail();
            if ($feedback->getPhone()) $fields['Телефон'] = $feedback->getPhone();
            if ($feedback->getComment()) $fields['Комментарий'] = $feedback->getComment();
            $this->arResult['FIELDS'] = $fields;
            $this->includeComponentTemplate();
        } catch (Throwable) {
        }
    }
}
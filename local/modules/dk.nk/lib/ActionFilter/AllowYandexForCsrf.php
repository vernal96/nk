<?php

namespace DK\NK\ActionFilter;

use Bitrix\Main\Context;
use Bitrix\Main\Engine\ActionFilter\Base;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;

class AllowYandexForCsrf extends Base
{

    public function onBeforeAction(Event $event): ?EventResult
    {
        $request = Context::getCurrent()->getRequest();
        $userAgent = $request->getUserAgent();

        if (str_contains($userAgent, 'Yandex') || $request->getHeader('X-Yandex')) {
            return new EventResult(EventResult::SUCCESS, null, null, $this);
        }

        return null;
    }

}
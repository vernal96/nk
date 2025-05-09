<?php

namespace DK\NK\ActionFilter;

use Bitrix\Main\Context;
use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Main\Engine\ActionFilter\Base;
use Bitrix\Main\Engine\ActionFilter\Csrf as BitrixCsrf;

class Csrf extends Base
{
    private BitrixCsrf $originalCsrf;

    public function __construct(
        bool $enabled = true,
        string $tokenName = 'sessid',
        bool $returnNew = true
    ) {
        $this->originalCsrf = new BitrixCsrf($enabled, $tokenName, $returnNew);
        parent::__construct();
    }

    public function onBeforeAction(Event $event)
    {
        $request = Context::getCurrent()->getRequest();
        $referer = $request->getHeader('referer');

        if ($this->isTrustedReferer($referer)) {
            return new EventResult(EventResult::SUCCESS, null, null, $this);
        }

        return $this->originalCsrf->onBeforeAction($event);
    }

    private function isTrustedReferer(?string $referer): bool
    {
        if (!$referer) {
            return false;
        }

        $pattern = '/^https?:\/\/([^\/]+\.)?(n-krep\.ru|webvisor\.com|metri[ck]a\.yandex\.(com|ru|by|com\.tr))\//i';

        return preg_match($pattern, $referer) === 1;
    }

    public function listAllowedScopes(): array
    {
        return $this->originalCsrf->listAllowedScopes();
    }
}
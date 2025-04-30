<?php

namespace DK\NK\Handler\Main;

class OnEndBufferContent
{

    public static function run(&$content): void
    {
        $pattern = '/(<link[^>]+rel=["\']canonical["\'][^>]+href=["\']https?:\/\/[^"\']*?\/)page-\d+\/([^"\']*["\'][^>]*>)/iu';
        $replacement = '$1$2';
        $content = preg_replace($pattern, $replacement, $content);
    }

}
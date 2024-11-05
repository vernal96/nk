<?php

namespace DK\NK\Handler\Rest;

use DK\NK\Rest\Handler;

class OnRestServiceBuildDescription
{
    public static function run(): array
    {
        return [
            "nk" => [
                "nk.handler" => [
                    "callback" => [Handler::class, "run"],
                    "options" => []
                ],
                "nk.bot.command" => [
                    "callback" => [Handler::class, "bot"],
                    "options" => []
                ],
            ]
        ];
    }
}
<?php

namespace DK\NK\Handler\Rest;

use DK\NK\Order;
use DK\NK\Rest\Handler;

class OnRestServiceBuildDescription
{
    public static function run(): array
    {
        return [
            'nk' => [
                'nk.handler' => [
                    'callback' => [Handler::class, 'run'],
                    'options' => []
                ],
                'nk.bot.command' => [
                    'callback' => [Handler::class, 'bot'],
                    'options' => []
                ],
                'nk.orders.list' => [
                    'callback' => [Order::class, 'getList'],
                    'options' => []
                ],
                'nk.orders.register.1c' => [
                    'callback' => [Order::class, 'registerIn1C'],
                    'options' => []
                ]
            ]
        ];
    }
}
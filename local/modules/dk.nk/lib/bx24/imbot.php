<?php

namespace DK\NK\BX24;

trait ImBot
{
    static protected function getKeyBoard(): array
    {
        return [
            [
                "TEXT" => "Ускорить загрузку счёта",
                "COMMAND" => "FASTINVOICE",
                "COMMAND_PARAMS" => "1",
                "BG_COLOR" => "#4caf50",
                "TEXT_COLOR" => "#fff",
                "DISPLAY" => "LINE"
            ]
        ];
    }
}
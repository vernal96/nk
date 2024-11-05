<?php

namespace DK\NK\Handler\Main;

class OnEpilog
{

    public static function run(): void
    {
        $page404 = "/404.php";
        global $APPLICATION;
        if (!str_contains($APPLICATION->GetCurPage(), $page404) && defined("ERROR_404") && ERROR_404 == "Y") {
            $APPLICATION->RestartBuffer();
            include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/header.php");
            include($_SERVER["DOCUMENT_ROOT"] . $page404);
            include($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/footer.php");
        }
    }
}
<? use Bitrix\Main\Localization\Loc;

/** @var array $arResult */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
CJSCore::Init();
?>
<?= $arResult['ERROR_MESSAGE']; ?>
<?
$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "flat",
    [
        "AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
        "AUTH_URL" => $arResult["AUTH_URL"],
        "POST" => $arResult["POST"],
        "POPUP" => "Y",
        "SUFFIX" => "form",
    ],
    $component,
    ["HIDE_ICONS" => "Y"]
);
?>
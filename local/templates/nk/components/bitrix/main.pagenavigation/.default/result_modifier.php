<?php

/** @var array $arResult */
$arResult["URL"] = preg_replace("/page-.+\//", "", $arResult["URL"]);
$arResult["URL_TEMPLATE"] = preg_replace("/" . $arResult["ID"] . "\//", "", $arResult["URL_TEMPLATE"]);
$arResult["URL_TEMPLATE"] = preg_replace("/page-\w+\//", "", $arResult["URL_TEMPLATE"]);
<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult)) {
    return "";
}

global $APPLICATION;
$result = "<ul class=\"breadcrumbs__wrapper\" itemscope itemtype=\"http://schema.org/BreadcrumbList\">";
foreach ($arResult as $index => $arItem) {
    $title = htmlspecialcharsex($arItem["TITLE"]);
    $result .= "<li itemprop=\"itemListElement\" itemscope itemtype=\"http://schema.org/ListItem\" ";
    $result .= "class=\"breadcrumbs__item" . (($arItem["LINK"] == "/") ? " breadcrumbs__item--home" : "") . "\"";
    $result .= ">";
    if ($arItem["LINK"] && $APPLICATION->GetCurPage() != $arItem["LINK"]) {
        $result .= "<a href=\"" . $arItem["LINK"] . "\" itemprop=\"item\">";
        $result .= "<span itemprop=\"name\">" . $title . "</span>";
        $result .= "</a>";
    } else {
        $result .= "<span itemprop=\"name\">" . $title . "</span>";
    }
    $result .= "<meta itemprop=\"position\" content=\"" . ($index + 1) . "\">";
    $result .= "</li>";
}
$result .= "</ul>";

return $result;
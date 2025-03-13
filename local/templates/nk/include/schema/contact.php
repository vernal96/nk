<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use DK\NK\Helper\Main;

/** @var array $PARAMS */
$arLocation = explode(",", $PARAMS["PROPERTIES"]["COORD"]["VALUE"]);

$timesAssoc = [
    "Понедельник - пятница" => '[
        "http://schema.org/Monday",
        "http://schema.org/Tuesday",
        "http://schema.org/Wednesday",
        "http://schema.org/Thursday",
        "http://schema.org/Friday"
    ]',
    "Суббота" => "\"http://schema.org/Saturday\"",
    "Воскресенье" => "\"http://schema.org/Sunday\"",
];

?>
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "<?= SITE_NAME; ?>",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "<?= $PARAMS["PROPERTIES"]["STREET"]["VALUE"] . ", дом " . $PARAMS["PROPERTIES"]["HOUSE"]["VALUE"]; ?>",
            "addressLocality": "<?= $PARAMS["PROPERTIES"]["CITY"]["VALUE"]; ?>",
            "addressCountry": "<?= $PARAMS["PROPERTIES"]["COUNTRY"]["VALUE"]; ?>",
            "addressRegion": "<?= $PARAMS["PROPERTIES"]["REGION"]["VALUE"]; ?>",
            "postalCode": "<?= $PARAMS["PROPERTIES"]["POST_INDEX"]["VALUE"]; ?>"
        },
        "email": "<?= $PARAMS["PROPERTIES"]["EMAIL"]["VALUE"]; ?>",
        "telephone": "<?= Main::getPhone($PARAMS["PROPERTIES"]["PHONE"]["VALUE"]); ?>",
        "openingHoursSpecification": [
    <? foreach ($PARAMS["PROPERTIES"]["TIMES"]["VALUE"] as $index => $value) : ?>
        <? $arTimes = explode("-", $value); ?>
{
             "@type": "OpeningHoursSpecification",
             "dayOfWeek": <?= $timesAssoc[$PARAMS["PROPERTIES"]["TIMES"]["DESCRIPTION"][$index]]; ?>,
             "opens": "<?= $arTimes[0]; ?>",
             "closes": "<?= $arTimes[1]; ?>"
            }<? if ($index + 1 != count($PARAMS["PROPERTIES"]["TIMES"]["VALUE"])) : ?>,<? endif; ?>
    <? endforeach; ?>
    ],
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": "<?= $arLocation[0]; ?>",
            "longitude": "<?= $arLocation[1]; ?>"
        }
    }
</script>
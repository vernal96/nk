<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

return [
    'css' => 'dist/sizes.bundle.css',
    'js' => 'dist/sizes.bundle.js',
    'rel' => [
		'main.polyfill.core',
		'dk.catalog.sizes',
	],
    'skip_core' => true,
];

<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

return [
    'css' => 'dist/product.bundle.css',
    'js' => 'dist/product.bundle.js',
    'rel' => [
		'main.polyfill.core',
		'dk.catalog.product.buttons.sizes',
		'dk.catalog.product.buttons.counter',
	],
    'skip_core' => true,
];

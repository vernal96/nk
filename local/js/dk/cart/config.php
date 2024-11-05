<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

return [
    'css' => 'dist/cart.bundle.css',
    'js' => 'dist/cart.bundle.js',
    'rel' => [
		'main.polyfill.core',
		'dk.ui.selector',
		'dk.catalog.sizes',
		'dk.main.methods',
	],
    'skip_core' => true,
];

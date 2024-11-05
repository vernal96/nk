<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

return [
    'css' => 'dist/main.bundle.css',
    'js' => 'dist/main.bundle.js',
    'rel' => [
		'main.polyfill.core',
		'ui.vue3',
		'dk.catalog.product',
		'dk.ui.pagination',
		'dk.main.methods',
	],
    'skip_core' => true,
];

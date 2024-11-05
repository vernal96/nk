<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

return [
    'css' => 'dist/main.bundle.css',
    'js' => 'dist/main.bundle.js',
    'rel' => [
		'main.polyfill.core',
		'dk.main.methods',
		'dk.cart',
	],
    'skip_core' => true,
];

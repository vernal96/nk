<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

return [
    'css' => 'dist/counter.bundle.css',
    'js' => 'dist/counter.bundle.js',
    'rel' => [
		'main.polyfill.core',
		'dk.main.methods',
	],
    'skip_core' => true,
];

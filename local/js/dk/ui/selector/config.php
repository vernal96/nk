<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

return [
    'css' => 'dist/selector.bundle.css',
    'js' => 'dist/selector.bundle.js',
    'rel' => [
		'main.polyfill.core',
		'dk.main.methods',
	],
    'skip_core' => true,
];

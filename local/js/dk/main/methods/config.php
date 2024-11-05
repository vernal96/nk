<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

return [
    'css' => 'dist/methods.bundle.css',
    'js' => 'dist/methods.bundle.js',
    'rel' => [
		'main.polyfill.core',
	],
    'skip_core' => true,
];

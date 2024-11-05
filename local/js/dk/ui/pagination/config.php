<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

return [
    'css' => 'dist/pagination.bundle.css',
    'js' => 'dist/pagination.bundle.js',
    'rel' => [
		'main.polyfill.core',
	],
    'skip_core' => true,
];

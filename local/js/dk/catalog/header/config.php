<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

return [
    'css' => 'dist/header.bundle.css',
    'js' => 'dist/header.bundle.js',
    'rel' => [
		'dk.main',
		'ui.vue3',
		'main.core',
	],
    'skip_core' => false,
];

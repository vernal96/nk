<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

return [
    'css' => 'dist/mm.bundle.css',
    'js' => 'dist/mm.bundle.js',
    'rel' => [
		'main.polyfill.core',
		'ui.vue3',
		'dk.main',
		'dk.ui.socnet',
	],
    'skip_core' => true,
];

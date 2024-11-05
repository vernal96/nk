<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

return [
    'css' => 'dist/socnet.bundle.css',
    'js' => 'dist/socnet.bundle.js',
    'rel' => [
		'main.polyfill.core',
	],
    'skip_core' => true,
];

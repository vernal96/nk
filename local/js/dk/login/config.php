<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/login.bundle.css',
	'js' => 'dist/login.bundle.js',
	'rel' => [
		'main.polyfill.core',
	],
	'skip_core' => true,
];

<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
{
	die();
}

return [
	'css' => 'dist/tinymce.bundle.css',
	'js' => 'dist/tinymce.bundle.js',
	'rel' => [
		'main.polyfill.core',
	],
	'skip_core' => true,
];

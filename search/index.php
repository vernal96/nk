<? require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php');
$APPLICATION->SetTitle("");
$arFilter = ['ACTIVE' => 'Y'];
?><?$APPLICATION->IncludeComponent(
	"arturgolubev:search.page", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"DEFAULT_SORT" => "rank",
		"USE_LANGUAGE_GUESS" => "Y",
		"arrFILTER" => array(
			0 => "iblock_catalog",
		),
		"arrFILTER_iblock_catalog" => array(
			0 => "2",
		),
		"SHOW_WHERE" => "N",
		"arrWHERE" => array(
			0 => "iblock_catalog",
		),
		"SHOW_WHEN" => "N",
		"SHOW_DATA_MODIFY" => "N",
		"SHOW_PROPS" => array(
		),
		"INPUT_PLACEHOLDER" => "",
		"SHOW_HISTORY" => "N",
		"PREVIEW_TEXT" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CHECK_DATES" => "Y",
		"FILTER_NAME" => "",
		"PAGE_RESULT_COUNT" => "24",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Результаты поиска",
		"PAGER_SHOW_ALWAYS" => "N"
	),
	false
);?>
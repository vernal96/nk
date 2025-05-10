<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Loader,
	\Bitrix\Main\Localization\Loc;

$arPreviewTypes = [
	"" => GetMessage("SP_BSP_PREVIEW_TEXT_DEFAULT"),
	"DETAIL_TEXT" => GetMessage("SP_BSP_PREVIEW_TEXT_DETAIL"),
	"PREVIEW_TEXT" => GetMessage("SP_BSP_PREVIEW_TEXT_PREVIEW"),
];


$arTemplateParameters["SHOW_DATA_MODIFY"] = [
	"PARENT" => "VISUAL",
	"NAME" => GetMessage("SP_BSP_SHOW_DATA_MODIFY"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "N"
];

$arTemplateParameters["SHOW_PROPS"] = [
	"NAME" => GetMessage("SP_BSP_SHOW_PROPS"),
	"TYPE" => "STRING",
	"DEFAULT" => "",
	"MULTIPLE" => "Y",
	"PARENT" => "VISUAL",
];

$arTemplateParameters["INPUT_PLACEHOLDER"] = [
	"NAME" => GetMessage("SP_BSP_INPUT_PLACEHOLDER"),
	"TYPE" => "STRING",
	"DEFAULT" => "",
	"PARENT" => "VISUAL",
];

$arTemplateParameters["SHOW_HISTORY"] = [
	"PARENT" => "VISUAL",
	"NAME" => GetMessage("SP_BSP_SHOW_HISTORY"),
	"TYPE" => "CHECKBOX",
	"DEFAULT" => "N"
];

$arTemplateParameters["PREVIEW_TEXT"] = [
	"PARENT" => "VISUAL",
	"NAME" => GetMessage("SP_BSP_PREVIEW_TEXT"),
	"TYPE" => "LIST",
	"VALUES" => $arPreviewTypes,
	"MULTIPLE" => "N",
	"DEFAULT" => ""
];


if (Loader::includeModule('catalog') && Loader::includeModule('currency')){
	$arPrice = [];
	$rsPrice = CCatalogGroup::GetList($v1="sort", $v2="asc");
	while($arr = $rsPrice->Fetch()){
		$arPrice[$arr["NAME"]] = "[".$arr["NAME"]."] ".$arr["NAME_LANG"];
	}
	
	$arTemplateParameters['PRICE_CODE'] = [
		"PARENT" => "VISUAL",
		"NAME" => Loc::getMessage("SP_BST_PRICE_CODE"),
		"TYPE" => "LIST",
		"MULTIPLE" => "Y",
		"VALUES" => $arPrice,
	];
	
	$arTemplateParameters['PRICE_VAT_INCLUDE'] = [
		"PARENT" => "VISUAL",
		"NAME" => Loc::getMessage("SP_BST_PRICE_VAT_INCLUDE"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
	];
	
	$arTemplateParameters['CONVERT_CURRENCY'] = [
		"PARENT" => "VISUAL",
		'NAME' => Loc::getMessage('SP_BST_CONVERT_CURRENCY'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y',
	];

	if (isset($arCurrentValues['CONVERT_CURRENCY']) && 'Y' == $arCurrentValues['CONVERT_CURRENCY'])
	{
		$arCurrencyList = [];
		$rsCurrencies = CCurrency::GetList(($by = 'SORT'), ($order = 'ASC'));
		while ($arCurrency = $rsCurrencies->Fetch()){
			$arCurrencyList[$arCurrency['CURRENCY']] = $arCurrency['CURRENCY'];
		}
		
		$arTemplateParameters['CURRENCY_ID'] = [
			"PARENT" => "VISUAL",
			'NAME' => Loc::getMessage('SP_BST_CURRENCY_ID'),
			'TYPE' => 'LIST',
			'VALUES' => $arCurrencyList,
			'DEFAULT' => CCurrency::GetBaseCurrency()
		];
	}
}
?>
